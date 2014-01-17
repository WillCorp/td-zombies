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
use WillCorp\ZombieBundle\Game\Processor\Collector\Resources as ResourcesCollector;
use WillCorp\ZombieBundle\Game\Helper\Resources as ResourcesHelper;

/**
 * Test the resources collector class
 *          {@link WillCorp\ZombieBundle\Game\Processor\Collector\Resources}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class ResourcesTest extends \PHPUnit_Framework_TestCase
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
                ->setIncome(array(
                    ResourcesHelper::ENERGY => $i * 15,
                    ResourcesHelper::METAL  => $i * 20,
                ));
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
     *      - Assert stronghold resource increment
     */
    public function testCollectStronghold()
    {
        $stronghold = $this->getStrongholdInstance();

        $this->getProcessor()->collectStronghold($stronghold);

        //Test that resources has been correctly incremented
        $this->assertSame(
            array(
                ResourcesHelper::ENERGY => 905, // 80  + (15 * 1) + (30 * 2) + (45 * 3) + (60 * 4) + (75 * 5)
                ResourcesHelper::METAL  => 1200 // 100 + (20 * 1) + (40 * 2) + (60 * 3) + (80 * 4) + (100 * 5)
            ),
            $stronghold->getResources()
        );
    }

    /**
     * Test the method "collectBuilding"
     * Standard process
     *      - Assert stronghold resource increment
     *      - Assert building update time changed
     */
    public function testCollectBuilding()
    {
        $stronghold = $this->getStrongholdInstance();

        $building = $stronghold->getBuildings()->first();
        /* @var $building BuildingInstance */
        $buildingUpdatedAt = $building->getUpdatedAt();

        $this->getProcessor()->collectBuilding($stronghold->getBuildings()->first());

        //Test that resources has been correctly incremented
        $this->assertSame(
            array(ResourcesHelper::ENERGY => 95, ResourcesHelper::METAL  => 120),
            $stronghold->getResources()
        );
        //Test that building "updatedAt" properties has been modified
        $this->assertGreaterThan($buildingUpdatedAt->getTimestamp(), $building->getUpdatedAt()->getTimestamp());
    }

    /**
     * Test the method "collectBuilding"
     * Process premature call (no changes)
     *      - Assert stronghold resource not changed
     *      - Assert building update time not changed
     */
    public function testCollectBuildingPremature()
    {
        $stronghold = $this->getStrongholdInstance();

        $building = $stronghold->getBuildings()->first();
        /* @var $building BuildingInstance */
        $building->setUpdatedAt(new \DateTime()); //Simulate the last collect date
        $buildingUpdatedAt = $building->getUpdatedAt();

        $this->getProcessor()->collectBuilding($building);

        //Test that resources have not been modified
        $this->assertSame(
            array(ResourcesHelper::ENERGY => 80, ResourcesHelper::METAL  => 100),
            $stronghold->getResources()
        );
        //Test that building "updatedAt" properties have not been modified
        $this->assertSame($buildingUpdatedAt->getTimestamp(), $building->getUpdatedAt()->getTimestamp());
    }

    /**
     * Return the collector processor object to test
     *
     * @return ResourcesCollector
     */
    protected function getProcessor()
    {
        return new ResourcesCollector();
    }

    /**
     * Return the stronghold instance to use for tests
     *
     * @return StrongholdInstance
     */
    protected function getStrongholdInstance()
    {
        $stronghold = new StrongholdInstance();
        $stronghold
            ->setResources(array(
                ResourcesHelper::ENERGY => 80,
                ResourcesHelper::METAL  => 100,
            ));

        for ($i=1 ; $i<=5 ; $i++) {
            $updatedAt = new \DateTime();
            $updatedAt->modify(sprintf('-%d minutes', $i * 5));

            $building = new BuildingInstance();
            $building
                ->setUpdatedAt($updatedAt)
                ->setLevel($this->buildingLevels[$i]);

            $stronghold->addBuilding($building);
        }

        return $stronghold;
    }
}