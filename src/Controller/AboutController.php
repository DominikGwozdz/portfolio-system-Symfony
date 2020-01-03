<?php

namespace App\Controller;

use App\Entity\About;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     */
    public function index()
    {
        //always get id = 1 because there is only 1 record in this table
        $about = $this->getDoctrine()->getRepository(About::class)->find(1);

        return $this->render('about/index.html.twig', [
            'about' => $about
        ]);
    }
}
