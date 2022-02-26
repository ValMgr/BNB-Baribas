<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;


use App\Entity\Account;
use App\Entity\Payment;
use App\Form\AccountType;


class AccountController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
       $this->user = $this->security->getUser();
    }

    #[Route('/account', name: 'account')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $accounts = $doctrine->getRepository(Account::class)->findByUserId($this->user->getId());
        $payments = $doctrine->getRepository(Payment::class)->findBy(['payerUser' => $this->user->getId(), 'status' => 0]);
        $requests = $doctrine->getRepository(Payment::class)->findBy(['owner' => $this->user->getId()]);
        

        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
            'payments' => $payments,
            'requests' => array_reverse($requests)
        ]);
    }

    #[Route('/account/create', name: 'createAccount')]
    public function createAccount(Request $request, EntityManagerInterface $entityManager): Response
    {

        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $account->setUserId($this->user);
            $account->setRIB('FRA'.str_pad(mt_rand(1,9999999999999999),16,'0',STR_PAD_LEFT));
            $account->setAmount(0);
            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('account/new.html.twig', [
            'acccountCreateForm' => $form->createView(),
        ]);
    }

    #[Route('/account/loan', name: 'loanForm', methods: ['GET'])]
    public function loanForm(ManagerRegistry $doctrine)
    {
        $accounts = $doctrine->getRepository(Account::class)->findByUserId($this->user->getId());
        return $this->render('account/loan.html.twig', [
            'accounts' => $accounts
        ]);
    }

    #[Route('/account/loan', name: 'loanCreate', methods: ['POST'])]
    public function loanCreate(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $rq = $requestStack->getMainRequest();
        $accountId = $rq->get('accountId');
        $account = $doctrine->getRepository(Account::class)->find($accountId);

        if (!$account) {
            throw $this->createNotFoundException(
                'No account found for id '.$accountId
            );
        }

        $newAmount = $account->getAmount() + $rq->get('cents') + ($rq-> get('euro') * 100);
        $account->setAmount($newAmount);

        $entityManager->flush();
        return $this->redirectToRoute('account');
    }

    #[Route('/account/delete/{id}', name: 'accountDelete', methods: ['GET'])] // Parce que j'ai pas envie de faire de l'ajax pour utliser la méthode DELETE. Désolé
    public function accountDelete(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $rq = $requestStack->getMainRequest();
        $entityManager = $doctrine->getManager();
        $accountId = $rq->attributes->get('id');
        $account = $doctrine->getRepository(Account::class)->find($accountId);

        if (!$account) {
            throw $this->createNotFoundException(
                'No account found for id '.$id
            );
        }

        $entityManager->remove($account);
        $entityManager->flush();

        return $this->redirectToRoute('account');
    }

}
