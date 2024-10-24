<?php

namespace App\Controller\Frontend;

use App\Entity\Categorie;
use App\Entity\Contact;
use App\Entity\Realisation;
use App\Form\Frontend\ContactFormType;
use App\Repository\AvisRepository;
use App\Repository\CategorieRepository;
use App\Repository\RealisationRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
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
        private CategorieRepository $categoryRepository,
    ) {}

    #[Route('/', name: 'app_app', methods: ['GET', 'POST'])]
    public function index(Request $request, AvisRepository $avisRepository, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        $categories = $this->categoryRepository->findAll();

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
            'categories' => $categories,
        ]);
    }

    #[Route('/categories', name: 'app_categories', methods: ['GET'])]
    public function showCategories() : Response {

        $categories = $this->categoryRepository->findAll();

        return $this->render('app/Categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    #[Route('/categories/{slug}', name:'app_categories_name', methods:['GET'])]
    public function showRealisations(Categorie $category): Response {

        $realisations = $category->getRealisations();

        return $this->render('app/Categories/showCategoriesRealisations.html.twig', [
            'realisations' => $realisations,
            'categorie' => $category,
        ]);
    }

    #[Route('categories/{slug}/{id}-{slugr}', name:'app_realisations_name', methods: ['GET'])]
    public function showSpecificRealisation(Realisation $realisation, RealisationRepository $realisationRepository): Response {

        $category = $realisation->getCategorie();

        $realisationsSameCategory = $realisationRepository->findThreeRandomByCategory($category);

        $categories = $this->categoryRepository->findAll();

        return $this->render('app/Realisations/showSpecificRealisation.html.twig', [
            'realisationsSameCategory' => $realisationsSameCategory,
            'categories' => $categories,
            'category' => $category,
            'realisation' => $realisation,
        ]);
    }
}