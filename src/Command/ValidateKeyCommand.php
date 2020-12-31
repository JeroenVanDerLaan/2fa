<?php declare(strict_types = 1);

namespace App\Command;

use App\Secret\SecretValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function is_string;

class ValidateKeyCommand extends Command
{
    private const NAME = '2fa:validate';

    private SecretValidator $secretValidator;

    public function __construct(
        SecretValidator $secretValidator
    ) {
        parent::__construct(static::NAME);
        $this->secretValidator = $secretValidator;
    }

    protected function configure(): void
    {
        $this->setDescription('Validates a 2FA key against a 2FA secret');
        $this->addArgument(
            'secret',
            InputArgument::REQUIRED,
            'The 2FA secret'
        );
        $this->addArgument(
            'key',
            InputArgument::REQUIRED,
            'The 2FA key (provided by the user)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $secret = (string) $input->getArgument('secret');
        if (false === is_string($secret)) {
            $output->writeln('No 2FA secret given');
            return Command::FAILURE;
        }
        if (true === empty($secret)) {
            $output->writeln('Given 2FA secret is empty');
            return Command::FAILURE;
        }

        $key = (string) $input->getArgument('key');
        if (false === is_string($key)) {
            $output->writeln('No 2FA key given');
            return Command::FAILURE;
        }
        if (true === empty($key)) {
            $output->writeln('Given 2FA key is empty');
            return Command::FAILURE;
        }

        $isValid = $this->secretValidator->isValid($secret, $key);
        if (false === $isValid) {
            $output->writeln('The 2FA key is invalid');
            return Command::FAILURE;
        }

        $output->writeln('The 2FA is valid');
        return Command::SUCCESS;
    }
}