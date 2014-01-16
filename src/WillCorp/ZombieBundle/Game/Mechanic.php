<?php

namespace WillCorp\ZombieBundle\Game;

use Doctrine\ORM\EntityManager;
use WillCorp\ZombieBundle\Game\Processor as Processor;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;

/**
 * Class Mechanic
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Mechanic
{
    /**
     * Doctrine entity manager
     * @var EntityManager
     */
    protected $em;

    /**
     * Entities upgrades process
     * @var Processor\Upgrade
     */
    protected $upgradeProcessor;

    /**
     * Entities moves process
     * @var Processor\Move
     */
    protected $movesProcessor;


    /**
     * Class constructor
     *
     * @param EntityManager     $em               Doctrine entity manager
     * @param Processor\Upgrade $upgradeProcessor The upgrade processor object
     */
    public function __construct(EntityManager $em, Processor\Upgrade $upgradeProcessor, Processor\Move $movesProcessor)
    {
        $this->em = $em;
        $this->upgradeProcessor = $upgradeProcessor;
        $this->movesProcessor = $movesProcessor;
    }

    /**
     * Upgrade a stronghold instance
     *
     * @param StrongholdInstance $stronghold The stronghold to upgrade
     * @param integer            $increment  The upgrade gap
     */
    public function upgradeStronghold(StrongholdInstance $stronghold, $increment = 1)
    {
        $this->upgradeProcessor->processStronghold($stronghold, $increment);
        $this->em->persist($stronghold);
        $this->em->flush();
    }

    /**
     * Upgrade a building instance
     *
     * @param BuildingInstance $building  The building to upgrade
     * @param integer          $increment The upgrade gap
     */
    public function upgradeBuilding(BuildingInstance $building, $increment = 1)
    {
        $this->upgradeProcessor->processBuilding($building, $increment);
        $this->em->persist($building);
        $this->em->flush();
    }

    /**
     * Move a building instance
     *
     * @param BuildingInstance $building  The building to move
     * @param int $newRoundStart
     * @param int $newColumnStart
     */
    public function moveBuilding(BuildingInstance $building, $newRoundStart, $newColumnStart)
    {
        $this->movesProcessor->processBuilding($building, $newRoundStart, $newColumnStart);
        $this->em->persist($building);
        $this->em->flush();
    }
}