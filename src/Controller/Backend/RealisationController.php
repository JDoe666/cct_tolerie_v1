<?php

namespace App\Controller\Backend;

use App\Entity\Filtres\RealisationFilter;
use App\Entity\Realisation;
use App\Form\Backend\Filtres\SearchRealisationType;
use App\Form\Backend\RealisationFormType;
use App\Repository\RealisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/realisations', name:'admin_realisations')]
class RealisationController extends AbstractController
{   
    public function __construct(
        private EntityManagerInterface $em,
        private RealisationRepository $realisationRepository,
    ){}

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $data = new RealisationFilter;

        $data->setPage($request->get('page', 1));
        $data->setLimit($request->get('limit', 10));

        $form = $this->createForm(SearchRealisationType::class, $data);
        $form->handleRequest($request);

        $realisations = $this->realisationRepository->findRealisationData($data);


        return $this->render('Backend/Realisation/index.html.twig', [
            'realisations' => $realisations,
            'form' => $form,
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $realisation = new Realisation();

        $form = $this->createForm(RealisationFormType::class, $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($realisation);
            $this->em->flush();

            $this->addFlash('success', 'Réalisation crée avec succès !');

            return $this->redirectToRoute('admin_realisations_index');
        }

        return $this->render('Backend/Realisation/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/update', name: '_update', methods: ['POST', 'GET'])]
    public function update(Request $request, Realisation $realisation): Response
    {
        if (!$realisation) {
            $this->addFlash('danger', 'Réalisation introuvable');

            $this->redirectToRoute('admin_realisations_index');
        }

        $form = $this->createForm(RealisationFormType::class, $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($realisation);
            $this->em->flush();

            $this->addFlash('success', 'Réalisation modifiée avec succès !');

            return $this->redirectToRoute('admin_realisations_index');
        }

        return $this->render('Backend/Realisation/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'_delete', methods:['POST'])]
    public function delete(Request $request, Realisation $realisation): Response {
        if (!$realisation) {
            $this->addFlash('danger', 'Réalisation introuvable');

           return $this->redirectToRoute('admin_realisations_index');
        } 

        if ($this->isCsrfTokenValid('delete' . $realisation->getId(), $request->request->get('token'))) {
            $this->em->remove($realisation);
            $this->em->flush();

            $this->addFlash('success', 'Réalisation supprimée avec succès !');
        } else {
            $this->addFlash('danger', 'Token invalide');
        }

        return $this->redirectToRoute('admin_realisations_index');
    }
}