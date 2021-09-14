<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::CLEAN_CODE);
    $containerConfigurator->import(SetList::ARRAY);
    $containerConfigurator->import(SetList::COMMENTS);
    $containerConfigurator->import(SetList::DOCBLOCK);
    $containerConfigurator->import(SetList::NAMESPACES);
    $containerConfigurator->import(SetList::STRICT);
    $containerConfigurator->import(SetList::DOCTRINE_ANNOTATIONS);
    $containerConfigurator->import(SetList::SYMFONY);
    $containerConfigurator->import(SetList::SYMFONY_RISKY);
    $containerConfigurator->import(SetList::PHPUNIT);

    $containerConfigurator
        ->parameters()
        ->set(Option::PATHS, [__FILE__, __DIR__ . '/example/', __DIR__ . '/src/', __DIR__ . '/tests/'])
        ->set(Option::SKIP, [__DIR__ . '/tests/Support/Fixture1.php']);

    $containerConfigurator
        ->services()
        ->set(ConcatSpaceFixer::class)->call('configure', [[
            'spacing' => 'one',
        ]])
        ->set(CastSpacesFixer::class)->call('configure', [[
            'space' => 'none',
        ]])
        ->set(LineLengthFixer::class)->call('configure', [[
            'max_line_length' => 180,
            'break_long_lines' => true,
            'inline_short_lines' => true,
        ]])
        ->set(DeclareStrictTypesFixer::class)
        ->set(FinalClassFixer::class);
};
