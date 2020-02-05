<?php

namespace App\Controller;

use App\Entity\GalleryCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery", name="gallery")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(GalleryCategory::class)->findBy(['is_visible' => '1']);
        return $this->render('gallery/index.html.twig', [
            'controller_name' => 'GalleryController',
            'categories' => $categories,
        ]);
    }
}
