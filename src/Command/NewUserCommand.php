<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:new-user', description: 'Creates a new user')]
class NewUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user.')
            ->addOption('email', null, InputOption::VALUE_REQUIRED)
            ->addOption('password', null, InputOption::VALUE_REQUIRED)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $email = $input->getOption('email');
            $password = $input->getOption('password');

            $user = new User();
            $user->email = $email;
            $user->password = $this->hasher->hashPassword($user, $password);

            $violations = $this->validator->validate($user);
            if ($violations->count() > 0) {
                foreach ($violations as $violation) {
                    $output->writeln(sprintf('<error>%s</error>', $violation->getMessage()));
                }
                return Command::FAILURE;
            }

            $output->writeln(sprintf('<info>Utilisateur "%s" créé en base de données.</info>', $email));
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
