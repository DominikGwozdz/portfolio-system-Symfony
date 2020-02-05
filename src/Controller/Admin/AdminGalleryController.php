<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Entity\Gallery;
use App\Entity\GalleryItem;
use App\Form\GalleryType;
use Behat\Transliterator\Transliterator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
                $slugName = Transliterator::transliterate($form->get('name')->getData());
                $gallery->setSlug($slugName);
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

    /**
     * @Route("/admin/gallery/edit/{id}", name="admin_gallery_edit")
     * @param Request $request
     * @param null $id
     * @return RedirectResponse|Response
     */
    public function galleryEdit(Request $request, $id = null)
    {
        $emOldGallery = $this->getDoctrine()->getManager();
        /** @var Gallery $galleryRepository */
        $galleryRepository = $emOldGallery->getRepository(Gallery::class)->find($id);

        $gallery = new Gallery();
        $gallery->setName($galleryRepository->getName());
        $gallery->setIsVisible($galleryRepository->getIsVisible());
        $gallery->setIsProtected($galleryRepository->getIsProtected());
        $gallery->setPassword($galleryRepository->getPassword());
        $gallery->setCategory($galleryRepository->getCategory());

        $form = $this->createForm(GalleryType::class, $gallery);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $em = $this->getDoctrine()->getManager();

                $gallery = $em->getRepository(Gallery::class)->find($id);
                $gallery->setName($form->get('name')->getData());
                $gallery->setIsProtected($form->get('is_protected')->getData());
                $gallery->setPassword($form->get('password')->getData());
                $gallery->setIsVisible($form->get('is_visible')->getData());
                $gallery->setCategory($form->get('category')->getData());

                /** @var UploadedFile $pictureFile */
                $pictureFile = $form->get('picture')->getData();

                if ($pictureFile) {
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    try {
                        $pictureFile->move('images/gallery/'.$gallery->getDirectory().'/label', $newFilename);
                        $gallery->setPicture($newFilename);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Wystąpił błąd przy wgrywaniu zdjęcia');
                    }
                }

                $em->persist($gallery);
                $em->flush();

                $this->addFlash('success','Zmieniono istniejącą galerie');
                return $this->redirectToRoute('admin_gallery');

            } catch (\Exception $e) {
                $this->addFlash('error', "Wystąpił błąd przy zmianie galerii. Możliwy powód to zbyt duże zdjęcie okładki");
            }
        }

        return $this->render('admin/gallery_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/gallery/remove/{id}", name="admin_gallery_remove")
     * @param int $id
     * @return RedirectResponse
     */
    public function galleryRemove($id) : RedirectResponse
    {
        $emGallery = $this->getDoctrine()->getManager();
        $emGalleryItem = $this->getDoctrine()->getManager();

        $gallery = $emGallery->getRepository(Gallery::class)->find($id);
        $galleryItem = $emGalleryItem->getRepository(GalleryItem::class)->findBy(['gallery' => $id]);

        try {
            foreach ($galleryItem as $item)
            {
                //remove gallery item records from gallery
                $emGalleryItem->remove($item);
                $emGalleryItem->flush();
            }

            //remove entire directory of gallery with label and photos
            $filesGallery = new Filesystem();
            $filesGallery->remove('images/gallery/' . $gallery->getDirectory());

            //remove gallery record
            $emGallery->remove($gallery);
            $emGallery->flush();
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił nieoczekiwany błąd przy usuwaniu galerii');
        }

        $this->addFlash('success', 'Usunięto galerie!');
        return $this->redirectToRoute('admin_gallery');
    }
}
