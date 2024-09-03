<?php

namespace App\Controller\Frontend;

use App\Entity\Contact;
use App\Form\Frontend\ContactFormType;
use App\Repository\AvisRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class AppController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private EmailVerifier $emailVerifier,
    ) {}

    #[Route('/', name: 'app_app', methods: ['GET', 'POST'])]
    public function index(Request $request, AvisRepository $avisRepository, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        $avis = $avisRepository->findThreeRandomAvis();

        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $html = '<p>Vous avez reçu un message de : '. $contact->getFirstname() . ' ' . $contact->getLastname() .'</p>
            <p>' . $contact->getEmail() . '</p>
            <p>Message : </p>
            <p>' . $contact->getMessage() .'</p>';
            $emailTemplated = new Email();
            $emailTemplated->from(new Address('anthony.koenig.testing@outlook.com', 'No-reply-cct-tolerie'))
                ->to('anthony.kng.pro@gmail.com')
                ->subject('Demande de contact')
                ->replyTo($contact->getEmail())
                ->html($html);

            $mailer->send($emailTemplated);

            $this->em->persist($contact);
            $this->em->flush();

            $this->addFlash('success', 'Nous avons reçu votre message ! Nous reviendrons vers vous au plus vite.');

            return $this->redirectToRoute('app_app');
        }

        return $this->render('app/index.html.twig', [
            'avis' => $avis,
            'form' => $form,
        ]);
    }
}