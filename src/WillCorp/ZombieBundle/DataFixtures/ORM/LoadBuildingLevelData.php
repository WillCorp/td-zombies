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
                    'energy' => 10,
                    'metal'  => 10,
                ),
                'income' => array(
                    'energy' => 10,
                    'metal'  => 10,
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
                    'energy' => 10,
                    'metal'  => 10,
                ),
                'income' => array(
                    'energy' => 10,
                    'metal'  => 10,
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
                    'energy' => 1,
                    'metal'  => 1,
                ),
                'income' => array(
                    'energy' => 1,
                    'metal'  => 1,
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
                        'energy' => $i * $buildingData['cost']['energy'],
                        'metal'  => $i * $buildingData['cost']['metal'],
                    ))
                    ->setIncome(array(
                        'energy' => $i * $buildingData['income']['energy'],
                        'metal'  => $i * $buildingData['income']['metal'],
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