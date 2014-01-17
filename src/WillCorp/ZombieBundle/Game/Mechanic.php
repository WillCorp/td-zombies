<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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
     * Entities collect process
     * @var Processor\Collector
     */
    protected $collectProcessor;


    /**
     * Class constructor
     *
     * @param EntityManager       $em               Doctrine entity manager
     * @param Processor\Upgrade   $upgradeProcessor The upgrade processor object
     * @param Processor\Collector $collectProcessor The collect processor object
     */
    public function __construct(EntityManager $em, Processor\Upgrade $upgradeProcessor, Processor\Collector $collectProcessor)
    {
        $this->em = $em;

        $this->upgradeProcessor = $upgradeProcessor;
        $this->collectProcessor = $collectProcessor;
    }

    /**
     * Upgrade a stronghold instance
     *
     * @param StrongholdInstance $stronghold The stronghold to upgrade
     * @param integer            $increment  The upgrade gap
     */
    public function upgradeStronghold(StrongholdInstance $stronghold, $increment = 1)
    {
        $this->upgradeProcessor->upgradeStronghold($stronghold, $increment);
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
        $this->upgradeProcessor->upgradeBuilding($building, $increment);
        $this->em->persist($building);
        $this->em->flush();
    }

    /**
     * Process collect of a stronghold instance
     *
     * @param StrongholdInstance $stronghold The stronghold to collect from
     */
    public function collectStronghold(StrongholdInstance $stronghold)
    {
        $this->collectProcessor->collectStronghold($stronghold);
        $this->em->persist($stronghold);
        $this->em->flush();
    }

    /**
     * Process collect of a building instance
     *
     * @param BuildingInstance $building  The building to collect from
     */
    public function collectBuilding(BuildingInstance $building)
    {
        $this->collectProcessor->collectBuilding($building);
        $this->em->persist($building);
        $this->em->flush();
    }
}