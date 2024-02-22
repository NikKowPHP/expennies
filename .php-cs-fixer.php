<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixerCustomFixers\Fixers;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer;
use PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer;
use PhpCsFixerCustomFixers\Fixer\DataProviderReturnTypeFixer;
use PhpCsFixerCustomFixers\Fixer\DeclareAfterOpeningTagFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoSuperfluousParamFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use PhpCsFixerCustomFixers\Fixer\NoTrailingCommaInSinglelineFixer;
use PhpCsFixerCustomFixers\Fixer\NoLeadingSlashInGlobalNamespaceFixer;
use PhpCsFixerCustomFixers\Fixer\MultilineCommentOpeningClosingAloneFixer;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->name('*.php');

$config = new Config();
$config->registerCustomFixers(new Fixers());

$config->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'list_syntax' => [
        'syntax' => 'short',
    ],
    'ordered_class_elements' => true,
    NoLeadingSlashInGlobalNamespaceFixer::name() => true,
    PhpdocNoSuperfluousParamFixer::name() => true,
    ConstructorEmptyBracesFixer::name() => true,
    DeclareAfterOpeningTagFixer::name() => true,
    MultilineCommentOpeningClosingAloneFixer::name() => true,
    MultilinePromotedPropertiesFixer::name() => true,
    NoTrailingCommaInSinglelineFixer::name() => true,
    
    
]);

$config->getRiskyAllowed(true);


// Set the finder
$config->setFinder($finder);

return $config;