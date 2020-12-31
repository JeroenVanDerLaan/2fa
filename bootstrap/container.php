<?php declare(strict_types = 1);

use App\Command\GenerateQrCodeCommand;
use App\Command\GenerateSecretCommand;
use App\Command\ValidateKeyCommand;
use App\Qr\EndroidQrCodeGenerator;
use App\Qr\QrCodeGenerator;
use App\Secret\GoogleSecretGenerator;
use App\Secret\GoogleSecretValidator;
use App\Secret\SecretGenerator;
use App\Secret\SecretValidator;
use DI\Container;
use DI\ContainerBuilder;
use PragmaRX\Google2FA\Google2FA;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();

$builder->useAutowiring(false);
$builder->useAnnotations(false);

$builder->addDefinitions([
    '2fa.secret_length' => 16,
    '2fa.secret_prefix' => '',
    '2fa.validation_window' => 8,
    '2fa.company_name' => 'My Company',
    '2fa.company_email' => 'my@company.com',
    '2fa.qr_code_format' => 'png',
    Google2FA::class => static function () {
        return new Google2FA();
    },
    SecretGenerator::class => static function (Container $container) {
        $google2fa = $container->get(Google2FA::class);
        $length = $container->get('2fa.secret_length');
        $prefix = $container->get('2fa.secret_prefix');
        return new GoogleSecretGenerator(
            $google2fa,
            $length,
            $prefix
        );
    },
    SecretValidator::class => static function (Container $container) {
        $google2fa = $container->get(Google2FA::class);
        $validationWindow = $container->get('2fa.validation_window');
        return new GoogleSecretValidator(
            $google2fa,
            $validationWindow
        );
    },
    QrCodeGenerator::class => static function (Container $container) {
        $google2fa = $container->get(Google2FA::class);
        $companyName = $container->get('2fa.company_name');
        $companyEmail = $container->get('2fa.company_email');
        $qrCodeFormat = $container->get('2fa.qr_code_format');
        return new EndroidQrCodeGenerator(
            $google2fa,
            $companyName,
            $companyEmail,
            $qrCodeFormat
        );
    },
    GenerateSecretCommand::class => static function (Container $container) {
        $secretGenerator = $container->get(SecretGenerator::class);
        return new GenerateSecretCommand(
            $secretGenerator
        );
    },
    GenerateQrCodeCommand::class => static function (Container $container) {
        $qrCodeGenerator = $container->get(QrCodeGenerator::class);
        return new GenerateQrCodeCommand(
            $qrCodeGenerator,
        );
    },
    ValidateKeyCommand::class => static function (Container $container) {
        $secretValidator = $container->get(SecretValidator::class);
        return new ValidateKeyCommand(
            $secretValidator,
        );
    },
]);

return $builder->build();