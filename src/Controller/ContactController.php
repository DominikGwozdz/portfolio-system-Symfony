<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository(Contact::class)->find(1);

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contact' => $contact
        ]);
    }
}
