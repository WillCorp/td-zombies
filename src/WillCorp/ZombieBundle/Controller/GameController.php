<?php

namespace WillCorp\ZombieBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WillCorp\ZombieBundle\Entity\BuildingInstance;
use WillCorp\ZombieBundle\Entity\StrongholdInstance;

/**
 * Class GameController
 *
 * @Route("/game")
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class GameController extends Controller
{
    /**
     * Process a stronghold upgrade
     * This action is only available through XML HTTP requests (i.e. AJAX)
     *
     * @param Request            $request    The HTTP request object
     * @param StrongholdInstance $stronghold The stronghold object to upgrade
     * @param integer            $increment  The upgrade gap
     *
     * @return Response
     *
     * @Route("/stronghold/{id}/upgrade",
     *      name="game_upgrade_stronghold",
     *      requirements={"id": "\d+"}
     * )
     * @Route("/stronghold/{id}/upgrade/{increment}",
     *      name="game_upgrade_stronghold_increment",
     *      requirements={"id": "\d+", "increment": "\d+"}
     * )
     */
    public function upgradeStrongholdAction(Request $request, StrongholdInstance $stronghold, $increment = 1)
    {
        $this->throwNotFoundUnless($request->isXmlHttpRequest());
        $this->throwNotFoundUnless($this->getUser() && $this->getUser()->getId() == $stronghold->getPlayer()->getId());

        $error = null;
        try {
            $this->getGameMechanic()->upgradeStronghold($stronghold, $increment);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->getJsonResponse($stronghold, $error);
    }

    /**
     * Process a building upgrade
     * This action is only available through XML HTTP requests (i.e. AJAX)
     *
     * @param Request          $request   The HTTP request object
     * @param BuildingInstance $building  The building object to upgrade
     * @param integer          $increment The upgrade gap
     *
     * @return Response
     *
     * @Route("/building/{id}/upgrade",
     *      name="game_upgrade_building",
     *      requirements={"id": "\d+"}
     * )
     * @Route("/building/{id}/upgrade/{increment}",
     *      name="game_upgrade_building_increment",
     *      requirements={"id": "\d+", "increment": "\d+"}
     * )
     */
    public function upgradeBuildingAction(Request $request, BuildingInstance $building, $increment = 1)
    {
        $this->throwNotFoundUnless($request->isXmlHttpRequest());
        $this->throwNotFoundUnless($this->getUser() && $this->getUser()->getId() == $building->getStronghold()->getPlayer()->getId());

        $error = null;
        try {
            $this->getGameMechanic()->upgradeBuilding($building, $increment);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->getJsonResponse($building, $error);
    }
}