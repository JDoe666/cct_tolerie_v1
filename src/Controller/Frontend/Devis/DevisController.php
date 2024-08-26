<?php

namespace App\Controller\Frontend\Devis;

use App\Entity\Devis;
use App\Entity\Filtres\DevisFilter;
use App\Entity\User;
use App\Form\Backend\Filtres\SearchDevisType;
use App\Form\Frontend\UserDevisFormType;
use App\Repository\DevisRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DevisController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(
        private EntityManagerInterface $em,
        private DevisRepository $devisRepository,
        EmailVerifier $emailVerifier,
    ) {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/app/devis/create', name: 'app_devis_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        /**
         *  @var User $user
         */
        $user = $this->getUser();
        if (!$user->isVerified()) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette page car vous n\'avez pas vérifié votre email. Nous vous avons renvoyé un lien de vérification.');

            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('anthony.koenig.testing@outlook.com', 'No-reply-cct-tolerie'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_app');
        }

        if (!$user) {

            $this->addFlash('danger', 'Vous devez être connecté pour demander un devis.');

            return $this->redirectToRoute('app_login');
        }

        if ($user->getUserAddresses()->isEmpty()) {

            $this->addFlash('danger', 'Vous devez renseigner au moins une adresse avant de demander un devis.');

            return $this->redirectToRoute('app_profile_address_index');
        }

        /**
         * @var User $user
         */
        $devis = new Devis();
        $devis->setFirstname($user->getFirstname());
        $devis->setLastname($user->getLastname());
        $devis->setUser($user);

        $form = $this->createForm(UserDevisFormType::class, $devis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devis->setStatus(Devis::STATUS_WAITING);
            $this->em->persist($devis);
            $this->em->flush();

            $this->addFlash('success', 'Nous avons bel et bien reçu votre devis, nous le traiterons dans les plus bref délais.');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('Frontend/Devis/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/app/profile/devis', name: 'app_profile_devis_index', methods: ['GET', 'POST'])]
    public function show(): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connectés pour accéder à cette page.');

            return $this->redirectToRoute('app_login');
        }
        
        $devis = $user->getDevis();

        if (!$devis) {
            $this->addFlash('danger', 'Vous n\'avez pas effectué de demande de devis.');

            return $this->redirectToRoute('app_devis_create');
        }
        
        return $this->render('Frontend/Devis/index.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('app/devis/{id}/cancel', name:'app_devis_cancel', methods:['POST'])]
    public function delete(Request $request, Devis $devis): Response {
        if (!$devis) {
            $this->addFlash('danger', 'Devis introuvable');

           return $this->redirectToRoute('app_devis_index');
        } 

        if ($devis->getStatus() !== Devis::STATUS_WAITING) {
            $this->addFlash('danger', 'Le devis ne peut pas être annulé');

            return $this->redirectToRoute('app_devis_index');
        }

        if ($this->isCsrfTokenValid('delete' . $devis->getId(), $request->request->get('token'))) {
            $devis->setStatus(Devis::STATUS_CANCELED);
            $devis->setCanceledBy('user');
            $this->em->persist($devis);
            $this->em->flush();

            $this->addFlash('success', 'Devis annulé avec succès !');
        } else {
            $this->addFlash('danger', 'Token invalide');
        }

        return $this->redirectToRoute('app_profile_devis_index');
    }

    #[Route('admin/devis', name: 'admin_devis_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $data = new DevisFilter();

        /**
         * @var User $user
         */

        $data->setPage($request->get('page', 1));
        $data->setLimit($request->get('limit', 10));

        $form = $this->createForm(SearchDevisType::class, $data);
        $form->handleRequest($request);

        $devis = $this->devisRepository->findDevisData($data);

        return $this->render('Backend/Devis/index.html.twig', [
            'devis' => $devis,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('admin/devis/{id}/update', name: 'admin_devis_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Devis $devis, MailerInterface $mailer): Response
    {

        $user = $this->getUser();
        $devis->setUser($user);
        $oldStatus = $devis->getStatus();

        if (!$devis) {
            $this->addFlash('danger', "Devis introuvable.");

            return $this->redirectToRoute('admin_devis_index');
        }

        $oldStatus = $devis->getStatus();

        $form = $this->createForm(UserDevisFormType::class, $devis, [
            'isEdit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newStatus = $devis->getStatus();

            if ($newStatus === Devis::STATUS_CANCELED) {
                $devis->setCanceledBy('admin');
            }

            $this->em->persist($devis);
            $this->em->flush();

            if ($oldStatus !== $newStatus) {
                $email = (new TemplatedEmail())
                    ->from(new Address('anthony.koenig.testing@outlook.com', 'No-reply-cct-tolerie'))
                    ->to($devis->getUser()->getEmail())
                    ->subject('Mise à jour de votre devis');
                if ($newStatus === Devis::STATUS_ACCEPTED) {
                    $email->htmlTemplate('devisEmail/devis_accepted.html.twig');
                } elseif ($newStatus === Devis::STATUS_FINISHED) {
                    $email->htmlTemplate('devisEmail/devis_finished.html.twig');
                } elseif ($newStatus === Devis::STATUS_CANCELED) {
                    $email->htmlTemplate('devisEmail/devis_canceled.html.twig');
                } elseif ($newStatus === Devis::STATUS_DENIED) {
                    $email->htmlTemplate('devisEmail/devis_denied.html.twig');
                }
                $mailer->send($email);
            }

            $this->addFlash('success', 'Status modifié avec succès, un email à été envoyé au client.');

            return $this->redirectToRoute('admin_devis_index');
        }

        return $this->render('Backend/Devis/update.html.twig', [
            'form' => $form,
        ]);
    }
}