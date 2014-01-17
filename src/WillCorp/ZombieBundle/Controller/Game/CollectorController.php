<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Controller\Game;

use WillCorp\ZombieBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;

/**
 * Class CollectorController
 *
 * @Route("/game")
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class CollectorController extends Controller
{
    /**
     * Process a stronghold collect
     * This action is only available through XML HTTP requests (i.e. AJAX)
     *
     * @param Request            $request    The HTTP request object
     * @param StrongholdInstance $stronghold The stronghold object to collect
     *
     * @return Response
     *
     * @Route("/stronghold/{id}/collect",
     *      name="game_collect_stronghold",
     *      requirements={"id": "\d+"}
     * )
     */
    public function strongholdAction(Request $request, StrongholdInstance $stronghold)
    {
        $this->throwNotFoundUnless($this->isXmlHttpRequest($request));
        $this->throwNotFoundUnless($this->isPlayersStronghold($stronghold));

        $error = null;
        try {
            $this->getGameMechanic()->collectStronghold($stronghold);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->getJsonResponse($stronghold, $error);
    }

    /**
     * Process a building collect
     * This action is only available through XML HTTP requests (i.e. AJAX)
     *
     * @param Request          $request   The HTTP request object
     * @param BuildingInstance $building  The building object to collect
     *
     * @return Response
     *
     * @Route("/building/{id}/collect",
     *      name="game_collect_building",
     *      requirements={"id": "\d+"}
     * )
     */
    public function buildingAction(Request $request, BuildingInstance $building)
    {
        $this->throwNotFoundUnless($this->isXmlHttpRequest($request));
        $this->throwNotFoundUnless($this->isPlayersStronghold($building->getStronghold()));

        $error = null;
        try {
            $this->getGameMechanic()->collectBuilding($building);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->getJsonResponse($building, $error);
    }
}