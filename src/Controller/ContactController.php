<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // je récupère les données
            $contact=$form->getData();
            // dd($contact);
            $message=(new \Swift_Message('Nouveau contact'))
            //on attribue l'auteur du message
            ->setFrom($contact['email'])
            // on attribue le destinataire
            ->setTo('jeannet.julie@gmail.com')
            ->setBody(
                $this->renderView('emails/contact.html.twig', compact('contact')), 'text/html'
            );

            // j'envoie le message
            $mailer->send($message);
            return $this->redirectToRoute("home");

        }
        return $this->render('contact/index.html.twig', [
            'formContact' => $form->createView()
        ]);
    }
}
