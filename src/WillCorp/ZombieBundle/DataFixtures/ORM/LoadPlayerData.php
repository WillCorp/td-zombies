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
use WillCorp\ZombieBundle\Entity\Player;

/**
 * @todo: provide class description
 *
 * @author    Yann Eugoné <yann.eugone@gmail.com>
 * @copyright 2013 © Cloudy-Web-Creations <http://www.cloudy-web-creations.com>
 */
class LoadPlayerData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $players = array(
            array(
                'email'    => 'william.sauvan@gmail.com',
                'username' => 'william.sauvan',
                'password' => 'william',
            ),
            array(
                'email'    => 'yann.eugone@gmail.com',
                'username' => 'yann.eugone',
                'password' => 'yann',
            ),
        );
        foreach ($players as $playerData) {
            $player = new Player();
            $player
                ->setEmail($playerData['email'])
                ->setUsername($playerData['username'])
                ->setPlainPassword($playerData['password'])
                ->setEnabled(true);

            $manager->persist($player);

            $this->addReference('player-' . $playerData['username'], $player);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7;
    }
}