<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryRepository")
 */
class Gallery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $directory;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_visible;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_protected;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GalleryCategory", inversedBy="galleries")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GalleryItem", mappedBy="gallery")
     */
    private $galleryItems;

    /**
     * @ORM\OneToMany(targetEntity=Visit::class, mappedBy="gallery")
     */
    private $visits;

    public function __construct()
    {
        $this->galleryItems = new ArrayCollection();
        $this->visits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): self
    {
        $this->is_visible = $is_visible;

        return $this;
    }

    public function getIsProtected(): ?bool
    {
        return $this->is_protected;
    }

    public function setIsProtected(bool $is_protected): self
    {
        $this->is_protected = $is_protected;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCategory(): ?GalleryCategory
    {
        return $this->category;
    }

    public function setCategory(?GalleryCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|GalleryItem[]
     */
    public function getGalleryItems(): Collection
    {
        return $this->galleryItems;
    }

    public function addGalleryItem(GalleryItem $galleryItem): self
    {
        if (!$this->galleryItems->contains($galleryItem)) {
            $this->galleryItems[] = $galleryItem;
            $galleryItem->setGallery($this);
        }

        return $this;
    }

    public function removeGalleryItem(GalleryItem $galleryItem): self
    {
        if ($this->galleryItems->contains($galleryItem)) {
            $this->galleryItems->removeElement($galleryItem);
            // set the owning side to null (unless already changed)
            if ($galleryItem->getGallery() === $this) {
                $galleryItem->setGallery(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits[] = $visit;
            $visit->setGallery($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->contains($visit)) {
            $this->visits->removeElement($visit);
            // set the owning side to null (unless already changed)
            if ($visit->getGallery() === $this) {
                $visit->setGallery(null);
            }
        }

        return $this;
    }
}
