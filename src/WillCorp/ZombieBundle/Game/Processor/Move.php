<?php

namespace WillCorp\ZombieBundle\Game\Processor;

use Doctrine\ORM\EntityManager;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\BuildingLevel;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;
use WillCorp\ZombieBundle\Entity\StrongholdLevel;
use WillCorp\ZombieBundle\Game\Helper\Moves;

/**
 * Class Upgrade
 *
 * @author Sauvan William <william.sauvan@gmail.com>
 */
class Move
{

    /**
     * Class constructor
     *
     * @param EntityManager $em Doctrine entity manager
     */
    public function __construct(EntityManager $em)
    {
      
    }

    /**
     * Process a building move
     *
     * @param BuildingInstance $building  The building to upgrade
     * @param integer          $increment The upgrade gap
     *
     * @throws \Exception If there is not enough resources in the stronghold
     */
    public function processBuilding(BuildingInstance $building, $newRoundStart, $newColumnStart)
    {
        //before anything, check if the new positions aren't outside the stronghold size
        if($newRoundStart<0
          || $newColumnStart<0
          || $newRoundStart >= $building->getStronghold()->getLevel()->getLinesCount()
          || $newColumnStart >= $building->getStronghold()->getLevel()->getColumnsCount()
        )
          throw new \Exception('This is outside the map !');
      
        //first get all the setted building, except the moving one
        $otherBuildingsSpace = array();
        //get the stronghold building
        $stronghold = $building->getStronghold();
        foreach($stronghold->getBuildings() as $otherbuilding)
          if($otherbuilding->getId() != $building->getId())
            $otherBuildingsSpace[] = $this->getSpaceUsed($otherbuilding);
        
        foreach($otherBuildingsSpace as $otherBuildingSpace){
          if(!Moves::canMoveBuilding($newRoundStart, $newColumnStart, $building->getLevel()->getRoundCount(), $building->getLevel()->getColumnCount(), $otherBuildingSpace[0], $otherBuildingSpace[1], $otherBuildingSpace[2], $otherBuildingSpace[3]))
            throw new \Exception('This cannot be set here !');
        }
        
        $building->setRoundStart($newRoundStart)
                 ->setColumnStart($newColumnStart);
        
    }
    
    private function getSpaceUsed(BuildingInstance $building){
      return array(
                    $building->getRoundStart(),
                    $building->getColumnStart(),
                    $building->getLevel()->getRoundCount(),
                    $building->getLevel()->getColumnCount(),
                   );
      
    }
}