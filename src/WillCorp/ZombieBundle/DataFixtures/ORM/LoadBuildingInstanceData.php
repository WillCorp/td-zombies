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
use WillCorp\ZombieBundle\Entity\BuildingInstance;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 */
class LoadBuildingInstanceData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $strongholds = array(
            'william.sauvan' => array(
                'income' => array(
                    'round' => 1,
                    'units' => 1,
                ),
                'defense' => array(
                    'round' => 2,
                    'units' => 1,
                ),
                'attack' => array(
                    'round' => 3,
                    'units' => 1,
                ),
            ),
            'yann.eugone' => array(
                'income' => array(
                    'round' => 1,
                    'units' => 1,
                ),
                'defense' => array(
                    'round' => 2,
                    'units' => 1,
                ),
                'attack' => array(
                    'round' => 3,
                    'units' => 1,
                ),
            ),
        );
        foreach ($strongholds as $playerName => $buildings) {
            $stronghold = $this->getReference('stronghold-' . $playerName);
            foreach ($buildings as $buildingName => $buildingData) {
                $building = new BuildingInstance();
                $building
                    ->setStronghold($stronghold)
                    ->setLevel($this->getReference('building-' . $buildingName . '-level-1'))
                    ->setRoundStart($buildingData['round'])
                    ->setUnitCount($buildingData['units'])
                    ->setResources(array(
                        'energy' => 300,
                        'metal'  => 300,
                    ))
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime());

                $manager->persist($building);

                $this->addReference('stronghold-' . $playerName . '-building-' . $buildingName, $building);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 9;
    }
}