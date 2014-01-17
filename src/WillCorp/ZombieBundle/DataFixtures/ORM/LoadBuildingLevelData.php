<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Game\Helper\Resources as ResourcesHelper;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 */
class LoadBuildingLevelData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $units = array(
            'income' => array(
                'unit' => 'small',
                'cost' => array(
                    ResourcesHelper::ENERGY => 10,
                    ResourcesHelper::METAL  => 10,
                ),
                'income' => array(
                    ResourcesHelper::ENERGY => 10,
                    ResourcesHelper::METAL  => 10,
                ),
                'defense'    => 1,
                'round'      => 1,
                'columns'    => 1,
                'unit_count' => 1,
                'unit_cd'    => 1,
            ),
            'defense' => array(
                'unit' => 'normal',
                'cost' => array(
                    ResourcesHelper::ENERGY => 10,
                    ResourcesHelper::METAL  => 10,
                ),
                'income' => array(
                    ResourcesHelper::ENERGY => 10,
                    ResourcesHelper::METAL  => 10,
                ),
                'defense'    => 1,
                'round'      => 1,
                'columns'    => 1,
                'unit_count' => 1,
                'unit_cd'    => 1,
            ),
            'attack' => array(
                'unit' => 'big',
                'cost' => array(
                    ResourcesHelper::ENERGY => 1,
                    ResourcesHelper::METAL  => 1,
                ),
                'income' => array(
                    ResourcesHelper::ENERGY => 1,
                    ResourcesHelper::METAL  => 1,
                ),
                'defense'    => 1,
                'round'      => 1,
                'columns'    => 1,
                'unit_count' => 1,
                'unit_cd'    => 1,
            ),
        );
        foreach ($units as $buildingName => $buildingData) {
            for ($i=1 ; $i<=10 ; $i++) {
                $level = new BuildingLevel();
                $level
                    ->setBuilding($this->getReference('building-' . $buildingName))
                    ->setUnit($this->getReference('unit-' . $buildingData['unit'] . '-level-' . $i))
                    ->setLevel($i)
                    ->setCost(array(
                        ResourcesHelper::ENERGY => $i * $buildingData['cost'][ResourcesHelper::ENERGY],
                        ResourcesHelper::METAL  => $i * $buildingData['cost'][ResourcesHelper::METAL],
                    ))
                    ->setIncome(array(
                        ResourcesHelper::ENERGY => $i * $buildingData['income'][ResourcesHelper::ENERGY],
                        ResourcesHelper::METAL  => $i * $buildingData['income'][ResourcesHelper::METAL],
                    ))
                    ->setDefense($i * $buildingData['defense'])
                    ->setRoundCount($i * $buildingData['round'])
                    ->setColumnsCount($i * $buildingData['columns'])
                    ->setUnitCountLimit($i * $buildingData['unit_count'])
                    ->setUnitCooldown($i * $buildingData['unit_cd']);

                $manager->persist($level);

                $this->addReference('building-' . $buildingName . '-level-' . $i, $level);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }
}