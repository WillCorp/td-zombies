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
use WillCorp\ZombieBundle\Entity\StrongholdInstance;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 * @copyright 2013 © Cloudy-Web-Creations <http://www.cloudy-web-creations.com>
 */
class LoadStrongholdInstanceData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $strongholds = array(
            'william.sauvan' => array(
                'coord_x' => 3,
                'coord_y' => 3,
            ),
            'yann.eugone' => array(
                'coord_x' => 3,
                'coord_y' => 2,
            ),
        );
        foreach ($strongholds as $playerName => $strongholdData) {
            $stronghold = new StrongholdInstance();
            $stronghold
                ->setPlayer($this->getReference('player-' . $playerName))
                ->setSquare($this->getReference(sprintf('square-%d-%d', $strongholdData['coord_x'], $strongholdData['coord_y'])))
                ->setLevel($this->getReference('stronghold-level-1'))
                ->setColumns(array(
                    1 => 100
                ));

            $manager->persist($stronghold);

            $this->addReference('stronghold-' . $playerName, $stronghold);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 8;
    }
}