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

            // dd($form->getData());
            $rib = $form->get('rib')->getData();

            $accounts = $doctrine->getRepository(Account::class)->findByRIB($rib);
            dd($accounts);

            $virement = $form->getData();
            $virement->setDestinataire($rib);
            // dd($virement);
            $entityManager->persist($virement);
            $entityManager->flush();


            return $this->redirectToRoute('virement');
        }

        return $this->renderForm('virement/index.html.twig', [
            'virementCreateForm' => $form
        ]);

        // return $this->render('home/index.html.twig', []);
    }
}
