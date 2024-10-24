<?php

namespace App\Controller\Backend;

use App\Entity\Avis;
use App\Entity\Filtres\AvisFilter;
use App\Entity\User;
use App\Form\Backend\Filtres\SearchAvisType;
use App\Form\Frontend\UserAvisFormType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AvisController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private AvisRepository $avisRepository,
    ) {
    }

    #[Route('admin/avis', name: 'admin_avis_index', methods: ['GET'])]
    public function manage(): Response
    {

        $avis = $this->avisRepository->findAll();

        return $this->render('Backend/Avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('admin/avis/{id}/delete', name: 'admin_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avis): Response
    {
        if (!$avis) {
            $this->addFlash('danger', 'Avis introuvable');

            return $this->redirectToRoute('admin_avis_index');
        }

        if ($this->isCsrfTokenValid('delete' . $avis->getId(), $request->request->get('token'))) {
            $this->em->remove($avis);
            $this->em->flush();

            $this->addFlash('success', 'Avis supprimé avec succès !');
        } else {
            $this->addFlash('danger', 'Token invalide');
        }

        return $this->redirectToRoute('admin_avis_index');
    }
}