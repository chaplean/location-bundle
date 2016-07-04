<?php

namespace Chaplean\Bundle\LocationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Department
 *
 * @ORM\Table(
 *     name="cl_department",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="department_code_UNIQUE", columns={"code"})}
 * )
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\LocationBundle\Repository\DepartmentRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Department extends Location
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=3, nullable=false, name="code")
     *
     * @JMS\Expose
     * @JMS\Groups({"department_code"})
     */
    private $code;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Chaplean\Bundle\LocationBundle\Entity\Region", inversedBy="departments")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", nullable=false)
     *
     * @JMS\Expose
     * @JMS\MaxDepth(depth=1)
     * @JMS\Groups({"department_region"})
     */
    private $region;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chaplean\Bundle\LocationBundle\Entity\City", mappedBy="department")
     *
     * @JMS\Expose
     * @JMS\MaxDepth(depth=1)
     * @JMS\Groups({"department_cities"})
     */
    private $cities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cities = new ArrayCollection();
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
     * @return Department
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set region
     *
     * @param Region $region
     *
     * @return Department
     */
    public function setRegion(Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Add city
     *
     * @param City $city
     *
     * @return Department
     */
    public function addCity(City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param City $city
     *
     * @return void
     */
    public function removeCity(City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities
     *
     * @return ArrayCollection
     */
    public function getCities()
    {
        return $this->cities;
    }
}
