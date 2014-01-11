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
use WillCorp\ZombieBundle\Entity\UnitLevel;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 * @copyright 2013 © Cloudy-Web-Creations <http://www.cloudy-web-creations.com>
 */
class LoadUnitLevelData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $units = array(
            'small' => array(
                'speed'   => 5,
                'health'  => 3,
                'damages' => 1,
            ),
            'normal' => array(
                'speed'   => 3,
                'health'  => 3,
                'damages' => 3,
            ),
            'big' => array(
                'speed'   => 1,
                'health'  => 3,
                'damages' => 5,
            ),
        );
        foreach ($units as $unitName => $unitData) {
            for ($i=1 ; $i<=10 ; $i++) {
                $level = new UnitLevel();
                $level
                    ->setUnit($this->getReference('unit-' . $unitName))
                    ->setLevel($i)
                    ->setSpeed($i * $unitData['speed'])
                    ->setHealth($i * $unitData['health'])
                    ->setDamages($i * $unitData['damages']);

                $manager->persist($level);

                $this->addReference('unit-' . $unitName . '-level-' . $i, $level);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}