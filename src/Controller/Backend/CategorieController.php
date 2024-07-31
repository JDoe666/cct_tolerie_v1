<?php

namespace App\Controller\Backend;

use App\Entity\Categorie;
use App\Entity\Filtres\CategorieFilter;
use App\Form\Backend\CategorieFormType;
use App\Form\Backend\Filtres\SearchCategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/categories', name: 'admin_categories')]
class CategorieController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategorieRepository $categorieRepo,
    ) {
    }

    #[Route('', name: '_index', methods:['GET'])]
    public function index(Request $request): Response
    {
        $data = new CategorieFilter();

        $data->setPage($request->get('page', 1));
        $data->setLimit($request->get('limit', 10));

        $form = $this->createForm(SearchCategorieType::class, $data);
        $form->handleRequest($request);

        $categories = $this->categorieRepo->findCategorieData($data);

        return $this->render('Backend/Categorie/index.html.twig', [
            'categories' => $categories,
            'form' => $form,
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
            $this->addFlash('danger', 'Catégorie introuvable');

            $this->redirectToRoute('admin_categories_index');
        }

        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès !');

            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('Backend/Categorie/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'_delete', methods:['POST'])]
    public function delete(Request $request, Categorie $categorie): Response {
        if (!$categorie) {
            $this->addFlash('danger', 'Categorie introuvable');

           return $this->redirectToRoute('admin_categorie_index');
        } 

        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('token'))) {
            $this->em->remove($categorie);
            $this->em->flush();

            $this->addFlash('success', 'Categorie supprimée avec succès !');
        } else {
            $this->addFlash('danger', 'Token invalide');
        }

        return $this->redirectToRoute('admin_categories_index');
    }
}