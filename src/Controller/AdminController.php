<?php

namespace App\Controller;

use App\Entity\About;
use App\Form\AboutType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function about()
    {
        $about = new About();
        $form = $this->createForm(AboutType::class, $about);

        return $this->render('admin/about.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
