<?php

namespace App\Controller;

use App\Entity\Filtres\UserFilter;
use App\Entity\User;
use App\Form\Backend\Filtres\SearchUserType;
use App\Form\Backend\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users', name: 'admin_users', methods: ['GET', 'POST'])]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('', name: '_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $data = new UserFilter;

        $data->setPage($request->get('page', 1));
        $data->setLimit($request->get('limit', 10));

        $form = $this->createForm(SearchUserType::class, $data);
        $form->handleRequest($request);

        $users = $this->userRepository->findUserData($data);


        return $this->render('Backend/Users/index.html.twig', [
            'users' => $users,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '_update', methods: ['GET', 'POST'])]
    public function update(Request $request, ?User $user): Response|RedirectResponse
    {
        if (!$user) {
            $this->addFlash('error', 'Utilisateur introuvable');

            $this->redirectToRoute('admin_users_index');
        }

        $form = $this->createForm(UserFormType::class, $user, ['isSuperAdmin' => $this->isGranted('ROLE_SUPER_ADMIN')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // dd($request->request->get('roles'));
            // $roles = $request->request->get('roles');
            // $user->switchRoles($roles);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès !');

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('Backend/Users/update.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: '_delete', methods: ['POST'])]
    public function delete(?User $user, Request $request): RedirectResponse
    {
        if (!$user) {
            $this->addFlash('error', 'Utilisateur introuvable');

            return $this->redirectToRoute('admin_users_index');
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('token'))) {
            $this->em->remove($user);
            $this->em->flush();
        } else {
            $this->addFlash('error', 'Token invalide');
        }

        return $this->redirectToRoute('admin_users_index');
    }
}