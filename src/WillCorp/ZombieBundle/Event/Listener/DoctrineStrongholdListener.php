<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Event\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Game\Processor\Collect as CollectProcessor;
use WillCorp\ZombieBundle\Game\Processor\Collector;

/**
 * Class DoctrineStrongholdListener
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class DoctrineStrongholdListener
{
    /**
     * The collect processor object
     * @var CollectProcessor
     */
    protected $collector;

    /**
     * Class constructor
     *
     * @param CollectProcessor $collector The collect processor object
     */
    public function __construct(CollectProcessor $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Handle Doctrine "prePersist" event
     *      - Collect stronghold resources before save
     *
     * @param LifecycleEventArgs $event The event object
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $stronghold = $event->getEntity();

        if ($stronghold instanceof StrongholdInstance) {
            $this->collector->collectStrongholdResources($stronghold);
        }
    }

    /**
     * Handle Doctrine "preUpdate" event fo the StrongholdInstance object
     *      - Collect stronghold resources before save
     *
     * @param LifecycleEventArgs $event The event object
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $stronghold = $event->getEntity();

        if ($stronghold instanceof StrongholdInstance) {
            $this->collector->collectStrongholdResources($stronghold);
        }
    }
}