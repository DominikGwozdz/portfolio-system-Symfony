<?php

namespace App\Service;

use App\Entity\Configuration;
use Doctrine\ORM\EntityManagerInterface;

class ConfigurationHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return string|null
     */
    public function getSiteAddress()
    {
        return $this->em->getRepository(Configuration::class)->find(1)->getSiteAddress();
    }

    /**
     * @return string|null
     */
    public function getSiteName()
    {
        return $this->em->getRepository(Configuration::class)->find(1)->getSiteName();
    }

    /**
     * @return string|null
     */
    public function getSiteDescription()
    {
        return $this->em->getRepository(Configuration::class)->find(1)->getSiteDescription();
    }
}
