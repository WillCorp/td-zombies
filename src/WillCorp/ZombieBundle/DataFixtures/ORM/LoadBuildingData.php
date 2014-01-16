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
use WillCorp\ZombieBundle\Entity\Building;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 */
class LoadBuildingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $buildings = array(
            'income' => array(
                'name' => 'Resources',
                'desc' => '',
                'img'  => '',
            ),
            'defense' => array(
                'name' => 'Défense',
                'desc' => '',
                'img'  => '',
            ),
            'attack' => array(
                'name' => 'Attaque',
                'desc' => '',
                'img'  => '',
            ),
        );
        foreach ($buildings as $buildingName => $buildingData) {
            $building = new Building();
            $building
                ->setName($buildingData['name'])
                ->setDescription($buildingData['desc'])
                ->setImage($buildingData['img']);

            $manager->persist($building);

            $this->addReference('building-' . $buildingName, $building);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
}