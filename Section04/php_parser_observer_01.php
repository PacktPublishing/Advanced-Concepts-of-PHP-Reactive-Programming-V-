<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';
require_once __DIR__ . '/PHPParserOperator.php';
require_once __DIR__ . '/AssignmentInConditionNodeVisitor.php';

use Rx\Observable;

Observable::fromArray(['_test_source_code.php'])
    ->lift(function() {
        $classes = [AssignmentInConditionNodeVisitor::class];
        return new PHPParserOperator($classes);
    })
    ->subscribe(function($results) {
        print_r($results);
    });
