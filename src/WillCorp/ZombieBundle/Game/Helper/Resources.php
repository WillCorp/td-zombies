<?php

namespace WillCorp\ZombieBundle\Game\Helper;

/**
 * Class Resources
 *
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
final class Resources
{
    const ENERGY = 'energy';
    const METAL = 'metal';

    /**
     * This class should not be instantiated
     */
    private function __construct()
    {
    }

    /**
     * Return the list of the resources types
     *
     * @return array
     */
    public static function getResourcesTypes()
    {
        return array(
            static::ENERGY,
            static::METAL,
        );
    }

    /**
     * Indicates whether there is enough resources in $disposal to handle $cost
     *
     * @param array $disposal The resources disposal
     * @param array $cost     The resources cost
     *
     * @return boolean
     */
    public static function hasEnoughResources(array $disposal, array $cost)
    {
        $enough = true;
        $resourceTypes = static::getResourcesTypes();
        $i = 0;
        while ($enough && array_key_exists($i, $resourceTypes)) {
            $resourceDisposal = array_key_exists($resourceTypes[$i], $disposal) ? $disposal[$resourceTypes[$i]] : 0;
            $resourceCost = array_key_exists($resourceTypes[$i], $cost) ? $cost[$resourceTypes[$i]] : 0;

            $enough = $resourceDisposal >= $resourceCost;
            $i++;
        }

        return $enough;
    }
}