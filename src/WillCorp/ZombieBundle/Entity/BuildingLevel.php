<?php

namespace WillCorp\ZombieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BuildingLevel
 *
 * @ORM\Table(name="building_level")
 * @ORM\Entity(repositoryClass="WillCorp\ZombieBundle\Repository\BuildingLevelRepository")
 */
class BuildingLevel
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
     * @var array
     *
     * @ORM\Column(name="income", type="json_array")
     */
    private $income;

    /**
     * @var integer
     *
     * @ORM\Column(name="defense", type="integer")
     */
    private $defense;

    /**
     * @var integer
     *
     * @ORM\Column(name="roundCount", type="integer")
     */
    private $roundCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="columnsCount", type="integer")
     */
    private $columnsCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="unitCountLimit", type="integer")
     */
    private $unitCountLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="unitCooldown", type="integer")
     */
    private $unitCooldown;

    /**
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="WillCorp\ZombieBundle\Entity\Building", inversedBy="levels")
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     */
    private $building;

    /**
     * @var BuildingInstance[]
     *
     * @ORM\OneToMany(targetEntity="WillCorp\ZombieBundle\Entity\BuildingInstance", mappedBy="level")
     */
    private $buildingInstances;

    /**
     * @var UnitLevel
     *
     * @ORM\ManyToOne(targetEntity="WillCorp\ZombieBundle\Entity\UnitLevel", inversedBy="buildings")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     */
    private $unit;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->buildingInstances = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return BuildingLevel
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
     * @return BuildingLevel
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
     * Set income
     *
     * @param array $income
     * @return BuildingLevel
     */
    public function setIncome($income)
    {
        $this->income = $income;

        return $this;
    }

    /**
     * Get income
     *
     * @return array 
     */
    public function getIncome()
    {
        return $this->income;
    }

    /**
     * Set defense
     *
     * @param integer $defense
     * @return BuildingLevel
     */
    public function setDefense($defense)
    {
        $this->defense = $defense;

        return $this;
    }

    /**
     * Get defense
     *
     * @return integer 
     */
    public function getDefense()
    {
        return $this->defense;
    }

    /**
     * Set roundCount
     *
     * @param integer $roundCount
     * @return BuildingLevel
     */
    public function setRoundCount($roundCount)
    {
        $this->roundCount = $roundCount;

        return $this;
    }

    /**
     * Get roundCount
     *
     * @return integer 
     */
    public function getRoundCount()
    {
        return $this->roundCount;
    }

    /**
     * Set columnsCount
     *
     * @param integer $columnsCount
     * @return BuildingLevel
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
     * Set unitCountLimit
     *
     * @param integer $unitCountLimit
     * @return BuildingLevel
     */
    public function setUnitCountLimit($unitCountLimit)
    {
        $this->unitCountLimit = $unitCountLimit;

        return $this;
    }

    /**
     * Get unitCountLimit
     *
     * @return integer 
     */
    public function getUnitCountLimit()
    {
        return $this->unitCountLimit;
    }

    /**
     * Set unitCooldown
     *
     * @param integer $unitCooldown
     * @return BuildingLevel
     */
    public function setUnitCooldown($unitCooldown)
    {
        $this->unitCooldown = $unitCooldown;

        return $this;
    }

    /**
     * Get unitCooldown
     *
     * @return integer 
     */
    public function getUnitCooldown()
    {
        return $this->unitCooldown;
    }

    /**
     * Set building
     *
     * @param Building $building
     * @return BuildingLevel
     */
    public function setBuilding(Building $building = null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return Building
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Add buildingInstances
     *
     * @param BuildingInstance $buildingInstances
     * @return BuildingLevel
     */
    public function addBuildingInstance(BuildingInstance $buildingInstances)
    {
        $this->buildingInstances[] = $buildingInstances;

        return $this;
    }

    /**
     * Remove buildingInstances
     *
     * @param BuildingInstance $buildingInstances
     */
    public function removeBuildingInstance(BuildingInstance $buildingInstances)
    {
        $this->buildingInstances->removeElement($buildingInstances);
    }

    /**
     * Get buildingInstances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBuildingInstances()
    {
        return $this->buildingInstances;
    }

    /**
     * Set unit
     *
     * @param UnitLevel $unit
     * @return BuildingLevel
     */
    public function setUnit(UnitLevel $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return UnitLevel
     */
    public function getUnit()
    {
        return $this->unit;
    }
}
