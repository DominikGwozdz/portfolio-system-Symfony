<?php

namespace App\Controller;

use App\Entity\About;
use App\Entity\GalleryCategory;
use App\Form\AboutType;
use App\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/about", name="admin_about")
     * @param Request $request
     * @return Response
     */
    public function about(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var About $aboutRepository */
        $aboutRepository = $em->getRepository(About::class)->find(1);

        $about = new About();
        $about->setDescription($aboutRepository->getDescription());
        $about->setMetaDescription($aboutRepository->getMetaDescription());

        $form = $this->createForm(AboutType::class, $about);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try{
                /** @var UploadedFile $pictureFile */
                $pictureFile = $form->get('picture')->getData();
                if ($pictureFile) {

                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                    try {
                        //remove existing image first - then add new
                        $filesystem = new Filesystem();
                        $filesystem->remove('images/about/'.$aboutRepository->getPicture());

                        $pictureFile->move('images/about', $newFilename);
                        $aboutRepository->setPicture($newFilename);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Wystąpił błąd przy wgrywaniu zdjęcia');
                    }
                }


                $aboutRepository->setDescription($form->get('description')->getData());
                $aboutRepository->setMetaDescription($form->get('meta_description')->getData());
                $em->persist($aboutRepository);
                $em->flush();

                $this->addFlash("success", "Zapisano zmiany");
            } catch (\Exception $e) {
                $this->addFlash('error', "Wystąpił błąd przy zapisywaniu. Możliwe powody to zbyt duże zdjęcie lub zbyt długi opis dla wyszukiwarek");
            }

        }

        return $this->render('admin/about.html.twig', [
            'form' => $form->createView(),
        ]);
    }

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
            //Ogarnij edycje kategorii - jest good
        }

        return $this->render('admin/category_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
