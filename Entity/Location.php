<?php
namespace Chaplean\Bundle\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_location", indexes={@ORM\Index(name="name_INDEX", columns={"name"})})
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="dtype", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "city"="Chaplean\Bundle\LocationBundle\Entity\City",
 *     "department"="Chaplean\Bundle\LocationBundle\Entity\Department",
 *     "region"="Chaplean\Bundle\LocationBundle\Entity\Region"
 * }
 * )
 */
abstract class Location
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Groups({"location_id"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @JMS\Groups({"location_name"})
     */
    protected $name;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
