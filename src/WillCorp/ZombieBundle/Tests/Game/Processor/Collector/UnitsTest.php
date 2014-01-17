<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Tests\Game\Processor\Collector;

use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Game\Processor\Collector\Units as UnitsCollector;

/**
 * Test the units collector class
 *          {@link WillCorp\ZombieBundle\Game\Processor\Collector\Units}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class UnitsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BuildingLevel[]
     */
    protected $buildingLevels;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        for ($i=1 ; $i<=5 ; $i++) {
            $buildingLevel = new BuildingLevel();
            $buildingLevel
                ->setLevel($i)
                ->setUnitCooldown($i * 60)
                ->setUnitCountLimit(2 + $i);
            $this->buildingLevels[$i] = $buildingLevel;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->buildingLevels = null;
    }

    /**
     * Test the method "collectStronghold"
     * Standard process
     *      - Assert stronghold's building's unit count were changed
     */
    public function testCollectStronghold()
    {
        $stronghold = $this->getStrongholdInstance();

        $counts = array();
        $i = 1;
        foreach ($stronghold->getBuildings() as $building) {
            /* @var $building BuildingInstance */
            $counts[$i] = $building->getUnitCount();
            $i++;
        }

        $this->getProcessor()->collectStronghold($stronghold);

        $i = 1;
        foreach ($stronghold->getBuildings() as $building) {
            /* @var $building BuildingInstance */
            //Test that unit count has been changed
            $this->assertNotSame(
                $counts[$i],
                $building->getUnitCount()
            );
            $i++;
        }
    }

    /**
     * Test the method "collectBuilding"
     * Standard process
     *      - Assert building available units increment
     *      - Assert Assert building update time changed
     */
    public function testCollectBuilding()
    {
        $stronghold = $this->getStrongholdInstance();

        $building = $stronghold->getBuildings()->first();
        /* @var $building BuildingInstance */
        $buildingUpdatedAt = $building->getUpdatedAt();

        $this->getProcessor()->collectBuilding($stronghold->getBuildings()->first());

        //Test that unit count has been correctly incremented
        $this->assertSame(
            3,
            $building->getUnitCount()
        );
        //Test that building "updatedAt" properties has been modified
        $this->assertGreaterThan($buildingUpdatedAt->getTimestamp(), $building->getUpdatedAt()->getTimestamp());
    }

    /**
     * Test the method "collectBuilding"
     * Process premature call (no changes)
     *      - Assert building available units no changes
     *      - Assert building update time no changes
     */
    public function testCollectBuildingPremature()
    {
        $stronghold = $this->getStrongholdInstance();

        $building = $stronghold->getBuildings()->first();
        /* @var $building BuildingInstance */
        $building->setUpdatedAt(new \DateTime()); //Simulate the last collect date
        $buildingUpdatedAt = $building->getUpdatedAt();

        $this->getProcessor()->collectBuilding($building);

        //Test that unit count have not been modified
        $this->assertSame(
            null, //todo
            $building->getUnitCount()
        );
        //Test that building "updatedAt" properties have not been modified
        $this->assertSame($buildingUpdatedAt->getTimestamp(), $building->getUpdatedAt()->getTimestamp());
    }

    /**
     * Test the method "collectBuilding"
     * Process call when unit limit is already reached (no changes)
     *      - Assert building available units no changes
     *      - Assert building update time no changes
     */
    public function testCollectBuildingLimitReached()
    {
        $stronghold = $this->getStrongholdInstance();

        $building = $stronghold->getBuildings()->first();
        /* @var $building BuildingInstance */
        $building->setUnitCount($building->getLevel()->getUnitCountLimit()); //Simulate the unit count limit
        $buildingUpdatedAt = $building->getUpdatedAt();

        $this->getProcessor()->collectBuilding($building);

        //Test that unit count have not been modified
        $this->assertSame(
            $building->getLevel()->getUnitCountLimit(),
            $building->getUnitCount()
        );
        //Test that building "updatedAt" properties have not been modified
        $this->assertSame($buildingUpdatedAt->getTimestamp(), $building->getUpdatedAt()->getTimestamp());
    }

    /**
     * Return the collector processor object to test
     *
     * @return UnitsCollector
     */
    protected function getProcessor()
    {
        return new UnitsCollector();
    }

    /**
     * Return the stronghold instance to use for tests
     *
     * @return StrongholdInstance
     */
    protected function getStrongholdInstance()
    {
        $stronghold = new StrongholdInstance();

        for ($i=1 ; $i<=5 ; $i++) {
            $updatedAt = new \DateTime();
            $updatedAt->modify('-1 days');

            $building = new BuildingInstance();
            $building
                ->setUpdatedAt($updatedAt)
                ->setLevel($this->buildingLevels[$i]);

            $stronghold->addBuilding($building);
        }

        return $stronghold;
    }
}