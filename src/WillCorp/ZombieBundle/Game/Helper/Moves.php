<?php

namespace WillCorp\ZombieBundle\Game\Helper;

use Proxies\__CG__\WillCorp\ZombieBundle\Entity\BuildingInstance;
/**
 * Class Resources
 *
 * @author Sauvan William <sauvan.william@gmail.com>
 */
final class Moves
{

    /**
     * This class should not be instantiated
     */
    private function __construct()
    {
    }
    
    /**
     * Indicates if the set of position is available, i.e. two building are using the same spot
     * 
     * @param integer $b1_roundstart The round of the first building
     * @param integer $b1_columnstart The column of the first building
     * @param integer $b1_roundcount The round number of the first building
     * @param integer $b1_columncount The column number of the first building
     * @param integer $b2_roundstart The round of the second building
     * @param integer $b2_columnstart The column of the second building
     * @param integer $b2_roundcount The round number of the second building
     * @param integer $b2_columncount The column number of the second building
     * 
     * @return boolean
     */
    public static function canMoveBuilding($b1_roundstart, $b1_columnstart, $b1_roundcount, $b1_columncount, $b2_roundstart, $b2_columnstart, $b2_roundcount, $b2_columncount)
    {
        $isFree = false;
        
        //check all the egde, if at least one rule is true, the two squares can't overlap
        if( ($b1_roundstart > $b2_roundstart + $b2_roundcount - 1) //left edge
            ||
            ($b2_roundstart > $b1_roundstart + $b1_roundcount - 1) //right edge
            ||
            ($b1_columnstart > $b2_columnstart + $b2_columncount - 1) //top edge
            ||
            ($b2_columnstart > $b1_columnstart + $b1_columncount - 1) //bottom edge
        )
          $isFree = true;
        

        return $isFree;
    }
    
}