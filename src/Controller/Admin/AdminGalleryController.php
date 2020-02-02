<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Entity\Gallery;
use App\Form\GalleryType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/admin/gallery/add", name="admin_gallery_add")
     * @param Request $request
     * @return Response
     */
    public function galleryAdd(Request $request)
    {
        $form = $this->createForm(GalleryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();

                $gallery = new Gallery();
                $gallery->setName($form->get('name')->getData());
                $gallery->setIsProtected($form->get('is_protected')->getData());
                $gallery->setPassword($form->get('password')->getData());
                $gallery->setIsVisible($form->get('is_visible')->getData());
                $gallery->setCategory($form->get('category')->getData());

                $generateHash = md5(time());
                $gallery->setDirectory($generateHash);

                /** @var UploadedFile $pictureFile */
                $pictureFile = $form->get('picture')->getData();

                if ($pictureFile) {
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    try {
                        $pictureFile->move('images/gallery/'.$generateHash.'/label', $newFilename);
                        $gallery->setPicture($newFilename);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Wystąpił błąd przy wgrywaniu zdjęcia');
                    }
                }

                $em->persist($gallery);
                $em->flush();

                $this->addFlash('success','Dodano nową galerie');
                return $this->redirectToRoute('admin_gallery');

            } catch (\Exception $e) {
                $this->addFlash('error', "Wystąpił błąd przy dodawaniu nowej galerii. Możliwy powód to zbyt duże zdjęcie okładki");
            }
        }

        return $this->render('admin/gallery_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
