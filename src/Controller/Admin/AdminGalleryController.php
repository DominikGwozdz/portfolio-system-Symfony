<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Entity\Gallery;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminGalleryController
 * @package App\Controller\Admin
 * @isGranted("ROLE_ADMIN")
 */
class AdminGalleryController extends AdminController
{
    /**
     * @Route("/admin/gallery", name="admin_gallery")
     * @return Response
     */
    public function gallery()
    {
        $em = $this->getDoctrine()->getManager();
        $galleryRepository = $em->getRepository(Gallery::class)->findAll();

        return $this->render('admin/gallery.html.twig', [
            'galleries' => $galleryRepository,
        ]);
    }
}
