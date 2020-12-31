<?php declare(strict_types = 1);

namespace App\Command;

use App\Qr\QrCodeGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function file_put_contents;
use function is_string;

class GenerateQrCodeCommand extends Command
{
    private const NAME = '2fa:qr';

    private QrCodeGenerator $qrCodeGenerator;

    public function __construct(
        QrCodeGenerator $qrCodeGenerator
    ) {
        parent::__construct(static::NAME);
        $this->qrCodeGenerator = $qrCodeGenerator;
    }

    protected function configure(): void
    {
        $this->setDescription('Generates a new 2FA QR code');
        $this->addArgument(
            'secret',
            InputArgument::REQUIRED,
            'The 2FA secret'
        );
        $this->addOption(
            'file',
            'f',
            InputOption::VALUE_OPTIONAL,
            'The QR code destination file'
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
        $code = $this->qrCodeGenerator->generate($secret);
        $file = $input->getOption('file');
        if (false === is_string($file)) {
            $output->writeln($code);
            return Command::SUCCESS;
        }
        if (true === empty($file)) {
            $output->writeln('Given QR code destination file is empty');
            return Command::FAILURE;
        }
        $result = @file_put_contents($file, $code);
        if (false === $result) {
            $output->writeln('Failed to write QR code to destination file');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}