<?php

namespace App\Service;

use App\Entity\Gallery;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorCounterService
{
    const GALLERY_VISIT_PREFIX = 'vg_';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Gallery $gallery
     * @param Request $request
     * @return void
     */
    public function countVisit(Gallery $gallery, Request $request) : void
    {
        $cookies = $request->cookies->all();
        if (!array_key_exists(self::GALLERY_VISIT_PREFIX . $gallery->getId(), $cookies)) {
            $cookie = Cookie::create(self::GALLERY_VISIT_PREFIX . $gallery->getId())
                ->withValue('true')
                ->withExpires(new \DateTime('+3 days'))
                ->withDomain(null)
                ->withSecure(false)
                ->withHttpOnly(false);

            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->sendHeaders();

            $now = new \DateTime();
            /** @var Visit $todayVisit */
            $todayVisit = $this->entityManager->getRepository(Visit::class)->findOneBy([
                'date' => $now,
                'gallery' => $gallery->getId()
            ]);
            if ($todayVisit) {
                $currentCount = $todayVisit->getCount();
                $todayVisit->setCount($currentCount + 1);
                $this->entityManager->persist($todayVisit);
                $this->entityManager->flush();
            } else {
                $visit = new Visit();
                $visit->setCount(1);
                $visit->setDate($now);
                $visit->setGallery($gallery);
                $this->entityManager->persist($visit);
                $this->entityManager->flush();
            }
        }
    }
}