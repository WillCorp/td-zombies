<?php
/**
 * This file is part of the CloudyFlowBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WillCorp\ZombieBundle\Entity\Unit;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 * @copyright 2013 © Cloudy-Web-Creations <http://www.cloudy-web-creations.com>
 */
class LoadUnitData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $units = array(
            'small' => array(
                'name' => 'Goule',
                'desc' => '',
                'img'  => '',
            ),
            'normal' => array(
                'name' => 'Zombie',
                'desc' => '',
                'img'  => '',
            ),
            'big' => array(
                'name' => 'Aberration',
                'desc' => '',
                'img'  => '',
            ),
        );
        foreach ($units as $unitName => $unitData) {
            $unit = new Unit();
            $unit
                ->setName($unitData['name'])
                ->setDescription($unitData['desc'])
                ->setImage($unitData['img']);

            $manager->persist($unit);

            $this->addReference('unit-' . $unitName, $unit);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}