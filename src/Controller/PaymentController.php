<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\RequestStack;

use App\Entity\Account;
use App\Entity\Virement;
use App\Entity\Payment;
use App\Entity\User;
use App\Form\PaymentType;

class PaymentController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
       $this->user = $this->security->getUser();
    }


    #[Route('/request', name: 'request')]
    public function createPaymentRequest(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, UserInterface $user){
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment, ["accounts" =>  $user->getAccount()] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $form->get('pUser')->getData();
            $payerUser = $doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
            

            $payment->setOwner($this->user);
            $payment->setCreatedAt(new \DatetimeImmutable());
            $payment->setUpdatedAt(new \DatetimeImmutable());
            $payment->setPayerUser($payerUser);
            $payment->setStatus(0);
            
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('account');
        }


        return $this->renderForm('payment/request.html.twig', [
            'requestCreateForm' => $form,
        ]);
    }

    #[Route('/payment/accept/{id}', name: 'paymentAccept', methods: ['GET'])]
    public function chooseAccountPayment(ManagerRegistry $doctrine){
        $accounts = $doctrine->getRepository(Account::class)->findByUserId($this->user->getId());
        return $this->render('payment/valid.html.twig', [
            'accounts' => $accounts
        ]);
    }

    #[Route('/payment/accept/{id}', name: 'createPayment', methods: ['POST'])]
    public function acceptPayment(RequestStack $requestStack, ManagerRegistry $doctrine){
        $rq = $requestStack->getMainRequest();
        $entityManager = $doctrine->getManager();

        $payment = $doctrine->getRepository(Payment::class)->findOneBy(['id' => $rq->get('id')]);
        $myAccount = $doctrine->getRepository(Account::class)->findOneBy(['id' => $rq->get('accountId')]);
        $transaction = new Virement();

        $transaction->setMontant($payment->getAmount());
        $transaction->setOrigine($myAccount);
        $transaction->setDestinataire($payment->getMyAccount());
        $transaction->setDate(new \DatetimeImmutable());
        $payment->setStatus(1);
        
        $entityManager->persist($transaction);
        $entityManager->flush();

        return $this->redirectToRoute('account');
    }

    #[Route('/payment/refuse/{id}', name: 'paymentRefuse')]
    public function refusePayment(RequestStack $requestStack, ManagerRegistry $doctrine){
        $rq = $requestStack->getMainRequest();
        $entityManager = $doctrine->getManager();

        $payment = $doctrine->getRepository(Payment::class)->findOneBy(['id' => $rq->get('id')]);
        $payment->setStatus(2);
        $entityManager->flush();

        return $this->redirectToRoute('account');
    }

}
