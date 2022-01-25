<?php

namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUsersCommand extends Command
{
    private $userSrv;
    public function __construct(UserService $userSrv)
    {
        parent::__construct();
        $this->userSrv = $userSrv;
    }

    protected static $defaultName = 'app:create-users';
    protected static $defaultDescription = 'Add bulk users into the database';

    protected function configure(): void
    {
        $this->addArgument('user_no', InputArgument::REQUIRED, 'Number of the users to add');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user_no = $input->getArgument('user_no');

        if (is_numeric($user_no)) {
            $io->note("Creating {$user_no} users...");

            $this->userSrv->createBulk($user_no);

            $io->success('Creation done...');

            return Command::SUCCESS;
        } else {
            $io->success('Argument must be a number');
            return Command::FAILURE;
        }
    }
}
