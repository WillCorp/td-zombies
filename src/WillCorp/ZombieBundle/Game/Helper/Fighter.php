<?php

namespace WillCorp\ZombieBundle\Game\Helper;

use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\UnitLevel;
use WillCorp\ZombieBundle\Entity\Player;
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
    const HEALTH = "health";

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
      foreach($attack_matrix as $unitid => $unitInfos){
        if(self::isUnitCanMove($unitInfos)){
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
     * 
     * @return array a new attack matrix with correct information
     */
    public static function moveAttachers($attack_matrix, $finalRound){
      
      $new_matrix = array();
      foreach($attack_matrix as $unitInfos){
        
        if(!self::isUnitCanMove($unitInfos)){
          $new_matrix[] = $unitInfos;
          continue;
        }
        
        $unitInfos[self::ROUND] += $unitInfos[self::UNIT]->getSpeed();

        if($unitInfos[self::ROUND] >= $finalRound){
          $unitInfos[self::FINISHED] = true;
        }

        $new_matrix[] = $unitInfos;
      }
      
      return $new_matrix;
    }
    
    /**
     * Check if a given unit can move (i.e. is not to the end and have some hp)
     * 
     * @param unknown $unitInfos
     * @return boolean
     */
    public static function isUnitCanMove($unitInfos){
      return ( ( $unitInfos[self::FINISHED] !== true ) && ( $unitInfos[self::HEALTH] > 0 ) ); 
    }
    
    /**
     * Check if a given unit is in the battlefield
     * 
     * @param array $unitInfos
     * @param array $battlefield
     * @return boolean
     */
    public static function isUnitInBattlefield($unitInfos, $battlefield){
      return ( isset($battlefield[$unitInfos[self::ROUND]]) && isset($battlefield[$unitInfos[self::ROUND]][$unitInfos[self::COLUMN]]) ); 
    }
    
    /**
     * Deal damage to unit on a building spot. Also decrement the corresponding building's unit count
     * 
     * @param array $attack_matrix
     * @param array $battlefield
     * 
     * @return array a new attack matrix with correct health
     * 
     */
    public static function processDamages($attack_matrix, $battlefield){

      $new_matrix = array();
      
      foreach($attack_matrix as $unitInfos){
        
        if(!self::isUnitCanMove($unitInfos)){
          $new_matrix[] = $unitInfos;
          continue;
        }
        
        if( self::isUnitInBattlefield($unitInfos, $battlefield) &&
            ($buildinginstance = $battlefield[$unitInfos[self::ROUND]][$unitInfos[self::COLUMN]]) !== false){
          $unitInfos[self::HEALTH] -= $buildinginstance->getLevel()->getDefense();//deal damages to the unit
        }
        
        if($unitInfos[self::HEALTH] <= 0){
          self::unitCountDecrement($unitInfos[self::BUILDING]);
        }
      
        $new_matrix[] = $unitInfos;
      }
      
      return $new_matrix;
      
    }
    
    /**
     * Decrement te unit count of the given building
     * 
     * @param BuildingInstance $building
     * 
     * @throws \Exception If we try to decrement a unit count under 0
     */
    public static function unitCountDecrement(BuildingInstance $building){
      if( ($nbcurrent = $building->getUnitCount() ) <=0){
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
    public static function setUnitPosition(BuildingInstance $building, $round, $column){
      
      $arrayunit = array();
      
      $arrayunit[self::ROUND] = $round;
      $arrayunit[self::COLUMN] = $column;
      $arrayunit[self::FINISHED] = false;
      $arrayunit[self::UNIT] = $building->getLevel()->getUnit();
      $arrayunit[self::BUILDING] = $building;
      $arrayunit[self::HEALTH] = $building->getLevel()->getUnit()->getHealth();
      
      return $arrayunit;
      
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
      
      //var_dump("coucou" ,$attack_matrix);
      foreach($attack_matrix as $unitInfos){
        if($unitInfos[self::FINISHED]){
          //if this column has never been initialised, set it to 0
          if( !isset($array_countbycolumn[$unitInfos[self::COLUMN]]) ){
            $array_countbycolumn[$unitInfos[self::COLUMN]] = 0;
          }
          
          $array_countbycolumn[$unitInfos[self::COLUMN]] += $unitInfos[self::UNIT]->getDamages();
        }
      }
      
      $columnCount = $defender->getStronghold()->getLevel()->getColumnsCount();
      $columnlife = $defender->getStronghold()->getLevel()->getColumnLife();
      
      $number_fallen = array();//create a list of all fallend colum
      for($i=1; $i <= $columnCount; $i++){
        if(isset($array_countbycolumn[$i]) &&
          ($array_countbycolumn[$i] > $columnlife) ){
          $number_fallen[] = $i;
        }
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