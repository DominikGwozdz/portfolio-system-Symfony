<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Entity\Contact;
use App\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminContactController
 * @package App\Controller
 * @isGranted("ROLE_ADMIN")
 */
class AdminContactController extends AdminController
{
    /**
     * @Route("/admin/contact", name="admin_contact")
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Contact $aboutRepository */
        $contactRepository = $em->getRepository(Contact::class)->find(1);

        $contact = new Contact();
        $contact->setDescription($contactRepository->getDescription());

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            try{
                $contactRepository->setDescription($form->get('description')->getData());
                $em->persist($contactRepository);
                $em->flush();

                $this->addFlash("success", "Zapisano zmiany");
            } catch (\Exception $e) {
                $this->addFlash('error', "Wystąpił błąd przy zapisywaniu");
            }

        }

        return $this->render('admin/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
