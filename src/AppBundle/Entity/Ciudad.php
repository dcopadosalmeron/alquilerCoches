<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Ciudad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $nombre;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $provincia;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Coche", mappedBy="ciudad")
     */
    protected $coches;

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Ciudad
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     *
     * @return Ciudad
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->coches = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add coch
     *
     * @param \AppBundle\Entity\Coche $coch
     *
     * @return Ciudad
     */
    public function addCoch(\AppBundle\Entity\Coche $coch)
    {
        $this->coches[] = $coch;

        return $this;
    }

    /**
     * Remove coch
     *
     * @param \AppBundle\Entity\Coche $coch
     */
    public function removeCoch(\AppBundle\Entity\Coche $coch)
    {
        $this->coches->removeElement($coch);
    }

    /**
     * Get coches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoches()
    {
        return $this->coches;
    }
}
