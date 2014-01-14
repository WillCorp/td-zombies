<?php

namespace WillCorp\ZombieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use WillCorp\ZombieBundle\Game\Mechanic;

/**
 * Class Controller
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Controller extends BaseController
{
    /**
     * Return the game mechanic service object
     *
     * @return Mechanic
     */
    public function getGameMechanic()
    {
        return $this->get('will_corp_zombie.game.mechanic');
    }

    /**
     * Get a formatted JSON response object
     *
     * @param string|object                               $content The response content
     * @param null|array|ConstraintViolationListInterface $errors  The errors (optionally)
     *
     * @return Response
     */
    public function getJsonResponse($content, $errors = null)
    {
        $result = array(
            'content' => null,
            'errors'  => array()
        );

        //Prepare content value
        $contentValue = $content;
        if (is_object($content)) {
            $contentValue = $this->serializeObjectToArray($content);
        }

        //Assign content value
        $result['content'] = $contentValue;

        if (!is_null($errors)) {
            //Prepare errors value
            $errorsValue = $errors;
            if (is_object($errors)) {
                if ($errors instanceof ConstraintViolationListInterface) {
                    foreach ($errors as $error) {
                        /* @var $error ConstraintViolationInterface */
                        $errorsValue[$error->getPropertyPath()] = $error->getMessage();
                    }
                }
            }

            $errorsValue = is_scalar($errorsValue) ? array($errorsValue) : $errorsValue;
            if (is_array($errorsValue)) {
                //Assign errors value
                $result['errors'] = $errorsValue;
            }
        }

        return new Response(json_encode($result));
    }

    /**
     * Serialize an object through JMS serializer and transform it to array
     *
     * @param object $object The object to serialize to array
     *
     * @return array
     */
    public function serializeObjectToArray($object)
    {
        return json_decode($this->get('jms_serializer')->serialize($object, 'json'), true);
    }

    /**
     * Throw a not found exception, unless the given $condition is TRUE
     *
     * @param callable|boolean $condition The condition to test
     * @param string           $message   The not found message to use
     *
     * @throws NotFoundHttpException
     */
    public function throwNotFoundUnless($condition, $message = 'Not Found')
    {
        $condition = (boolean) (is_callable($condition) ? call_user_func($condition) : $condition);

        if (!$condition) {
            throw $this->createNotFoundException($message);
        }
    }
}