<?php
/**
 * This file is part of the WillCorpZombieBundle package
 *
 * (c) Yann Eugoné <yann.eugone@gmail.com> ; William Sauvan <william.sauvan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;

/**
 * Square
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\SquareRepository")
 *
 * @Serialize\ExclusionPolicy("all")
 */
class Square
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="coordinate_x", type="integer")
     *
     * @Serialize\Expose
     */
    private $coordinateX;

    /**
     * @var integer
     *
     * @ORM\Column(name="coordinate_y", type="integer")
     *
     * @Serialize\Expose
     */
    private $coordinateY;

    /**
     * @var StrongholdInstance
     *
     * @ORM\OneToOne(targetEntity="WillCorp\ZombieBundle\Entity\StrongholdInstance", mappedBy="square")
     */
    private $stronghold;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set coordinateX
     *
     * @param integer $coordinateX
     * @return Square
     */
    public function setCoordinateX($coordinateX)
    {
        $this->coordinateX = $coordinateX;

        return $this;
    }

    /**
     * Get coordinateX
     *
     * @return integer 
     */
    public function getCoordinateX()
    {
        return $this->coordinateX;
    }

    /**
     * Set coordinateY
     *
     * @param integer $coordinateY
     * @return Square
     */
    public function setCoordinateY($coordinateY)
    {
        $this->coordinateY = $coordinateY;

        return $this;
    }

    /**
     * Get coordinateY
     *
     * @return integer 
     */
    public function getCoordinateY()
    {
        return $this->coordinateY;
    }

    /**
     * Set stronghold
     *
     * @param StrongholdInstance $stronghold
     * @return Square
     */
    public function setStronghold(StrongholdInstance $stronghold = null)
    {
        $this->stronghold = $stronghold;

        return $this;
    }

    /**
     * Get stronghold
     *
     * @return StrongholdInstance
     */
    public function getStronghold()
    {
        return $this->stronghold;
    }
}
