<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationRepository")
 */
class Configuration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $site_address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $site_name;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $site_description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteAddress(): ?string
    {
        return $this->site_address;
    }

    public function setSiteAddress(?string $site_address): self
    {
        $this->site_address = $site_address;

        return $this;
    }

    public function getSiteName(): ?string
    {
        return $this->site_name;
    }

    public function setSiteName(?string $site_name): self
    {
        $this->site_name = $site_name;

        return $this;
    }

    public function getSiteDescription(): ?string
    {
        return $this->site_description;
    }

    public function setSiteDescription(?string $site_description): self
    {
        $this->site_description = $site_description;

        return $this;
    }
}
