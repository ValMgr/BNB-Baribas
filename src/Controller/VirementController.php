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

use App\Entity\Virement;
use App\Entity\Account;
use App\Form\VirementType;


class VirementController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
       $this->user = $this->security->getUser();
    }

    #[Route('/virement', name: 'virement')]
    public function index(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, UserInterface $user): Response
    {
        $virement = new Virement();

        $form = $this->createForm(VirementType::class, $virement, ["origines" =>  $user->getAccount()] );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $virement = $form->getData();
            $isUserAccount = $doctrine->getRepository(Account::class)->findOneBy(['userId' => $this->user->getId(), 'id' => $virement->getOrigine()->getId()]);
         
            if (!$isUserAccount) {
                throw new Exception('It\'s not your account petit filou');
            }

            $rib = $form->get('rib')->getData();
            $targetAccounts = $doctrine->getRepository(Account::class)->findOneBy(['RIB' => $rib]);

            if (!$targetAccounts) {
               throw $this->createNotFoundException(
                   'No account found with RIB: '.$rib
               ); 
            }

            $originAmount = $virement->getOrigine()->getAmount() - ($virement->getMontant() * 100);
            $virement->getOrigine()->setAmount($originAmount);
            $virement->setDestinataire($targetAccounts);
            $virement->setDate(new \DatetimeImmutable());
            $virement->setStatus(1);


            
            $targetAmount = $targetAccounts->getAmount() + ($virement->getMontant() * 100);
            $targetAccounts->setAmount($targetAmount);

            $entityManager->persist($virement);
            $entityManager->flush();
            
                
            return $this->redirectToRoute('account');
        }
            
        return $this->renderForm('virement/index.html.twig', [
            'virementCreateForm' => $form
        ]);
    }

   
}
