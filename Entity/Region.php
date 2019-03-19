<?php

namespace Chaplean\Bundle\LocationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Region
 *
 * @ORM\Table(
 *     name="cl_region",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="region_code_UNIQUE", columns={"code"})}
 * )
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\LocationBundle\Repository\RegionRepository")
 */
class Region extends Location
{
    const DOM_TOM_REGION_CODES = ['01', '02', '03', '04', '06', '99'];

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=2, nullable=false, name="code")
     *
     * @Groups({"region_code"})
     */
    private $code;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chaplean\Bundle\LocationBundle\Entity\Department", mappedBy="region")
     *
     * @MaxDepth(1)
     * @Groups({"region_departments"})
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
     * Get the Region associated with this Location if any
     *
     * @return Region|null
     */
    public function getRegion()
    {
        return $this;
    }

    /**
     * Get the Department associated with this Location if any
     *
     * @return Department|null
     */
    public function getDepartment()
    {
        return null;
    }

    /**
     * Get the City associated with this Location if any
     *
     * @return City|null
     */
    public function getCity()
    {
        return null;
    }

    /**
     * Returns wether or not the given $location contains this Location
     *
     * @param Location|null $location
     *
     * @return boolean
     */
    public function containsLocation(Location $location = null)
    {
        return $this->compareIds($location !== null ? $location->getRegion() : null);
    }
}
