<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class InvestigationIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    /**
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'analyzer' => [
                'edge_ngram_analyzer' => [
                    'type' => 'custom',
                    'tokenizer' => 'whitespace',
                    'char_filter' => [],
                    'filter' => [
                        'lowercase',
                        'edge_ngram'
                    ]
                ]
            ],
            'filter' => [
                'edge_ngram' => [
                    'type' => 'edge_ngram',
                    'min_gram' => 1,
                    'max_gram' => 50
                ]
            ]
        ]
    ];
}
