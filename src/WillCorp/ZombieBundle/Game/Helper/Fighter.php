<?php

namespace WillCorp\ZombieBundle\Game\Helper;

use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\UnitLevel;
/**
 * Class Resources
 *
 * @author Sauvan William <sauvan.william@gmail.com>
 */
class Fighter
{

    const ROUND = "round";
    const SPEED = "speed";
    const COLUMN = "column";
    const HEALTH = "health";
    const FINISHED = "finished";

    /**
     * This class should not be instantiated
     */
    private function __construct()
    {
    }
    
    /**
     * Check if the fight is finished
     * 
     * @param array $attack_matrix
     * @return boolean
     */
    public static function isFinished($attack_matrix)
    {
      
      //if there is still some attacking unit, the battle isn't finished
      foreach($attack_matrix as $unitid => &$unitInfos){
        if(self::isFINISHED($unitInfos)){
          return false;
        }
      }
      
      //some new end conditions can be defined here (like a "stop attacking" button)
      return true;
    }
    
    /**
     * Move the available unit to their speed
     * 
     * @param array $attack_matrix
     * @param the number of round of the current stronghold $finalRound
     */
    public static function moveAttachers(&$attack_matrix, $finalRound){
      
      foreach($attack_matrix as $unitid => &$unitInfos){
        
        if(!self::isFINISHED($unitInfos)){
          continue;
        }
        
        $unitInfos[self::ROUND] += $unitInfos[self::SPEED];

        if($unitInfos[self::ROUND] == $finalRound)
          $unitInfos[self::FINISHED] = false;
        
      }
      
    }
    
    /**
     * Check if a given unit is still alive and didn't reach the end of the battlfield
     * 
     * @param unknown $unitInfos
     * @return boolean
     */
    public static function isFINISHED($unitInfos){
      return ( $unitInfos[self::FINISHED] !== false ) && ( $unitInfos[self::HEALTH] > 0 ); 
    }
    
    /**
     * Deal damage to unit on a building spot
     * 
     * @param array $attack_matrix
     * @param array $battlefield
     */
    public static function processDamages(&$attack_matrix, $battlefield){
      
      foreach($attack_matrix as $unitid => &$unitInfos){
        
        if($buildinginstance = $battlefield[$unitInfos[self::ROUND]][$unitInfos[self::COLUMN]] !== false){
          $unitInfos[self::HEALTH] -= $buildinginstance->getLevel()->getDefense();//deal damages to the unit
          
        }
          
      }
      
    }
    
    /**
     * Set a unit position according to the correct keys
     * 
     * @param array $attack_matrix
     * @param UnitLevel $unit
     * @param int $round
     * @param int $column
     */
    public static function setUnitPosition(&$attack_matrix, UnitLevel $unit, $round, $column){
      
      $attack_matrix[self::ROUND] = $round;
      $attack_matrix[self::COLUMN] = $column;
      $attack_matrix[self::FINISHED] = true;
      $attack_matrix[self::HEALTH] = $unit->getHealth();
      $attack_matrix[self::ROUND] = $unit->getSpeed();
      
      
    }
    
    /**
     * Return true if the defending player won the fight or not
     * 
     * TODO : define how a player can lose
     * 
     * @param Player $defender
     * @param array $attack_matrix
     */
    public static function isDefenseSucced(Player $defender, array $attack_matrix){
      
      
      
    }
    
}