<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Entity\Gallery;
use App\Entity\GalleryItem;
use App\Form\GalleryItemType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @param Request $request
     * @param null $id
     * @return Response
     */
    public function galleryAddMassPhotos(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Gallery $gallery */
        $gallery = $em->getRepository(Gallery::class)->find($id);
        $gallerySourceDirectory = $gallery->getDirectory();

        $form = $this->createForm(GalleryItemType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $emGalleryItem = $this->getDoctrine()->getManager();

            // array of files
            $files = $request->files->get('gallery_item')['picture'];
            /** @var UploadedFile $file */
            foreach ($files as $file) {
                try {
                    $galleryItem = new GalleryItem();

                    $filename = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move("images/gallery/".$gallerySourceDirectory, $filename);

                    $galleryItem->setPicture($filename);
                    $galleryItem->setGallery($gallery);

                    $emGalleryItem->persist($galleryItem);
                    $emGalleryItem->flush();
                    $this->addFlash('success', 'Dodano wszystkie zdjęcia!');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Wystąpił błąd przy dodawaniu zdjęć');
                }
            }
        }

        return $this->render('admin/gallery_item_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
