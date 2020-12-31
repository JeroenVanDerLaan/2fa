<?php declare(strict_types = 1);

namespace App\Command;

use App\Secret\SecretGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateSecretCommand extends Command
{
    private const NAME = '2fa:secret';

    private SecretGenerator $secretGenerator;

    public function __construct(
        SecretGenerator $secretGenerator
    ) {
        parent::__construct(static::NAME);
        $this->secretGenerator = $secretGenerator;
    }

    protected function configure(): void
    {
        $this->setDescription('Generates a new 2FA secret');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $secret = $this->secretGenerator->generate();
        $output->writeln($secret);
        return Command::SUCCESS;
    }
}