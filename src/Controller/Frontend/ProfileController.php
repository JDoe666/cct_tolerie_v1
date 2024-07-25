<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/profile', name:'app_profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: '_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('Frontend/Profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}