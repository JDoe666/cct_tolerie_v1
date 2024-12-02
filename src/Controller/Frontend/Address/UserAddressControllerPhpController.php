<?php

namespace App\Controller\Frontend\Address;

use App\Entity\UserAddress;
use App\Form\Frontend\UserAddressFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/profile/address', name:'app_profile_address')]
class UserAddressControllerPhpController extends AbstractController
{   
    public function __construct(
        private EntityManagerInterface $em,
    ){}

    #[Route('', name: '_index', methods:['GET', 'POST'])]
    public function index(): Response
    {   
        $user = $this->getUser();

        return $this->render('Frontend/Profile/Address/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/create', name: '_create', methods: ['GET', 'POST'])]
    public function createAddress(Request $request): Response
    {
        $userAddress = new UserAddress();

        $form = $this->createForm(UserAddressFormType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userAddress->addUser($this->getUser());
            $this->em->persist($userAddress);
            $this->em->flush();

            $this->addFlash('success', 'Nouvelle adresse renseignée !');
            
            return $this->redirectToRoute('app_profile_address_index');
        }

        return $this->render('Frontend/Profile/Address/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '_update', methods: ['POST', 'GET'])]
    public function update(Request $request, UserAddress $userAddress): Response
    {   

        if (!$userAddress) {
            $this->addFlash('danger', 'Adresse introuvable');

            $this->redirectToRoute('app_profile_address_index');
        }

        $form = $this->createForm(UserAddressFormType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($userAddress);
            $this->em->flush();

            $this->addFlash('success', 'Adresse modifiée avec succès !');

            return $this->redirectToRoute('app_profile_address_index');
        }

        return $this->render('Frontend/Profile/Address/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name:'_delete', methods:['POST'])]
    public function delete(Request $request, UserAddress $userAddress): Response {
        
        if (!$userAddress) {
            $this->addFlash('danger', 'Categorie introuvable');

            return $this->redirectToRoute('app_profile_address_index');
        } 

        if ($this->isCsrfTokenValid('delete' . $userAddress->getId(), $request->request->get('token'))) {
            $this->em->remove($userAddress);
            $this->em->flush();

            $this->addFlash('success', 'Adresse supprimée avec succès !');
        } else {
            $this->addFlash('danger', 'Token invalide');
        }

        return $this->redirectToRoute('app_profile_address_index');
        
    }
}