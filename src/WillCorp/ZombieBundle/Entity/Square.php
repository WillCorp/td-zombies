<?php

namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Square
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\SquareRepository")
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
     */
    private $coordinateX;

    /**
     * @var integer
     *
     * @ORM\Column(name="coordinate_y", type="integer")
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
