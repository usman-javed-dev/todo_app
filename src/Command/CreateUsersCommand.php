<?php

namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to create users in bulk
 */
class CreateUsersCommand extends Command
{
    private $userService;
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    protected static $defaultName = 'app:create-users';
    protected static $defaultDescription = 'Add bulk users into the database';

    protected function configure(): void
    {
        $this->addArgument('numOfUsers', InputArgument::OPTIONAL, 'Number of the users to add.', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numOfUsers = $input->getArgument('numOfUsers');

        if (is_numeric($numOfUsers)) {
            $io->note("Creating {$numOfUsers} users...");

            $this->userService->createBulk($numOfUsers);

            $io->success('Creation done...');

            return Command::SUCCESS;
        } else {
            $io->success('Argument must be a number');
            return Command::FAILURE;
        }
    }
}
