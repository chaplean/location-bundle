<?php

namespace Chaplean\Bundle\LocationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Region
 *
 * @ORM\Table(name="cl_region", uniqueConstraints={@ORM\UniqueConstraint(name="code_INDEX", columns={"code"})})
 * @ORM\Entity
 */
class Region extends Location
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=2, nullable=false, name="code")
     *
     * @JMS\Groups({"region_code"})
     */
    private $code;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chaplean\Bundle\LocationBundle\Entity\Department", mappedBy="region")
     *
     * @JMS\MaxDepth(depth=1)
     * @JMS\Groups({"region_departments"})
     */
    private $departments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departments = new ArrayCollection();
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Region
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
    
    /**
     * Add department
     *
     * @param Department $department
     *
     * @return Region
     */
    public function addDepartment(Department $department)
    {
        $this->departments[] = $department;

        return $this;
    }

    /**
     * Remove departments
     *
     * @param Department $department
     *
     * @return void
     */
    public function removeDepartment(Department $department)
    {
        $this->departments->removeElement($department);
    }

    /**
     * Get departments
     *
     * @return ArrayCollection
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Get Highchart key
     *
     * @return string
     */
    public function getHighchartKey()
    {
        return isset($this->highchartData[$this->getId()]) ? $this->highchartData[$this->getId()] : null;
    }
}
