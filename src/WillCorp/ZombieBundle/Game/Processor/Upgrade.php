<?php

namespace WillCorp\ZombieBundle\Game\Processor;

use Doctrine\ORM\EntityManager;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;
use WillCorp\ZombieBundle\Game\Helper\Resources;

/**
 * Class Upgrade
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class Upgrade
{
    /**
     * Existing stronghold levels
     * @var StrongholdLevel[]
     */
    private $strongholdLevels;

    /**
     * Class constructor
     *
     * @param EntityManager $em Doctrine entity manager
     */
    public function __construct(EntityManager $em)
    {
        foreach ($em->getRepository('WillCorpZombieBundle:StrongholdLevel')->findAll() as $strongholdLevel) {
            $this->strongholdLevels[$strongholdLevel->getLevel()] = $strongholdLevel;
        }
    }

    /**
     * Process a stronghold upgrade
     *
     * @param StrongholdInstance $stronghold The stronghold to upgrade
     * @param integer            $increment The upgrade gap
     *
     * @throws \Exception If there is not enough resources in the stronghold
     */
    public function processStronghold(StrongholdInstance $stronghold, $increment = 1)
    {
        $newStrongholdLevel = $this->getStrongholdLevel($stronghold->getLevel()->getLevel() + $increment);

        if ($newStrongholdLevel) {
            if (!Resources::hasEnoughResources($stronghold->getResources(), $newStrongholdLevel->getCost())) {
                throw new \Exception('You have not enough resources !');
            }
            $stronghold->setLevel($newStrongholdLevel);
        }
    }

    /**
     * Process a building upgrade
     *
     * @param BuildingInstance $building  The building to upgrade
     * @param integer          $increment The upgrade gap
     *
     * @throws \Exception If there is not enough resources in the stronghold
     */
    public function processBuilding(BuildingInstance $building, $increment = 1)
    {
        $buildingLevel = $building->getLevel();
        $newBuildingLevel = $buildingLevel->getBuilding()->getLevel($buildingLevel->getLevel() + $increment);

        if ($newBuildingLevel) {
            if (!Resources::hasEnoughResources($building->getStronghold()->getResources(), $newBuildingLevel->getCost())) {
                throw new \Exception('You have not enough resources !');
            }
            $building->setLevel($newBuildingLevel);
        }
    }

    /**
     * Get a stronghold instance for a given $level number
     *
     * @param integer $levelNum The level number
     *
     * @return StrongholdLevel|false
     */
    protected function getStrongholdLevel($levelNum)
    {
        if (array_key_exists($levelNum, $this->strongholdLevels)) {
            return $this->strongholdLevels[$levelNum];
        }

        return false;
    }
}