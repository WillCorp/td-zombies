<?php

namespace WillCorp\ZombieBundle\Game\Processor;

use Doctrine\ORM\EntityManager;
use WillCorp\ZombieBundle\Game\Helper\Fighter;
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
     * Class constructor
     * Set the battlefield up with correct defenses positions
     *
     * @param EntityManager $em Doctrine entity manager
     */
    public function __construct(EntityManager $em) 
    {
    }

    /**
     * Process a fight
     */
    public function processFighting(Player $defender, array $attack_matrix)
    {
        
        //start by initiating the matrix with defenses
        $this->initDefenseMatrix($defender);
        
        //get the final round
        $finalRound = $defender->getStronghold()->getLevel()->getLinesCount();
        
        while(!Fighter::isFinished($attack_matrix)){ // while the fight isn't finished, procces a round
          
          Fighter::moveAttachers($attack_matrix, $finalRound);
          
          Fighter::processDamages($attack_matrix, $battlefield);
          
        }
        
    }
    
    /**
     * Initiate the battlfield with all location to empty, depending on the player's stronghold
     * 
     * @param Player $defender
     */
    private function initMatrix(Player $defender)
    {
      
      //init the matrix with the correct stronghold size
      $roundLength = $defender->getStronghold()->getLevel()->getLinesCount();
      $columnLength = $defender->getStronghold()->getLevel()->getColumnsCount();

      for($round=0; $round<$roundLength; $round++){
        for($column=0; $column<$columnLength; $column++){
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

        $roundLength = $buildinginstance->getLevel()->getLinesCount();
        $columnLength = $buildinginstance->getLevel()->getColumnsCount();
        
        $this->battlefield[$roundStart][$columnStart] = $buildinginstance; //define the building here
        
        for($round=$roundStart; $round<$roundLength; $round++){
          for($column=$columnStart; $column<$columnLength; $column++){
            $this->battlefield[$round][$column] = $buildinginstance; //and define the building for each cases
          }
        }
        
      }
      
    }
    
}