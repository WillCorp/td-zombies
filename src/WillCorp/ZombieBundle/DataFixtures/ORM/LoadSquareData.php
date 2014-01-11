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
use WillCorp\ZombieBundle\Entity\Square;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 * @copyright 2013 © Cloudy-Web-Creations <http://www.cloudy-web-creations.com>
 */
class LoadSquareData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($x=1 ; $x<=5 ; $x++) {
            for ($y=1 ; $y<=5 ; $y++) {
                $square = new Square();
                $square
                    ->setCoordinateX($x)
                    ->setCoordinateY($y);

                $manager->persist($square);

                $this->addReference('square-' . $x . '-' . $y, $square);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6;
    }
}