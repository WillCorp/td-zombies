<?php

namespace WillCorp\ZombieBundle\Game\Processor;

use Doctrine\ORM\EntityManager;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;

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
        $this->strongholdLevels = $em->getRepository('WillCorpZombieBundle:StrongholdLevel')->findAll();
    }

    /**
     * Process a stronghold upgrade
     *
     * @param StrongholdInstance $stronghold The stronghold to upgrade
     * @param integer            $increment  The upgrade gap
     */
    public function processStronghold(StrongholdInstance $stronghold, $increment = 1)
    {
        $newStrongholdLevel = $this->getStrongholdLevel($stronghold->getLevel()->getLevel() + $increment);

        if ($newStrongholdLevel) {
            $stronghold->setLevel($newStrongholdLevel);
        }
    }

    /**
     * Process a building upgrade
     *
     * @param BuildingInstance $building  The building to upgrade
     * @param integer          $increment The upgrade gap
     */
    public function processBuilding(BuildingInstance $building, $increment = 1)
    {
        $buildingLevel = $building->getLevel();
        $newBuildingLevel = $buildingLevel->getBuilding()->getLevel($buildingLevel->getLevel() + $increment);

        if ($newBuildingLevel) {
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
        foreach ($this->strongholdLevels as $strongholdLevel) {
            if ($levelNum == $strongholdLevel->getLevel()) {
                return $strongholdLevel;
            }
        }

        return false;
    }
}