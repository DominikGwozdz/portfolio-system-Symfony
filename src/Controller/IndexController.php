<?php

namespace App\Controller;

use App\Entity\Gallery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $visibleGalleries = $em->getRepository(Gallery::class)->findBy(['is_visible' => true], ['id' => 'DESC'], 8);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'galleries' => $visibleGalleries,
        ]);
    }
}
