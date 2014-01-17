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
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Game\Processor\Collector\Chain as ChainCollector;
use WillCorp\ZombieBundle\Game\Processor\Collector;

/**
 * Class StrongholdListener
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class StrongholdListener
{
    /**
     * The collect processor object
     * @var ChainCollector
     */
    protected $collector;

    /**
     * Class constructor
     *
     * @param ChainCollector $collector The collect processor object
     */
    public function __construct(ChainCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Handle Doctrine "prePersist" event
     *      - Process the collect features
     *
     * @param LifecycleEventArgs $event The event object
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $stronghold = $event->getEntity();

        if ($stronghold instanceof StrongholdInstance) {
            $this->collector->collectStronghold($stronghold);
        }
    }

    /**
     * Handle Doctrine "preUpdate" event fo the StrongholdInstance object
     *      - Process the collect features
     *
     * @param LifecycleEventArgs $event The event object
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $stronghold = $event->getEntity();

        if ($stronghold instanceof StrongholdInstance) {
            $this->collector->collectStronghold($stronghold);
        }
    }
}