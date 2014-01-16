<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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

    /**
     * Subtract the resources $cost to the resources $supply and return the rest
     *
     * @param array $supply The resources supply
     * @param array $cost   The resources cost to subtract
     *
     * @return array
     * @throws \Exception If there is not enough resources in $supply
     */
    public static function subtractResources(array $supply, array $cost)
    {
        if (!static::hasEnoughResources($supply, $cost)) {
            throw new \Exception(sprintf('There is not enough resource for subtraction'));
        }

        $newSupply = $supply;
        foreach ($cost as $resource => $value) {
            $newSupply[$resource] -= $value;
        }

        return $newSupply;
    }

    /**
     * Add the resources $extra to the resources $supply and return the result
     *
     * @param array $supply The resources supply
     * @param array $extra  The resources extra to add
     *
     * @return array
     */
    public static function addResources(array $supply, array $extra)
    {
        $newSupply = $supply;
        foreach ($extra as $resource => $value) {
            if (!array_key_exists($resource, $newSupply)) {
                $newSupply[$resource] = 0;
            }
            $newSupply[$resource] += $value;
        }

        return $newSupply;
    }
}