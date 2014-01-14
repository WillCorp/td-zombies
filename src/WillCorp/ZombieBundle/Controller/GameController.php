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
     *
     * @return Response
     *
     * @Route("/stronghold/{id}/upgrade/", name="game_upgrade_stronghold")
     */
    public function upgradeStrongholdAction(Request $request, StrongholdInstance $stronghold)
    {
        $this->throwNotFoundUnless($request->isXmlHttpRequest());
        $this->throwNotFoundUnless($this->getUser() && $this->getUser()->getId() == $stronghold->getPlayer()->getId());

        $error = null;
        try {
            $this->getGameMecanic()->upgradeStronghold($stronghold);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->getJsonResponse($stronghold, $error);
    }

    /**
     * Process a building upgrade
     * This action is only available through XML HTTP requests (i.e. AJAX)
     *
     * @param Request          $request  The HTTP request object
     * @param BuildingInstance $building The building object to upgrade
     *
     * @return Response
     *
     * @Route("/building/{id}/upgrade/", name="game_upgrade_building")
     */
    public function upgradeBuildingAction(Request $request, BuildingInstance $building)
    {
        $this->throwNotFoundUnless($request->isXmlHttpRequest());
        $this->throwNotFoundUnless($this->getUser() && $this->getUser()->getId() == $building->getStronghold()->getPlayer()->getId());

        $error = null;
        try {
            $this->getGameMecanic()->upgradeBuilding($building);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return $this->getJsonResponse($building, $error);
    }
}