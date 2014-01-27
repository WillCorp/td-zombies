<?php

namespace WillCorp\ZombieBundle\Tests\Game\Processor;

use WillCorp\ZombieBundle\Game\Helper\Fighter;
use WillCorp\ZombieBundle\Game\Processor\Fight;
use WillCorp\ZombieBundle\Entity\Player;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\UnitLevel;
use WillCorp\ZombieBundle\Game\Helper\Resources;

/**
 * Test the game processor class
 *          {@link WillCorp\ZombieBundle\Game\Processor\Fight}
 *
 * @author Sauvan William <sauvan.william@gmail.com>
 */
class FightTest extends \PHPUnit_Framework_TestCase
{
  
  /**
   * {@inheritdoc}
   */
  public function tearDown()
  {
    $this->attacker = null;
    $this->defender = null;
  }
  
  /**
   * Test the method "processFighting"
   *
   * @param Player $defender
   * @param Player $attackers
   * @param array $attack_matrix
   *
   * @dataProvider testProcessFightingDataProvider
   */
  public function testProcessFighting(Player $defender, Player $attackers, array $attack_matrix, $purcentRes)
  {

      $fightProcessor = new Fight();
      
      $purcent = $fightProcessor->processFighting($defender, $attackers,  $attack_matrix);

      $this->assertSame(
          $purcent,
          $purcentRes
      );
  }
  
  /**
   * Data provider for the "testProcessFighting" method
   *
   * @return array
   */
  public function testProcessFightingDataProvider()
  {
    
      $attacker = new Player();
      $att_stronghol = new StrongholdInstance();
      $attacker->setStronghold($att_stronghol);
      
      $defender = $this->getADefender();
      
      return array(
          
          //try a sucessful defense
          array(
              $defender,
              $attacker,
              $this->getAttackerMatrixSample(),
              0
          ),
          
          //try a 25% defense
          array(
              $defender,
              $attacker,
              $this->getAttackerMatrixSample(0, 1, false, 2, 5),
              0.25
          ),
          
          //try a 25% defense but unit isn't dealing enough damages
          array(
              $defender,
              $attacker,
              $this->getAttackerMatrixSample(0, 1, false, 2, 2),
              0
          ),
          
          //try a 25% defense, unit isn't dealing enough damages, many of unit to succeed
          array(
              $defender,
              $attacker,
              $this->getAttackerMatrixSample(0, 1, false, 2, 2, 2),
              0.25
          ),
          
          //try a 50% defense
          array(
              $defender,
              $attacker,
              $this->getAttackerMatrixSample(0, 1, false, 3, 5),
              0.50
          ),
          
          //try a 75% defense
          array(
              $defender,
              $attacker,
              $this->getAttackerMatrixSample(0, 1, false, 4, 5),
              0.75
          ),
          
          //try a 100% defense
          array(
              $defender,
              $attacker,
              $this->getAttackerMatrixSample(0, 1, false, 5, 5),
              1
          ),
      );
  }
  
  /**
   * Test the method "initMatrix"
   *
   * @param Player $defender
   * @param Player $attackers
   * @param array $attack_matrix
   *
   * @dataProvider testInitMatrixDataProvider
   */
  public function testInitMatrix(Player $defender, array $goodmatrixform)
  {

      $fightProcessor = new Fight();
      
      $fightProcessor->initDefenseMatrix($defender);
      $result_battlefield = $fightProcessor->getBattelfield();
      
      foreach($result_battlefield as $keyround => $rounds){
        
        $this->assertArrayHasKey($keyround, $goodmatrixform);
        foreach($rounds as $keycolumn => $value){
          
          $this->assertArrayHasKey($keycolumn, $goodmatrixform[$keyround]);
          
          //echo "checking[$keyround][$keycolumn];";
          if($goodmatrixform[$keyround][$keycolumn] == false){
            $this->assertFalse($value);
          }
          else{
            $this->assertInstanceOf("WillCorp\ZombieBundle\Entity\BuildingInstance", $value);
          }
        }
      }
    
  }
  
  /**
   * Data provider for the "testInitMatrix" method
   *
   * @return array
   */
  public function testInitMatrixDataProvider()
  {
    
      $defender = $this->getADefender();
      
      //a true value in the second parameter assume that a buildinginstance is setted here.
      return array(
          
          array(
              $defender,
              array(
                  array(false,false,false,false,false),
                  array(false,true,false,false,true),
                  array(false,false,true,true,false),
                  array(false,false,true,true,false),
                  array(false,false,false,true,false),
                  array(false,false,false,false,false),
              ),
          ),
      );
  }
    
  /**
   * Get a sample of attacker array
   * 
   * @param int $round the round where the unit are setted
   * @param int $speed the speed of the unit
   * 
   * @return array $attacker_matrix
   */
  private function getAttackerMatrixSample($round = 0, $speed = 1, $finished = false, $health = 1, $damages = 1, $nb_round = 1){
    $attacker_matrix = array();

    for($i=1; $i<=4; $i++){
      for($j=0; $j<$nb_round; $j++){
        $building = $this->getAnUnitLikeThat($speed, $health, $damages);
        $unit = Fighter::setUnitPosition($building, $round + $j, $i);
        
        $unit[Fighter::FINISHED] = $finished;
        $attacker_matrix[] = $unit;
      }
    }
    
    return $attacker_matrix;
  }
    
  /**
   * 
   * @param int $speed
   * @param int $health
   * @param int $damages
   * 
   * @return \WillCorp\ZombieBundle\Entity\BuildingInstance
   */
  private function getAnUnitLikeThat($speed, $health, $damages){


    $unit = new UnitLevel();
    $unit->setSpeed($speed)
         ->setHealth($health)
         ->setDamages($damages);
    
    $buildingLevel = new BuildingLevel();
    $buildingLevel->setUnit($unit);
    
    $buildinginstance = new BuildingInstance();
    $buildinginstance->setLevel($buildingLevel)
                     ->setUnitCount(1);
    
    return $buildinginstance;
  }
  
  private function getADefender(){
    
    //create some ressources
    $ressources = array();
    foreach(Resources::getResourcesTypes() as $ressourcetype){
      $ressources[$ressourcetype] = 1000;
    }
    
    $defender = new Player();
    
    $stronghold1 = new StrongholdLevel();
    $stronghold1->setLinesCount(6)
    ->setColumnsCount(4)
    ->setColumnLife(3);
    
    $strongholdintance1 = new StrongholdInstance();
    $strongholdintance1->setLevel($stronghold1)
    ->setResources($ressources)
    ->setColumns(array(
        1 => 25,
        2 => 25,
        3 => 25,
        4 => 25,
    ));
     
    for ($i=1 ; $i<=2 ; $i++) {
       
      $buildingLevel = new BuildingLevel();
      $buildingLevel->setRoundCount($i)
      ->setColumnsCount($i)
      ->setDefense(1);
       
      $building = new BuildingInstance();
      $building->setRoundStart($i)
      ->setColumnStart($i)
      ->setLevel($buildingLevel);
      
      $strongholdintance1->addBuilding($building);
    
    }
    
    //add a third building
    $buildingLevel = new BuildingLevel();
    $buildingLevel->setRoundCount(1)
    ->setColumnsCount(1)
    ->setDefense(1);
     
    $building = new BuildingInstance();
    $building->setRoundStart(4)
    ->setColumnStart(3)
    ->setLevel($buildingLevel);
    
    $strongholdintance1->addBuilding($building);
    
    //add a last one
    $buildingLevel = new BuildingLevel();
    $buildingLevel->setRoundCount(1)
    ->setColumnsCount(1)
    ->setDefense(4);
     
    $building = new BuildingInstance();
    $building->setRoundStart(1)
    ->setColumnStart(4)
    ->setLevel($buildingLevel);
    
    $strongholdintance1->addBuilding($building);
    
    $defender->setStronghold($strongholdintance1);
    
    return $defender;
    
  }
    
}