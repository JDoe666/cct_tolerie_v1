<?php

namespace App\Controller\Frontend\Devis;

use App\Entity\Devis;
use App\Entity\User;
use App\Form\Frontend\UserDevisFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function PHPUnit\Framework\throwException;

#[Route('/app/devis', name: 'app_devis')]
class DevisController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user->getRoles()) {

            $this->addFlash('danger', 'Vous devez être connecté pour demander un devis.');

            return $this->redirectToRoute('app_login');
        }

        $devis = new Devis();

        $form = $this->createForm(UserDevisFormType::class, $devis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form);
            $this->em->flush();

            $this->addFlash('success', 'Nous avons bel et bien reçu votre devis, nous le traiterons dans les plus bref délais.');

            return $this->redirectToRoute('app_profile_index');
        } 

        return $this->render('Frontend/Devis/create.html.twig', [
            'form' => $form,
        ]);
    }
}