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

    #[Route('', name: 'app_index', methods: ['GET', 'POST'])]
    public function index(Request $request, AvisRepository $avisRepository, UserInterface $user): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $avis = new Avis();
        $avis->setFirstname($user->getFirstname());
        $avis->setLastname($user->getLastname());
        $avis->setUser($user);

        $form = $this->createForm(UserAvisFormType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
                $this->addFlash('danger', 'Vous devez être connecté pour poster un commentaire.');
                return $this->redirectToRoute('app_index');
            }

            $existingAvis = $avisRepository->findOneByUser($user);

            if ($existingAvis) {

                $this->addFlash('danger', 'Vous avez déjà soumis un avis. Si vous le souhaitez, vous pouvez modifier votre avis actuel.');

                return $this->redirectToRoute('app_index');
            }

            $this->em->persist($avis);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a été posté !');

            return $this->redirectToRoute('app_index');
        }

        return $this->render('app/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('admin/avis', name: 'admin_avis_index', methods: ['GET'])]
    public function manage(Request $request): Response
    {

        $data = new AvisFilter();

        $data->setPage($request->get('page', 1));
        $data->setLimit($request->get('limit', 10));

        $form = $this->createForm(SearchAvisType::class, $data);
        $form->handleRequest($request);

        $avis = $this->avisRepository->findAvisData($data);

        return $this->render('Backend/Avis/index.html.twig', [
            'avis' => $avis,
            'form' => $form,
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