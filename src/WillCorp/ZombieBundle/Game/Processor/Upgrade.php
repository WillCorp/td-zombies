<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Game\Processor;

use Doctrine\ORM\EntityManager;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
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
            $cost = $this->getTotalCost($stronghold->getLevel()->getLevel(), $this->strongholdLevels, $increment);
            if (!Resources::hasEnoughResources($stronghold->getResources(), $cost)) {
                throw new \Exception('You have not enough resources !');
            }
            $stronghold
                ->setLevel($newStrongholdLevel)
                ->setResources(Resources::subtractResources($stronghold->getResources(), $cost));
        } else {
            throw new \Exception('The required stronghold level is not available !');
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
            $strongholdResources = $building->getStronghold()->getResources();
            $cost = $this->getTotalCost($building->getLevel()->getLevel(), $buildingLevel->getBuilding()->getLevels(), $increment);
            if (!Resources::hasEnoughResources($strongholdResources, $cost)) {
                throw new \Exception('You have not enough resources !');
            }
            $building
                ->setLevel($newBuildingLevel)
                ->getStronghold()
                    ->setResources(Resources::subtractResources($strongholdResources, $cost));
        } else {
            throw new \Exception('The required building level is not available !');
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

    /**
     * Return the total cost for upgrading $increment from $currentLevel
     * Use costs stored in $objects
     *
     * @param integer                           $currentLevel
     * @param StrongholdLevel[]|BuildingLevel[] $objects
     * @param integer                           $increment
     *
     * @return array
     */
    protected function getTotalCost($currentLevel, $objects, $increment)
    {
        $objectsByLevel = array();
        foreach ($objects as $object) {
            $objectsByLevel[$object->getLevel()] = $object;
        }
        /* @var $objectsByLevel StrongholdLevel[]|BuildingLevel[] */

        $totalCost = array();
        for ($i=$currentLevel+1 ; $i<=$currentLevel+$increment ; $i++) {
            $cost = $objectsByLevel[$i]->getCost();
            foreach ($cost as $resource => $value) {
                if (!array_key_exists($resource, $totalCost)) {
                    $totalCost[$resource] = 0;
                }

                $totalCost[$resource] += $value;
            }
        }

        return $totalCost;
    }
}