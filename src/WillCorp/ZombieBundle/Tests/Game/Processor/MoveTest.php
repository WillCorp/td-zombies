<?php

namespace WillCorp\ZombieBundle\Tests\Game\Processor;

use WillCorp\ZombieBundle\Game\Helper\Moves;
use WillCorp\ZombieBundle\Game\Processor\Move;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;
use WillCorp\ZombieBundle\Entity\Building;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Entity\BuildingInstance;

/**
 * Test the game processor class
 *          {@link WillCorp\ZombieBundle\Game\Processor\Move}
 *
 * @author Sauvan William <sauvan.william@gmail.com>
 */
class MoveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Stronghold
     */
    protected $stronghold;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->stronghold = new StrongholdInstance();
        $strongholdLevel = new StrongholdLevel();
        $strongholdLevel->setColumnsCount(5)
                        ->setLinesCount(8);
        $this->stronghold->setLevel($strongholdLevel);

        for ($i=1 ; $i<=2 ; $i++) {

          $buildingLevel = new BuildingLevel();
          $buildingLevel->setRoundCount($i)
          ->setColumnsCount($i);
          
          $building = new BuildingInstance();
          $building->setStronghold($this->stronghold)
                   ->setRoundStart($i)
                   ->setColumnStart($i)
                   ->setLevel($buildingLevel);
          
        }
        
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->stronghold = null;
    }

    /**
     * Test the method "processBuilding" with set of coordinates
     *      - Assert building good spot
     *      
     * @param int $countRound
     * @param int $countColumn
     * @param int $startRound
     * @param int $startColumn
     * @param int $newRoundStart
     * @param int $newColumnStart
     *      
     * @dataProvider testMoveOverOtherDataProvider
     */
    public function testMoveOverOther($countRound, $countColumn, $startRound, $startColumn, $newRoundStart, $newColumnStart)
    {

        $building = $this->getBuildingInstance($countRound, $countColumn, $startRound, $startColumn);
      
        $this->getProcessor()->processBuilding($building, $newRoundStart, $newColumnStart);

        //Test if the building has been correctly moved round
        $this->assertSame(
            $newRoundStart,
            $building->getRoundStart()
        );
        //Test if the building has been correctly moved column
        $this->assertSame(
            $newColumnStart,
            $building->getColumnStart()
        );
        
    }

    /**
     * Data provider for the "testMoveOverOther" method
     *
     * @return array
     */
    public function testMoveOverOtherDataProvider()
    {
        return array(
            //another building
            array(
                1,
                1,
                4,
                1,
                5,
                1,
            ),
        );
    }


    /**
     * Test the method "processBuilding" method exception
     *
     * @param int $countRound
     * @param int $countColumn
     * @param int $startRound
     * @param int $startColumn
     * @param int $newRoundStart
     * @param int $newColumnStart
     *
     * @dataProvider testMoveOverOtherDataProviderExceptions
     */
    public function testMoveOverOtherExceptions($countRound, $countColumn, $startRound, $startColumn, $newRoundStart, $newColumnStart, $exceptionName, $exceptionMessage)
    {
        $this->setExpectedException($exceptionName, $exceptionMessage);
        
        $building = $this->getBuildingInstance($countRound, $countColumn, $startRound, $startColumn);
      
        $this->getProcessor()->processBuilding($building, $newRoundStart, $newColumnStart);
    }
    
    /**
     * Data provider for the "testCanMoveBuildingExceptions" method
     *
     * @return array
     */
    public function testMoveOverOtherDataProviderExceptions()
    {
        return array(
        //out of range
          array(
            1,
            1,
            2,
            2,
            5,
            -1,
            'Exception',
            'This is outside the map !'
          ),
        );
    }
    

    /**
     * Return the move processor object to test
     *
     * @return Move
     */
    protected function getProcessor()
    {
        $repository = $this->getMockBuilder('WillCorp\ZombieBundle\Repository\StrongholdInstanceRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        //commented for now, will be use later on better tests
        /*$repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($this->stronghold));*/

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        
        //commented for now, will be use later on better tests
        /*$entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($repository));*/

        return new Move($entityManager);
    }

    /**
     * Return the stronghold instance to use for tests
     *
     * @return BuildingInstance
     */
    protected function getBuildingInstance($countRound, $countColumn, $startRound, $startColumn)
    {
      
        $buildingLevel = new BuildingLevel();
        $buildingLevel->setRoundCount($countRound)
                      ->setColumnsCount($countColumn);

        $building = new BuildingInstance();
        $building->setStronghold($this->stronghold)
                 ->setRoundStart($startRound)
                 ->setColumnStart($startColumn)
                 ->setLevel($buildingLevel);
        
        return $building;
    }
    
}