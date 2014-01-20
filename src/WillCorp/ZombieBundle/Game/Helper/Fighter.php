<?php

namespace WillCorp\ZombieBundle\Game\Helper;

use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\UnitLevel;
use WillCorp\ZombieBundle\Entity\Player;
use WillCorp\ZombieBundle\Entity\UnitLevel;
/**
 * Class Resources
 *
 * @author Sauvan William <sauvan.william@gmail.com>
 */
class Fighter
{

    const ROUND = "round";
    const COLUMN = "column";
    const UNIT = "unit";
    const BUILDING = "building";
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
      
      foreach($attack_matrix as &$unitInfos){
        
        if(!self::isFINISHED($unitInfos)){
          continue;
        }
        
        $unitInfos[self::ROUND] += $unitInfos[self::UNIT]->getSpeed();

        if($unitInfos[self::ROUND] == $finalRound)
          $unitInfos[self::FINISHED] = true;
        
      }
      
    }
    
    /**
     * Check if a given unit is still alive and didn't reach the end of the battlfield
     * 
     * @param unknown $unitInfos
     * @return boolean
     */
    public static function isFINISHED($unitInfos){
      return ( $unitInfos[self::FINISHED] == false ) && ( $unitInfos[self::UNIT]->getHealth() > 0 ); 
    }
    
    /**
     * Deal damage to unit on a building spot. Also decrement the corresponding building's unit count
     * 
     * @param array $attack_matrix
     * @param array $battlefield
     * 
     */
    public static function processDamages(&$attack_matrix, $battlefield){
      
      
      foreach($attack_matrix as &$unitInfos){
        
        if($unitInfos[self::UNIT]->getHealth() <= 0){
          continue;
        }
        
        if($buildinginstance = $battlefield[$unitInfos[self::ROUND]][$unitInfos[self::COLUMN]] !== false){
          $unitInfos[self::UNIT]->getHealth() -= $buildinginstance->getLevel()->getDefense();//deal damages to the unit
        }

        if($unitInfos[self::UNIT]->getHealth() <= 0){
          self::unitCountDecrement($unitInfos[self::BUILDING]);
        }
      }
      
    }
    
    /**
     * Decrement te unit count of the given building
     * 
     * @param BuildingInstance $building
     * 
     * @throws \Exception If we try to decrement a unit count under 0
     */
    public static function unitCountDecrement(BuildingInstance $building){
      if($nbcurrent = $building->getUnitCount()<=0){
        throw new \Exception('There is no unit anymore !');
      }
      
      $building->setUnitCount($nbcurrent-1);
    }
    
    /**
     * Set a unit position according to the correct keys
     * 
     * @param array $attack_matrix
     * @param UnitLevel $unit
     * @param int $round
     * @param int $column
     */
    public static function setUnitPosition(&$attack_matrix, BuildingInstance $building, $round, $column){
      
      $arrayunit[self::ROUND] = $round;
      $arrayunit[self::COLUMN] = $column;
      $arrayunit[self::FINISHED] = false;
      $arrayunit[self::UNIT] = $building->getLevel()->getUnit();
      $arrayunit[self::BUILDING] = $building;
      
      $attack_matrix[] = $arrayunit;
      
    }
    
    /**
     * Return all the fallen column
     * 
     * @param Player $defender
     * @param array $attack_matrix
     * 
     * @return array The number list of fallen column
     * 
     */
    public static function getDefenseResult(Player $defender, array $attack_matrix){
      
      //get how many units succed to reach the end
      $array_countbycolumn = array();
      
      foreach($attack_matrix as $unitInfos){
        if($unitInfos[self::FINISHED]){
          $array_countbycolumn[$unitInfos[self::COLUMN]] += $unitInfos[self::UNIT]->getDamages();
        }
      }
      
      $columnCount = $defender->getStronghold()->getLevel()->getColumnsCount();
      
      $number_fallen = array();//create a list of all fallend colum
      for($i=0; $i < $columnCount; $i++){
        if(count($array_countbycolumn[$i]) > $defender->getStronghold()->getLevel()->getColumnLife() )
          $number_fallen[] = $i;
      }
      
      return $number_fallen;
      
    }
    
    /**
     * 
     * Return how many purcent of ressources has been stolen with a given list of fallen column
     * 
     * @param Player $defender
     * @param array $number_fallen A list with the index of fallen column
     * 
     * @return
     */
    public static function getPurcentStolen(Player $defender, $number_fallen){
      
      //get the purcent setted by the player first
      $distribution = $defender->getStronghold()->getColumns();
      
      $purcent = 0;
      foreach($number_fallen as $indexfallen){
        if(isset($distribution[$indexfallen])){
          $purcent += $distribution[$indexfallen];
        }
      }
      
      return $purcent / 100;
      
    }
    
}