<?php

namespace App\Controller\Backend;

use App\Entity\Filtres\DevisLogsFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\Backend\Filtres\SearchDevisLogsType;
use App\Repository\DevisLogsRepository;

class LogsController extends AbstractController
{
    public function __construct(
        private DevisLogsRepository $devisLogsRepository,
    ){}

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('admin/logs', name: 'admin_logs_index')]
    public function index(Request $request): Response
    {   
        $data = new DevisLogsFilter;

        $data->setPage($request->get('page', 1));
        $data->setLimit($request->get('limit', 10));

        $form = $this->createForm(SearchDevisLogsType::class, $data);
        $form->handleRequest($request);

        $devisLogs = $this->devisLogsRepository->findDevisLogsData($data);

        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');

            return $this->redirectToRoute('app_login');
        }

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('danger', 'Vous n\'avez pas l\'autorisation pour accéder à cette page.');

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('Backend/Logs/index.html.twig', [
            'devisLogs' => $devisLogs,
            'form' => $form,
        ]);
    }
}