<?php

// src/Command/CreateAdminCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class CreateAdminCommand extends Command
{


    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
        parent::__construct();
    }


    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-admin';

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The email of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       
        $output->writeln([
            'Add admin',
            '============',
            '',
        ]);

        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $input->getArgument('email')]);
        $user->setRoles(['ROLE_ADMIN']);
        $this->entityManager->flush();
        $output->writeln('User '.$input->getArgument('email'). ' is now admin');

        return Command::SUCCESS;
    }
}