<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'laravel/laravel';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'asm89/stack-cors' => 'v2.1.1@73e5b88775c64ccc0b84fb60836b30dc9d92ac4a',
  'brick/math' => '0.10.2@459f2781e1a08d52ee56b0b1444086e038561e3f',
  'coingate/coingate-php' => '3.0.5@c9e7f2c291cf8d5118c73028280aaa25a53c2302',
  'composer/ca-bundle' => '1.3.5@74780ccf8c19d6acb8d65c5f39cd72110e132bbd',
  'composer/package-versions-deprecated' => '1.11.99.5@b4f54f74ef3453349c24a845d22392cd31e65f1d',
  'dflydev/dot-access-data' => 'v3.0.2@f41715465d65213d644d3141a6a93081be5d3549',
  'doctrine/inflector' => '2.0.6@d9d313a36c872fd6ee06d9a6cbcf713eaa40f024',
  'doctrine/lexer' => '1.2.3@c268e882d4dbdd85e36e4ad69e02dc284f89d229',
  'dragonmantank/cron-expression' => 'v3.3.2@782ca5968ab8b954773518e9e49a6f892a34b2a8',
  'egulias/email-validator' => '2.1.25@0dbf5d78455d4d6a41d186da50adc1122ec066f4',
  'ezyang/htmlpurifier' => 'v4.16.0@523407fb06eb9e5f3d59889b3978d5bfe94299c8',
  'fideloper/proxy' => '4.4.2@a751f2bc86dd8e6cfef12dc0cbdada82f5a18750',
  'fruitcake/laravel-cors' => 'v2.2.0@783a74f5e3431d7b9805be8afb60fd0a8f743534',
  'graham-campbell/result-type' => 'v1.1.1@672eff8cf1d6fe1ef09ca0f89c4b287d6a3eb831',
  'guzzlehttp/guzzle' => '7.5.0@b50a2a1251152e43f6a37f0fa053e730a67d25ba',
  'guzzlehttp/promises' => '1.5.2@b94b2807d85443f9719887892882d0329d1e2598',
  'guzzlehttp/psr7' => '2.4.4@3cf1b6d4f0c820a2cf8bcaec39fc698f3443b5cf',
  'intervention/image' => '2.7.2@04be355f8d6734c826045d02a1079ad658322dad',
  'laminas/laminas-diactoros' => '2.24.0@6028af6c3b5ced4d063a680d2483cce67578b902',
  'laravel/framework' => 'v8.83.27@e1afe088b4ca613fb96dc57e6d8dbcb8cc2c6b49',
  'laravel/sanctum' => 'v2.15.1@31fbe6f85aee080c4dc2f9b03dc6dd5d0ee72473',
  'laravel/serializable-closure' => 'v1.3.0@f23fe9d4e95255dacee1bf3525e0810d1a1b0f37',
  'laravel/tinker' => 'v2.8.1@04a2d3bd0d650c0764f70bf49d1ee39393e4eb10',
  'laravel/ui' => 'v3.4.6@65ec5c03f7fee2c8ecae785795b829a15be48c2c',
  'lcobucci/clock' => '3.1.0@30a854ceb22bd87d83a7a4563b3f6312453945fc',
  'lcobucci/jwt' => '4.3.0@4d7de2fe0d51a96418c0d04004986e410e87f6b4',
  'league/commonmark' => '2.4.0@d44a24690f16b8c1808bf13b1bd54ae4c63ea048',
  'league/config' => 'v1.2.0@754b3604fb2984c71f4af4a9cbe7b57f346ec1f3',
  'league/flysystem' => '1.1.10@3239285c825c152bcc315fe0e87d6b55f5972ed1',
  'league/mime-type-detection' => '1.11.0@ff6248ea87a9f116e78edd6002e39e5128a0d4dd',
  'mailjet/mailjet-apiv3-php' => 'v1.5.8@747518ce0eebf64d27e9903441a255c85472a139',
  'messagebird/php-rest-api' => 'v1.20.0@f7c7ae490b0b2d9d228bac61b5d855c4623f7fad',
  'mollie/laravel-mollie' => 'v2.21.1@5fa08188d67153c407f42b2004658b38ee4fa9aa',
  'mollie/mollie-api-php' => 'v2.52.0@f2f694ce26ef37362bdffe7b230b4d89f1c74e2d',
  'monolog/monolog' => '2.9.1@f259e2b15fb95494c83f52d3caad003bbf5ffaa1',
  'nesbot/carbon' => '2.66.0@496712849902241f04902033b0441b269effe001',
  'nette/schema' => 'v1.2.3@abbdbb70e0245d5f3bf77874cea1dfb0c930d06f',
  'nette/utils' => 'v4.0.0@cacdbf5a91a657ede665c541eda28941d4b09c1e',
  'nikic/php-parser' => 'v4.15.4@6bb5176bc4af8bcb7d926f88718db9b96a2d4290',
  'opis/closure' => '3.6.3@3d81e4309d2a927abbe66df935f4bb60082805ad',
  'phpmailer/phpmailer' => 'v6.8.0@df16b615e371d81fb79e506277faea67a1be18f1',
  'phpoption/phpoption' => '1.9.1@dd3a383e599f49777d8b628dadbb90cae435b87e',
  'psr/clock' => '1.0.0@e41a24703d4560fd0acb709162f73b8adfc3aa0d',
  'psr/container' => '1.1.2@513e0666f7216c7459170d56df27dfcefe1689ea',
  'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0',
  'psr/http-client' => '1.0.1@2dfb5f6c5eff0e91e20e913f8c5452ed95b86621',
  'psr/http-factory' => '1.0.1@12ac7fcd07e5b077433f5f2bee95b3a771bf61be',
  'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363',
  'psr/log' => '2.0.0@ef29f6d262798707a9edd554e2b82517ef3a9376',
  'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b',
  'psy/psysh' => 'v0.11.13@722317c9f5627e588788e340f29b923e58f92f54',
  'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822',
  'ramsey/collection' => '2.0.0@a4b48764bfbb8f3a6a4d1aeb1a35bb5e9ecac4a5',
  'ramsey/uuid' => '4.7.3@433b2014e3979047db08a17a205f410ba3869cf2',
  'razorpay/razorpay' => '2.8.5@31027cfb689b9480d67419dbec7c203097e9d9ac',
  'rmccue/requests' => 'v2.0.5@b717f1d2f4ef7992ec0c127747ed8b7e170c2f49',
  'sendgrid/php-http-client' => '3.14.4@6d589564522be290c7d7c18e51bcd8b03aeaf0b6',
  'sendgrid/sendgrid' => '7.11.5@1d2fd3b72687fe82264853a8888b014f8f99e81f',
  'starkbank/ecdsa' => '0.0.5@484bedac47bac4012dc73df91da221f0a66845cb',
  'stripe/stripe-php' => 'v7.128.0@c704949c49b72985c76cc61063aa26fefbd2724e',
  'swiftmailer/swiftmailer' => 'v6.3.0@8a5d5072dca8f48460fce2f4131fcc495eec654c',
  'symfony/console' => 'v5.4.21@c77433ddc6cdc689caf48065d9ea22ca0853fbd9',
  'symfony/css-selector' => 'v6.2.7@aedf3cb0f5b929ec255d96bbb4909e9932c769e0',
  'symfony/deprecation-contracts' => 'v3.2.1@e2d1534420bd723d0ef5aec58a22c5fe60ce6f5e',
  'symfony/error-handler' => 'v5.4.21@56a94aa8cb5a5fbc411551d8d014a296b5456549',
  'symfony/event-dispatcher' => 'v6.2.7@404b307de426c1c488e5afad64403e5f145e82a5',
  'symfony/event-dispatcher-contracts' => 'v3.2.1@0ad3b6f1e4e2da5690fefe075cd53a238646d8dd',
  'symfony/finder' => 'v5.4.21@078e9a5e1871fcfe6a5ce421b539344c21afef19',
  'symfony/http-foundation' => 'v5.4.21@3bb6ee5582366c4176d5ce596b380117c8200bbf',
  'symfony/http-kernel' => 'v5.4.21@09c19fc7e4218fbcf73fe0330eea38d66064b775',
  'symfony/mime' => 'v5.4.21@ef57d9fb9cdd5e6b2ffc567d109865d10b6920cd',
  'symfony/polyfill-ctype' => 'v1.27.0@5bbc823adecdae860bb64756d639ecfec17b050a',
  'symfony/polyfill-iconv' => 'v1.27.0@927013f3aac555983a5059aada98e1907d842695',
  'symfony/polyfill-intl-grapheme' => 'v1.27.0@511a08c03c1960e08a883f4cffcacd219b758354',
  'symfony/polyfill-intl-idn' => 'v1.27.0@639084e360537a19f9ee352433b84ce831f3d2da',
  'symfony/polyfill-intl-normalizer' => 'v1.27.0@19bd1e4fcd5b91116f14d8533c57831ed00571b6',
  'symfony/polyfill-mbstring' => 'v1.27.0@8ad114f6b39e2c98a8b0e3bd907732c207c2b534',
  'symfony/polyfill-php72' => 'v1.27.0@869329b1e9894268a8a61dabb69153029b7a8c97',
  'symfony/polyfill-php73' => 'v1.27.0@9e8ecb5f92152187c4799efd3c96b78ccab18ff9',
  'symfony/polyfill-php80' => 'v1.27.0@7a6ff3f1959bb01aefccb463a0f2cd3d3d2fd936',
  'symfony/process' => 'v5.4.21@d4ce417ebcb0b7d090b4c178ed6d3accc518e8bd',
  'symfony/routing' => 'v5.4.21@2ea0f3049076e8ef96eab203a809d6b2332f0361',
  'symfony/service-contracts' => 'v2.5.2@4b426aac47d6427cc1a1d0f7e2ac724627f5966c',
  'symfony/string' => 'v6.2.7@67b8c1eec78296b85dc1c7d9743830160218993d',
  'symfony/translation' => 'v6.2.7@90db1c6138c90527917671cd9ffa9e8b359e3a73',
  'symfony/translation-contracts' => 'v3.2.1@dfec258b9dd17a6b24420d464c43bffe347441c8',
  'symfony/var-dumper' => 'v5.4.21@6c5ac3a1be8b849d59a1a77877ee110e1b55eb74',
  'textmagic/sdk' => 'dev-master@b81cd04df7ab6fd21f0ddd75bf987cf3f65fe9c6',
  'tijsverkoyen/css-to-inline-styles' => '2.2.6@c42125b83a4fa63b187fdf29f9c93cb7733da30c',
  'twilio/sdk' => '7.0.0@0cab797dfcb38af3c575e959b69e3e7cf1e28e7b',
  'vlucas/phpdotenv' => 'v5.5.0@1a7ea2afc49c3ee6d87061f5a233e3a035d0eae7',
  'voku/portable-ascii' => '1.6.1@87337c91b9dfacee02452244ee14ab3c43bc485a',
  'vonage/client' => '2.4.0@29f23e317d658ec1c3e55cf778992353492741d7',
  'vonage/client-core' => '2.7.1@c9bd01868339fc1c74e631d73d7333250b8eccbd',
  'vonage/nexmo-bridge' => '0.1.2@e9f63cd468b7e0edd73d0c90d0406d6b961f9eb7',
  'webmozart/assert' => '1.11.0@11cb2199493b2f8a3b53e7f19068fc6aac760991',
  'barryvdh/laravel-debugbar' => 'v3.7.0@3372ed65e6d2039d663ed19aa699956f9d346271',
  'beyondcode/laravel-query-detector' => '1.7.0@40c7e168fcf7eeb80d8e96f7922e05ab194269c8',
  'doctrine/instantiator' => '2.0.0@c6222283fa3f4ac679f8b9ced9a4e23f163e80d0',
  'facade/flare-client-php' => '1.10.0@213fa2c69e120bca4c51ba3e82ed1834ef3f41b8',
  'facade/ignition' => '2.17.7@b4f5955825bb4b74cba0f94001761c46335c33e9',
  'facade/ignition-contracts' => '1.0.2@3c921a1cdba35b68a7f0ccffc6dffc1995b18267',
  'fakerphp/faker' => 'v1.21.0@92efad6a967f0b79c499705c69b662f738cc9e4d',
  'filp/whoops' => '2.15.1@e864ac957acd66e1565f25efda61e37791a5db0b',
  'hamcrest/hamcrest-php' => 'v2.0.1@8c3d0a3f6af734494ad8f6fbbee0ba92422859f3',
  'laravel/sail' => 'v1.21.2@19d6fe167e2389b41fe1b4ee52293d1eaf8a43fc',
  'maximebf/debugbar' => 'v1.18.2@17dcf3f6ed112bb85a37cf13538fd8de49f5c274',
  'mockery/mockery' => '1.5.1@e92dcc83d5a51851baf5f5591d32cb2b16e3684e',
  'myclabs/deep-copy' => '1.11.1@7284c22080590fb39f2ffa3e9057f10a4ddd0e0c',
  'nunomaduro/collision' => 'v5.11.0@8b610eef8582ccdc05d8f2ab23305e2d37049461',
  'phar-io/manifest' => '2.0.3@97803eca37d319dfa7826cc2437fc020857acb53',
  'phar-io/version' => '3.2.1@4f7fd7836c6f332bb2933569e566a0d6c4cbed74',
  'phpunit/php-code-coverage' => '9.2.26@443bc6912c9bd5b409254a40f4b0f4ced7c80ea1',
  'phpunit/php-file-iterator' => '3.0.6@cf1c2e7c203ac650e352f4cc675a7021e7d1b3cf',
  'phpunit/php-invoker' => '3.1.1@5a10147d0aaf65b58940a0b72f71c9ac0423cc67',
  'phpunit/php-text-template' => '2.0.4@5da5f67fc95621df9ff4c4e5a84d6a8a2acf7c28',
  'phpunit/php-timer' => '5.0.3@5a63ce20ed1b5bf577850e2c4e87f4aa902afbd2',
  'phpunit/phpunit' => '9.6.6@b65d59a059d3004a040c16a82e07bbdf6cfdd115',
  'sebastian/cli-parser' => '1.0.1@442e7c7e687e42adc03470c7b668bc4b2402c0b2',
  'sebastian/code-unit' => '1.0.8@1fc9f64c0927627ef78ba436c9b17d967e68e120',
  'sebastian/code-unit-reverse-lookup' => '2.0.3@ac91f01ccec49fb77bdc6fd1e548bc70f7faa3e5',
  'sebastian/comparator' => '4.0.8@fa0f136dd2334583309d32b62544682ee972b51a',
  'sebastian/complexity' => '2.0.2@739b35e53379900cc9ac327b2147867b8b6efd88',
  'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d',
  'sebastian/environment' => '5.1.5@830c43a844f1f8d5b7a1f6d6076b784454d8b7ed',
  'sebastian/exporter' => '4.0.5@ac230ed27f0f98f597c8a2b6eb7ac563af5e5b9d',
  'sebastian/global-state' => '5.0.5@0ca8db5a5fc9c8646244e629625ac486fa286bf2',
  'sebastian/lines-of-code' => '1.0.3@c1c2e997aa3146983ed888ad08b15470a2e22ecc',
  'sebastian/object-enumerator' => '4.0.4@5c9eeac41b290a3712d88851518825ad78f45c71',
  'sebastian/object-reflector' => '2.0.4@b4f479ebdbf63ac605d183ece17d8d7fe49c15c7',
  'sebastian/recursion-context' => '4.0.5@e75bd0f07204fec2a0af9b0f3cfe97d05f92efc1',
  'sebastian/resource-operations' => '3.0.3@0f4443cb3a1d92ce809899753bc0d5d5a8dd19a8',
  'sebastian/type' => '3.2.1@75e2c2a32f5e0b3aef905b9ed0b179b953b3d7c7',
  'sebastian/version' => '3.0.2@c6c1022351a901512170118436c764e473f6de8c',
  'symfony/yaml' => 'v6.2.7@e8e6a1d59e050525f27a1f530aa9703423cb7f57',
  'theseer/tokenizer' => '1.2.1@34a41e998c2183e22995f158c581e7b5e755ab9e',
  'laravel/laravel' => 'dev-main@ce18ff7c2830a2cf50a8752268ed302335436db5',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!self::composer2ApiUsable()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (self::composer2ApiUsable()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }

    private static function composer2ApiUsable(): bool
    {
        if (!class_exists(InstalledVersions::class, false)) {
            return false;
        }

        if (method_exists(InstalledVersions::class, 'getAllRawData')) {
            $rawData = InstalledVersions::getAllRawData();
            if (count($rawData) === 1 && count($rawData[0]) === 0) {
                return false;
            }
        } else {
            $rawData = InstalledVersions::getRawData();
            if ($rawData === null || $rawData === []) {
                return false;
            }
        }

        return true;
    }
}
