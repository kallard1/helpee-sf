<?php

declare(strict_types=1);

/**
 * This file is a part of Helpee
 *
 * @author  Kevin Allard <contact@allard-kevin.fr>
 *
 * @license 2018
 */

namespace App\Entity\Ad;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Category.
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Entity(repositoryClass="App\Repository\Ad\CategoryRepository")
 * @ORM\Table(name="ads_categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $label;

    /**
     * @Gedmo\Slug(fields={"label"})
     *
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @Gedmo\TreeLeft
     *
     * @ORM\Column(name="lft", type="integer", nullable=true)
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     *
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     *
     * @ORM\Column(name="rgt", type="integer", nullable=true)
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent(self $parent = null): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->lvl;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
