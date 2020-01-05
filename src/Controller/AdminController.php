<?php

namespace App\Controller;

use App\Entity\About;
use App\Form\AboutType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\ExceptionInterface;
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
}
