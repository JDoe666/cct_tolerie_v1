<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\Backend\CategorieFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categories', name: 'admin_categories')]
class CategorieController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategorieRepository $categorieRepo,
    ) {
    }

    #[Route('', name: '_index')]
    public function index(): Response
    {
        $categories = $this->categorieRepo->findAll();

        return $this->render('Backend/Categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
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

    #[Route('/{slug}/update', name: '_update', methods: ['POST', 'GET'])]
    public function update(Request $request, Categorie $categorie): Response
    {
        if (!$categorie) {
            $this->addFlash('error', 'Catégorie introuvable');

            $this->redirectToRoute('admin_categories_index');
        }

        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Catégorie crée avec succès !');

            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('Backend/Categorie/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'_delete', methods:['POST'])]
    public function delete(Request $request, Categorie $categorie): Response {
        if (!$categorie) {
            $this->addFlash('error', 'Categorie introuvable');

            $this->redirectToRoute('admin_categorie_delete');
        } 

        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('token'))) {
            $this->em->remove($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Categorie supprimée avec succès !');

            return $this->redirectToRoute('admin_categories_index');
        }
    }
}