<?php

namespace App\Controller\Frontend\Avis;

use App\Entity\Avis;
use App\Entity\User;
use App\Form\Frontend\UserAvisFormType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvisController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private AvisRepository $avisRepository,
    ) {}

    #[Route('/avis/create', name: 'app_avis_create')]
    public function create(Request $request, AvisRepository $avisRepository): Response
    {   

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if ($user) {
            
        $avis = new Avis();
        $avis->setFirstname($user->getFirstname());
        $avis->setLastname($user->getLastname());
        $avis->setUser($this->getUser());

        $form = $this->createForm(UserAvisFormType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $this->addFlash('danger', 'Vous devez être connecté pour poster un commentaire.');
                
                return $this->redirectToRoute('app_app');
            }

            $existingAvis = $avisRepository->findOneByUser($user);

            if ($existingAvis) {

                $this->addFlash('danger', 'Vous avez déjà soumis un avis. Si vous le souhaitez, vous pouvez modifier votre avis actuel.');

                return $this->redirectToRoute('app_app');
            }

            $this->em->persist($avis);
            $this->em->flush();

            $this->addFlash('success', 'Votre commentaire a été posté !');

            return $this->redirectToRoute('app_app');
        } 
        
        return $this->render('Frontend/Avis/index.html.twig', [
            'form' => $form,
        ]);

        }
        
        $this->addFlash('danger', 'Vous devez être connecté pour ajouter un avis.');
        
        return $this->redirectToRoute('app_login');
    }

    #[Route('/app/profile/avis', name: 'app_profile_avis_index')]
    public function index(Request $request): Response {
        
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page.');

            return $this->redirectToRoute('app_app');
        }

        $avis = $user->getAvis();

        return $this->render('Frontend/Profile/Avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }
}