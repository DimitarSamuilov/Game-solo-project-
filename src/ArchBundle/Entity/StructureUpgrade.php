<?php

namespace ArchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StructureUpgrade
 *
 * @ORM\Table(name="structure_upgrades")
 * @ORM\Entity(repositoryClass="ArchBundle\Repository\StructureUpgradeRepository")
 */
class StructureUpgrade
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishesOn", type="datetime")
     */
    private $finishesOn;

    /**
     * @var Structure
     * @ORM\OneToOne(targetEntity="ArchBundle\Entity\Structure",inversedBy="structureUpgrade")
     * @ORM\JoinTable(name="structure_id")
     */
    private $structure;

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set finishesOn
     *
     * @param \DateTime $finishesOn
     *
     * @return StructureUpgrade
     */
    public function setFinishesOn($finishesOn)
    {
        $this->finishesOn = $finishesOn;

        return $this;
    }

    /**
     * Get finishesOn
     *
     * @return \DateTime
     */
    public function getFinishesOn()
    {
        return $this->finishesOn;
    }
}

