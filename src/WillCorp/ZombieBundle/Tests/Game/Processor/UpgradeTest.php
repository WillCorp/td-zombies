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
use WillCorp\ZombieBundle\Game\Processor\Upgrade;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;
use WillCorp\ZombieBundle\Entity\Building;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Entity\BuildingInstance;

/**
 * Test the game processor class
 *          {@link WillCorp\ZombieBundle\Game\Processor\Upgrade}
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class UpgradeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StrongholdLevel[]
     */
    protected $stronholdLevels;

    /**
     * @var BuildingLevel[]
     */
    protected $buildingLevels;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $building = new Building();
        for ($i=1 ; $i<=10 ; $i++) {
            $strongholdLevel = new StrongholdLevel();
            $strongholdLevel
                ->setLevel($i)
                ->setCost(array(
                    Resources::ENERGY => $i * 75,
                    Resources::METAL  => $i * 100,
                ));
            $this->stronholdLevels[$i] = $strongholdLevel;

            $buildingLevel = new BuildingLevel();
            $buildingLevel
                ->setBuilding($building)
                ->setLevel($i)
                ->setCost(array(
                    Resources::ENERGY => $i * 15,
                    Resources::METAL  => $i * 20,
                ));
            $this->buildingLevels[$i] = $buildingLevel;
            $building->addLevel($buildingLevel);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->stronholdLevels = null;
        $this->buildingLevels = null;
    }

    /**
     * Test the method "processStronghold" with an upgrade of a single level
     *      - Assert stronghold level increment
     *      - Assert stronghold resource decrement
     */
    public function testStrongholdUpgradeOneLevel()
    {
        $stronghold = $this->getStrongholdInstance();

        $this->getProcessor()->processStronghold($stronghold);

        //Test that level has been incremented by 1
        $this->assertSame(
            2,
            $stronghold->getLevel()->getLevel()
        );
        //Test that resources has been correctly decremented
        $this->assertSame(
            array(Resources::ENERGY => 350, Resources::METAL  => 600),
            $stronghold->getResources()
        );
    }

    /**
     * Test the method "processStronghold" with an upgrade of several levels
     *      - Assert stronghold level increment
     *      - Assert stronghold resource decrement
     */
    public function testStrongholdUpgradeMultipleLevel()
    {
        $stronghold = $this->getStrongholdInstance();

        $this->getProcessor()->processStronghold($stronghold, 2);

        //Test that level has been incremented by 2
        $this->assertSame(
            3,
            $stronghold->getLevel()->getLevel()
        );
        //Test that resources has been correctly decremented
        $this->assertSame(
            array(Resources::ENERGY => 125, Resources::METAL  => 300),
            $stronghold->getResources()
        );
    }

    /**
     * Test the method "processBuilding" with an upgrade of a single level
     *      - Assert building level increment
     *      - Assert stronghold resource decrement
     */
    public function testBuildingUpgradeOneLevel()
    {
        $stronghold = $this->getStrongholdInstance();
        $building = $stronghold->getBuildings()->first();
        /* @var $building BuildingInstance */

        $this->getProcessor()->processBuilding($building);

        //Test that level has been incremented by 1
        $this->assertSame(
            2,
            $building->getLevel()->getLevel()
        );
        //Test that resources has been correctly decremented
        $this->assertSame(
            array(Resources::ENERGY => 470, Resources::METAL  => 760),
            $stronghold->getResources()
        );
    }

    /**
     * Test the method "processBuilding" with an upgrade of several levels
     *      - Assert building level increment
     *      - Assert stronghold resource decrement
     */
    public function testBuildingUpgradeMultipleLevel()
    {
        $stronghold = $this->getStrongholdInstance();
        $building = $stronghold->getBuildings()->first();
        /* @var $building BuildingInstance */

        $this->getProcessor()->processBuilding($building, 2);

        //Test that level has been incremented by 2
        $this->assertSame(
            3,
            $building->getLevel()->getLevel()
        );
        //Test that resources has been correctly decremented
        $this->assertSame(
            array(Resources::ENERGY => 425, Resources::METAL  => 700),
            $stronghold->getResources()
        );
    }

    /**
     * Return the upgrade processor object to test
     *
     * @return Upgrade
     */
    protected function getProcessor()
    {
        $repository = $this->getMockBuilder('WillCorp\ZombieBundle\Repository\StrongholdLevelRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($this->stronholdLevels));

        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($repository));

        return new Upgrade($entityManager);
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
                Resources::ENERGY => 500,
                Resources::METAL  => 800,
            ))
            ->setLevel($this->stronholdLevels[1]);

        $building = new BuildingInstance();
        $building
            ->setLevel($this->buildingLevels[1]);

        $stronghold->addBuilding($building);

        return $stronghold;
    }
}