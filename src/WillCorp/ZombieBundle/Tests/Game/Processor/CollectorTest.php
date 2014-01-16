<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Tests\Game\Processor;

use WillCorp\ZombieBundle\Game\Helper\Resources;
use WillCorp\ZombieBundle\Game\Processor\Collector;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Entity\BuildingInstance;

/**
 * Test the game "collector" processor class
 *          {@link WillCorp\ZombieBundle\Game\Processor\Collector}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class CollectorTest extends \PHPUnit_Framework_TestCase
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
                    Resources::ENERGY => $i * 15,
                    Resources::METAL  => $i * 20,
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
     * Test the method "collectStrongholdResources"
     *      - Assert stronghold resource increment
     */
    public function testCollectStrongholdResources()
    {
        $stronghold = $this->getStrongholdInstance();

        $this->getProcessor()->collectStrongholdResources($stronghold);

        //Test that resources has been correctly incremented
        $this->assertSame(
            array(Resources::ENERGY => 305, Resources::METAL  => 400),
            $stronghold->getResources()
        );
    }

    /**
     * Test the method "collectBuildingResources"
     *      - Assert stronghold resource increment
     */
    public function testCollectBuildingResources()
    {
        $stronghold = $this->getStrongholdInstance();

        $this->getProcessor()->collectBuildingResources($stronghold->getBuildings()->first());

        //Test that resources has been correctly incremented
        $this->assertSame(
            array(Resources::ENERGY => 95, Resources::METAL  => 120),
            $stronghold->getResources()
        );
    }

    /**
     * Return the collector processor object to test
     *
     * @return Collector
     */
    protected function getProcessor()
    {
        return new Collector();
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
                Resources::ENERGY => 80,
                Resources::METAL  => 100,
            ));

        for ($i=1 ; $i<=5 ; $i++) {
            $building = new BuildingInstance();
            $building
                ->setLevel($this->buildingLevels[$i]);

            $stronghold->addBuilding($building);
        }

        return $stronghold;
    }
}