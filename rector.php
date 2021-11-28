<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::PHP_74);
    $containerConfigurator->import(SetList::PHP_80);
    $containerConfigurator->import(SetList::PRIVATIZATION);
    $containerConfigurator->import(SetList::PSR_4);
    $containerConfigurator->import(SetList::SAFE_07);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(SetList::EARLY_RETURN);

    $containerConfigurator
        ->parameters()
        ->set(Option::PATHS, [__DIR__ . '/src/', __DIR__ . '/tests/'])
        ->set(Option::SKIP, [
            AddArrayReturnDocTypeRector::class,
            AddArrayParamDocTypeRector::class,
            RemoveUnusedPromotedPropertyRector::class,
            FinalizeClassesWithoutChildrenRector::class => [__DIR__ . '/tests/Support'],
        ]);
};
