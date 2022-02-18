<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Account;
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

        return $this->render('account/index.html.twig', [
            'accounts' => $accounts
        ]);
    }

    #[Route('/account/create', name: 'createAccoun')]
    public function createAccount(Request $request, EntityManagerInterface $entityManager): Response
    {

        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $account->setUserId($this->user);
            $account->setRIB($num = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT));
            $account->setAmount(0);
            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('account/new.html.twig', [
            'acccountCreateForm' => $form->createView(),
        ]);
    }
}
