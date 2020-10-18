<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\GalleryCategory;
use App\Service\VisitorCounterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @var VisitorCounterService
     */
    protected $visitorCounter;

    public function __construct(VisitorCounterService $visitorCounter)
    {
        $this->visitorCounter = $visitorCounter;
    }

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
     * @param Request $request
     * @return Response
     */
    public function gallery($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gallery = $em->getRepository(Gallery::class)->findOneBy(['slug' => $slug]);
        $galleryItems = $gallery->getGalleryItems();

        $this->visitorCounter->countVisit($request);

        return $this->render('gallery/gallery.html.twig', [
            'galleryItems' => $galleryItems,
            'gallery' => $gallery,
        ]);
    }
}
