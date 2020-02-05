<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\GalleryCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/gallery/category/{slug}", name="category")
     * @param string $slug
     * @return Response
     */
    public function category($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $galleryCategoryRepository = $em->getRepository(GalleryCategory::class)->findOneBy(['slug' => $slug]);
        $galleries = $galleryCategoryRepository->getGalleries();

        return $this->render('gallery/category.html.twig', [
            'galleries' => $galleries,
        ]);

    }

    /**
     * @Route("/gallery/{slug}", name="gallery_show")
     * @param $slug
     * @return Response
     */
    public function gallery($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository(Gallery::class)->findOneBy(['slug' => $slug]);
        $galleryItems = $gallery->getGalleryItems();



        return $this->render('gallery/gallery.html.twig', [
            'galleryItems' => $galleryItems,
            'gallery' => $gallery,
        ]);
    }
}
