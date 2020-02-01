<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Entity\GalleryCategory;
use App\Form\CategoryType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminCategoryController
 * @package App\Controller\Admin
 * @isGranted("ROLE_ADMIN")
 */
class AdminCategoryController extends AdminController
{
    /**
     * @Route("/admin/category", name="admin_category")
     * @return Response
     */
    public function category()
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(GalleryCategory::class)->findAll();

        return $this->render('admin/category.html.twig', [
            'categories' => $categoryRepository,
        ]);
    }

    /**
     * @Route("/admin/category/add", name="admin_category_add")
     * @param Request $request
     * @return Response
     */
    public function category_add(Request $request)
    {
        $form = $this->createForm(CategoryType::class);

        //handle request is executed when request has method post
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();

                $category = new GalleryCategory();
                $category->setName($form->get('name')->getData());
                $category->setIsVisible($form->get('is_visible')->getData());

                /** @var UploadedFile $pictureFile */
                $pictureFile = $form->get('picture')->getData();

                if ($pictureFile) {
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    try {
                        $pictureFile->move('images/categories', $newFilename);
                        $category->setPicture($newFilename);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Wystąpił błąd przy wgrywaniu zdjęcia');
                    }
                }
                $em->persist($category);
                $em->flush();

                $this->addFlash('success', 'Dodano nową kategorie');

                return $this->redirectToRoute('admin_category');
            } catch (\Exception $e) {
                $this->addFlash('error', "Wystąpił błąd przy tworzeniu. Możliwy powód to zbyt duże zdjęcie");
            }
        }

        return $this->render('admin/category_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="admin_category_edit")
     * @param Request $request
     * @param null $id
     * @return Response
     */
    public function category_edit(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var GalleryCategory $galleryCategoryRepository */
        $galleryCategoryRepository = $em->getRepository(GalleryCategory::class)->find($id);

        $galleryCategory = new GalleryCategory();
        $galleryCategory->setName($galleryCategoryRepository->getName());
        $galleryCategory->setIsVisible($galleryCategoryRepository->getIsVisible());

        $form = $this->createForm(CategoryType::class, $galleryCategory);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $category = $em->getRepository(GalleryCategory::class)->find($id);
                $category->setName($form->get('name')->getData());
                $category->setIsVisible($form->get('is_visible')->getData());

                /** @var UploadedFile $pictureFile */
                $pictureFile = $form->get('picture')->getData();

                if ($pictureFile) {
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    try {
                        $pictureFile->move('images/categories', $newFilename);
                        $category->setPicture($newFilename);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Wystąpił błąd przy wgrywaniu zdjęcia');
                    }
                }
                $em->persist($category);
                $em->flush();

                $this->addFlash('success', 'Poprawnie zmieniono kategorie');

                return $this->redirectToRoute('admin_category');
            } catch (\Exception $e) {
                $this->addFlash('error', "Wystąpił błąd przy edycji. Możliwy powód to zbyt duże zdjęcie");
            }
        }

        return $this->render('admin/category_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/remove/{id}", name="admin_category_remove")
     * @param Request $request
     * @param null $id
     * @return Response
     */
    public function category_delete(Request $request, $id = null)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $galleryCategory = $em->getRepository(GalleryCategory::class)->find($id);

            $filesystem = new Filesystem();
            if ($filesystem->exists('images/categories/' . $galleryCategory->getPicture())) {
                $filesystem->remove('images/categories/' . $galleryCategory->getPicture());
            }
            $em->remove($galleryCategory);
            $em->flush();
            $this->addFlash('success','Usunięto kategorie!');
            return $this->redirectToRoute('admin_category');
        } catch (\Exception $e) {
            $this->addFlash('error','Nie można usunąć kategorii - byc może zawiera juz jakies galerie. Najpierw usuń wszystkie galerie z kategorii!');
        }
    }
}
