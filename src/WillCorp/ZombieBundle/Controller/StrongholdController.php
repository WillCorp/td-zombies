<?php
/**
 * This file is part of the CloudyFlowBundle package
 *
 * (c) Sauvan William <sauvan.william@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Model\User as BaseUser;
use WillCorp\ZombieBundle\Entity\Player;

/**
 * @todo: provide class description
 * 
 * @author    Sauvan William <sauvan.william@gmail.com>
 * @copyright 2013 © Cloudy-Web-Creations <http://www.cloudy-web-creations.com>
 */
class StrongholdController extends Controller
{
    /**
     * @Route("/base", name="base_view")
     * @Template()
     */
    public function viewbaseAction()
    {
      
        $render_var = array();
        
        $player = $this->getUser();
      
        //set the current player as the good one
        $render_var["player"] = $player;
        
        //get the base information of that player
        //$stronghold = $repository = $this->getDoctrine()
        //                                 ->getRepository('WillCorpZombieBundle:Stronghold');
        
        return $this->render(
            'WillCorpZombieBundle:Specific:stronghold.html.twig',
            $render_var
        );
    }
    
    /**
     * @Route("/base/{id}", name="baseplayer_view"), defaults={"id" = null}
     * @Template()
     */
    public function viewbaseplayerAction(Player $player)
    {
      
        $render_var = array();
        
        if(!$player)
          $player = $this->getUser();

        $render_var["player"] = $player;
        $render_var["arrayvalues"] = $this->initBaseTable($player);
        
        return $this->render(
            'WillCorpZombieBundle:Specific:stronghold.html.twig',
            $render_var
        );
    }
    
    /**
     * Return an array with 
     * @param Player $player
     */
    private function initBaseTable($player){

      $arrayValues = array();
      $line = $column = 0;
      
      //init all cases as empty, will be remove later
      while($line < $player->getStronghold()->getLevel()->getLinesCount()){
        $arrayValues[$line] = array();
        while($column < $player->getStronghold()->getLevel()->getColumnsCount()){
          $arrayValues[$line][$column] = 0;
          
          $column++;
        }
        $column = 0;
        $line++;
      }

      //get all buildings and set their cases as busy
      foreach($player->getStronghold()->getBuildings() as $building){
        $roundStart = $roundStartBase = $building->getRoundStart();

        //set all cases to busy
        while($roundStart >= 0 && $roundStart > $roundStartBase - $building->getLevel()->getRoundCount()){

          $columnStart = $columnStartBase = $building->getColumnStart();
          
          while($columnStart < $player->getStronghold()->getLevel()->getColumnsCount() && 
              $columnStart < $columnStartBase + $building->getLevel()->getColumnsCount()){
            
            $arrayValues[$roundStart][$columnStart] = 1;
            
            $columnStart++;
          }
          
          $roundStart--;
        }
      }
      
      return $arrayValues;
      
    }
}