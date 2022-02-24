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

use App\Entity\Account;
use App\Entity\Payment;
use App\Entity\User;
use App\Form\PaymentType;

class PaymentController extends AbstractController
{
    #[Route('/request', name: 'request')]
    public function createPaymentRequest(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, UserInterface $user){
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment, ["accounts" =>  $user->getAccount()] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $form->get('pUser')->getData();
            $payerUser = $doctrine->getRepository(User::class)->findOneBy(['email' => $email]);
            
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
}
