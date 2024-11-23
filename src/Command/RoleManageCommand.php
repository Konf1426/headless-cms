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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[AsCommand(name: 'app:role-manage', description: 'Manage user roles')]
class RoleManageCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
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
            ->setDescription('Manage user roles.')
            ->addOption('email', null, InputOption::VALUE_REQUIRED)
            ->addOption('role', null, InputOption::VALUE_REQUIRED)
            ->addArgument('action', InputOption::VALUE_REQUIRED)
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
            $role = $input->getOption('role');
            $action = $input->getArgument('action');

            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$user) {
                $output->writeln(sprintf('<error>L\'utilisateur avec l\'adresse mail "%s" n\'a pas été trouvé.</error>', $email));
                return Command::FAILURE;
            }

            if ($action === 'add') {
                if (!in_array($role, $user->roles)) {
                    $user->roles = array_merge($user->roles, [$role]);
                }
            } elseif ($action === 'remove') {
                $user->roles = array_diff($user->roles, [$role]);
                if (empty($user->roles)) {
                    $user->roles = ['ROLE_USER'];
                }
            } else {
                $output->writeln('<error>L\'action doit être "add" ou "remove".</error>');
                return Command::FAILURE;
            }

            $violations = $this->validator->validate($user);
            if ($violations->count() > 0) {
                foreach ($violations as $violation) {
                    $output->writeln(sprintf('<error>%s</error>', $violation->getMessage()));
                }
                return Command::FAILURE;
            }

            $output->writeln(sprintf(
                '<info>Rôle "%s" %s à l\'utilisateur "%s".</info>',
                $role,
                $action === 'add' ? 'ajouté' : 'retiré',
                $email
            ));
            $this->em->flush();
        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
