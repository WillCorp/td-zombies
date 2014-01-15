<?php

namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StrongholdLevel
 *
 * @ORM\Table(name="stronghold_level")
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\StrongholdLevelRepository")
 */
class StrongholdLevel
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
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var array
     *
     * @ORM\Column(name="cost", type="json_array")
     */
    private $cost;

    /**
     * @var integer
     *
     * @ORM\Column(name="columns_count", type="integer")
     */
    private $columnsCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="lines_count", type="integer")
     */
    private $linesCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="building_max_level", type="integer")
     */
    private $buildingMaxLevel;

    /**
     * @var StrongholdInstance[]
     *
     * @ORM\OneToMany(targetEntity="WillCorp\ZombieBundle\Entity\StrongholdInstance", mappedBy="level")
     */
    private $strongholdInstances;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->strongholdInstances = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set level
     *
     * @param integer $level
     * @return StrongholdLevel
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set cost
     *
     * @param array $cost
     * @return StrongholdLevel
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return array
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set columnsCount
     *
     * @param integer $columnsCount
     * @return StrongholdLevel
     */
    public function setColumnsCount($columnsCount)
    {
        $this->columnsCount = $columnsCount;

        return $this;
    }

    /**
     * Get columnsCount
     *
     * @return integer 
     */
    public function getColumnsCount()
    {
        return $this->columnsCount;
    }

    /**
     * Set linesCount
     *
     * @param integer $linesCount
     * @return StrongholdLevel
     */
    public function setLinesCount($linesCount)
    {
        $this->linesCount = $linesCount;

        return $this;
    }

    /**
     * Get linesCount
     *
     * @return integer 
     */
    public function getLinesCount()
    {
        return $this->linesCount;
    }

    /**
     * Set buildingMaxLevel
     *
     * @param integer $buildingMaxLevel
     * @return StrongholdLevel
     */
    public function setBuildingMaxLevel($buildingMaxLevel)
    {
        $this->buildingMaxLevel = $buildingMaxLevel;

        return $this;
    }

    /**
     * Get buildingMaxLevel
     *
     * @return integer 
     */
    public function getBuildingMaxLevel()
    {
        return $this->buildingMaxLevel;
    }

    /**
     * Add strongholdInstances
     *
     * @param StrongholdInstance $strongholdInstances
     * @return StrongholdLevel
     */
    public function addStrongholdInstance(StrongholdInstance $strongholdInstances)
    {
        $this->strongholdInstances[] = $strongholdInstances;

        return $this;
    }

    /**
     * Remove strongholdInstances
     *
     * @param StrongholdInstance $strongholdInstances
     */
    public function removeStrongholdInstance(StrongholdInstance $strongholdInstances)
    {
        $this->strongholdInstances->removeElement($strongholdInstances);
    }

    /**
     * Get strongholdInstances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStrongholdInstances()
    {
        return $this->strongholdInstances;
    }
}
