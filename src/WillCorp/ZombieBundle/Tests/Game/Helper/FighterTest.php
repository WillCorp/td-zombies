<?php

namespace WillCorp\ZombieBundle\Tests\Game\Helper;

use WillCorp\ZombieBundle\Game\Helper\Fighter;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\UnitLevel;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\Player;

/**
 * Test the game helper class
 *          {@link WillCorp\ZombieBundle\Game\Helper\Fighter}
 *
 * @author Sauvan William <sauvan.william@gmail.com>
 */
class FighterTest extends \PHPUnit_Framework_TestCase
{
  
    /**
     * Test the "isUnitCanMove" method results
     *
     * @param array $unitInfos
     * @param boolean $finished
     *
     * @dataProvider testIsUnitCanMoveDataProvider
     */
    public function testIsUnitCanMove($unitInfos, $can)
    {
      
        $result = Fighter::isUnitCanMove($unitInfos);
        
        $this->assertSame($result, $can);
    }
    
    /**
     * Data provider for the "testIsUnitCanMove" method
     *
     * @return array
     */
    public function testIsUnitCanMoveDataProvider()
    {
      
        return array(
            //try with a unit in the middle of nowhere
            array(
                array(
                    Fighter::FINISHED => false,
                    Fighter::HEALTH => 1
                ),
                true
            ),
            //try with a unit to the end
            array(
                array(
                    Fighter::FINISHED => true,
                    Fighter::HEALTH => 1
                ),
                false
            ),
            //try with a dead unit (sad :'( )
            array(
                array(
                    Fighter::FINISHED => false,
                    Fighter::HEALTH => 0
                ),
                false
            ),
        );
    }
  
    /**
     * Test the "moveAttachers" method results
     *
     * @param array $attack_matrix
     * @param int $finalRound
     * @param boolean $finished
     *
     * @dataProvider testMoveAttachersDataProvider
     */
    public function testMoveAttachers($attack_matrix, $finalRound, $goodarray)
    {
      
        $result_matrix = Fighter::moveAttachers($attack_matrix, $finalRound);

        $this->assertSameSize($result_matrix, $goodarray);
        foreach ($result_matrix as $key => $unit) {
          
          $this->assertArrayHasKey($key, $goodarray);
          $this->assertSame($unit[Fighter::ROUND], $goodarray[$key][Fighter::ROUND]);
          $this->assertSame($unit[Fighter::FINISHED], $goodarray[$key][Fighter::FINISHED]);
        }
    }
    
    /**
     * Data provider for the "testMoveAttachers" method
     *
     * @return array
     */
    public function testMoveAttachersDataProvider()
    {
      
        return array(
            //try with a unit with 1 speed
            array(
                $this->getAttackerMatrixSample(),
                5,
                $this->getAttackerMatrixSample(1)
            ),
            //try with a unit with 2 speed
            array(
                $this->getAttackerMatrixSample(0, 2),
                5,
                $this->getAttackerMatrixSample(2, 2)
            ),
            //try with a unit that finish
            array(
                $this->getAttackerMatrixSample(2, 4),
                5,
                $this->getAttackerMatrixSample(6, 4, true)
            ),
        );
    }
  
    /**
     * Test the "getPurcentStolen" method results
     *
     * @param Player $defender
     * @param unknown $number_fallen
     * @param unknown $goodpurcent
     *
     * @dataProvider testGetPurcentStolenDataProvider
     */
    public function testGetPurcentStolen(Player $defender, $number_fallen, $goodpurcent)
    {
      
        $purcent = Fighter::getPurcentStolen($defender, $number_fallen);

        $this->assertSame($purcent, $goodpurcent);
    }
    
    /**
     * Data provider for the "testGetPurcentStolen" method
     *
     * @return array
     */
    public function testGetPurcentStolenDataProvider()
    {
      
        return array(
            //try with a 100% stronghold
            array(
                $this->getPlayerStrongholdDistrib(array(
                    1 => 100,
                )),
                array(1),
                1
            ),
            //try with many columns
            array(
                $this->getPlayerStrongholdDistrib(array(
                    1 => 50,
                    2 => 50,
                )),
                array(1,2),
                1
            ),
            //try with many columns half fallen
            array(
                $this->getPlayerStrongholdDistrib(array(
                    1 => 50,
                    2 => 50,
                )),
                array(2),
                0.5
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
    private function getAttackerMatrixSample($round = 0, $speed = 1, $finished = false){
      $attacker_matrix = array();
      
      for($i=1; $i<3; $i++){
        $building = $this->getAnUnitLikeThat($speed, 2, 2);
        $unit = Fighter::setUnitPosition($building, $round, $i);
        $unit[Fighter::FINISHED] = $finished;
        $attacker_matrix[] = $unit;
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
      $buildinginstance->setLevel($buildingLevel);
      
      return $buildinginstance;
    }
    
    /**
     * Get a simple player with a stronghold with defined column distribution
     * 
     * @param array $distrib a given column distribution
     * 
     * @return \WillCorp\ZombieBundle\Entity\StrongholdInstance
     */
    private function getPlayerStrongholdDistrib($distrib){
      
      $stronghold = new StrongholdInstance();
      $stronghold->setColumns($distrib);
      
      $player = new Player();
      $player->setStronghold($stronghold);
      
      return $player;
      
    }

}