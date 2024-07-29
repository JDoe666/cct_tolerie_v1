<?php

namespace App\Controller\Frontend;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/profile', name: 'app_profile')]
class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        /**
         *  @var User $user
         */
        $user = $this->getUser();
        if (!$user->isVerified()) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette page car vous n\'avez pas vérifié votre email.');

            // TODO Envoyer un nouveau mail de vérification pour que l'utilisateur vérifie son email.

            return $this->redirectToRoute('app_app');
        }

        return $this->render('Frontend/Profile/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
