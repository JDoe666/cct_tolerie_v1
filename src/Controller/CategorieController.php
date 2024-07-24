<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\Backend\CategorieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categories', name:'admin_categories')]
class CategorieController extends AbstractController
{   
    public function __construct(
        private EntityManagerInterface $em,
    ){}

    #[Route('', name: '_index')]
    public function index(): Response
    {
        return $this->render('Backend/Categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }
    
    #[Route('/create', name:'_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Categorie crée avec succès !');

            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('Backend/Categorie/create.html.twig', [
            'form' => $form,
        ]);
    }
}