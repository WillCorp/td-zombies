<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Event\Listener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use WillCorp\ZombieBundle\Entity\Player;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Game\Processor\Collector;

/**
 * Class PlayerListener
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class PlayerListener
{
    /**
     * Handle Doctrine "prePersist" event
     *      - Prepare a player account
     *
     * @param LifecycleEventArgs $event The event object
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $player = $event->getEntity();
        $entityManager = $event->getEntityManager();

        if ($player instanceof Player) {
            $stronghold = new StrongholdInstance();
            $stronghold->setSquare($entityManager->getRepository('WillCorpZombieBundle:Square')->findOneAvailable());
            $stronghold->setLevel($entityManager->getRepository('WillCorpZombieBundle:StrongholdLevel')->findOneBy(array('level' => 1)));

            $player->setStronghold($stronghold);
            //todo prepare player account
        }
    }
}