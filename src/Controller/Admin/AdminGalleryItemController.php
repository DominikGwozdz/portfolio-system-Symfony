<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Entity\Gallery;
use App\Entity\GalleryItem;
use App\Form\GalleryItemType;
use App\Form\GalleryType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminGalleryItemController
 * @package App\Controller\Admin
 * @isGranted("ROLE_ADMIN")
 */
class AdminGalleryItemController extends AdminController
{
    /**
     * @Route("/admin/gallery/add_mass_photos/{id}", name="admin_gallery_add_mass_photos")
     * @param null $id
     * @return Response
     */
    public function galleryAddMassPhotos(Request $request, $id = null)
    {
        $form = $this->createForm(GalleryItemType::class);
        //DODAJ TUTAJ MOZLIWOSC MULTIPLE WGRYWANIA ZDJEC

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {


        }

        return $this->render('admin/gallery_item_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
