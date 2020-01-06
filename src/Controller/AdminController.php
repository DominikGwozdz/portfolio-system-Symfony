<?php

namespace App\Controller;

use App\Entity\About;
use App\Form\AboutType;
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
        return $this->render('admin/category.html.twig', [
            'test' => "test",
        ]);
    }
}
