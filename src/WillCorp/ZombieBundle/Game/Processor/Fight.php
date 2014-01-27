<?php

namespace WillCorp\ZombieBundle\Game\Processor;

use Doctrine\ORM\EntityManager;
use WillCorp\ZombieBundle\Game\Helper\Fighter;
use WillCorp\ZombieBundle\Game\Helper\Resources;
use WillCorp\ZombieBundle\Entity\Player;
use WillCorp\ZombieBundle\Entity\BuildingInstance;

/**
 * Class Fight
 *
 * @author Sauvan William <william.sauvan@gmail.com>
 */
class Fight
{

    /**
     * Matrix with units and defenses positions
     * 
     * @var battlefield[round][column]
     */
    private $battlefield = array();

    /**
     * Process a fight
     */
    public function processFighting(Player $defender, Player $attackers, array $attack_matrix)
    {
        
        //start by initiating the matrix with defenses
        $this->initDefenseMatrix($defender);
        
        //get the final round
        $finalRound = $defender->getStronghold()->getLevel()->getLinesCount();
        
        $i = 0;
        while(!Fighter::isFinished($attack_matrix)){ // while the fight isn't finished, procces a round

          $attack_matrix = Fighter::moveAttachers($attack_matrix, $finalRound);
          
          $attack_matrix = Fighter::processDamages($attack_matrix, $this->battlefield);
          $i++;
          
        }
        
        $result = Fighter::getDefenseResult($defender, $attack_matrix);

        $purcent = Fighter::getPurcentStolen($defender, $result);
        
        //now calc the exact number of stolen res
        $currentres = $defender->getStronghold()->getResources();
        $costs = array();
        foreach(Resources::getResourcesTypes() as $ressourcetype){
          
          $valuestolen = $currentres[$ressourcetype] * $purcent;
          $costs[$ressourcetype] = $valuestolen;
          
        }
        
        //substract ressources to the defender
        $finalsupply = Resources::subtractResources($currentres, $costs);
        $defender->getStronghold()->setResources($finalsupply);
        
        //and add the same amount to the attacker
        $finalsupply = Resources::addResources($currentres, $costs);
        $attackers->getStronghold()->setResources($finalsupply);
        
        return $purcent;//return the purcent stolen to display easier the battle result
        
    }
    
    /**
     * Initiate the battlfield with all location to empty, depending on the player's stronghold
     * 
     * @param Player $defender
     */
    public function initMatrix(Player $defender)
    {
      
      //init the matrix with the correct stronghold size
      $roundLength = $defender->getStronghold()->getLevel()->getLinesCount();
      $columnLength = $defender->getStronghold()->getLevel()->getColumnsCount();

      for($round=1; $round<$roundLength; $round++){
        for($column=1; $column<$columnLength; $column++){
          $this->battlefield[$round][$column] = false; //set the position as empty
        }
      }
      
    }
    
    /**
     * Set up corresponding buildings in the position where they are
     * 
     * @param Player $defender the player to init the battlefield with
     */
    public function initDefenseMatrix(Player $defender)
    {
      
      $this->initMatrix($defender);
      
      //get all the defenses buildings
      foreach($defender->getStronghold()->getBuildings() as $buildinginstance){

        $roundStart = $buildinginstance->getRoundStart();
        $columnStart = $buildinginstance->getColumnStart();

        $roundLength = $buildinginstance->getLevel()->getRoundCount() + $roundStart;
        $columnLength = $buildinginstance->getLevel()->getColumnsCount() + $columnStart;
        
        $this->battlefield[$roundStart][$columnStart] = $buildinginstance; //define the building here
        
        for($round=$roundStart; $round<$roundLength; $round++){
          for($column=$columnStart; $column<$columnLength; $column++){
            $this->battlefield[$round][$column] = $buildinginstance; //and define the building for each cases
          }
        }
        
      }
      
    }
    
    /**
     * Simple battlefield getter
     * 
     * @return array
     */
    public function getBattelfield(){
      return $this->battlefield;
    }
    
}