<?php

return [
    'investigation' => [
        // class of your domain object
        'class' => \App\Models\Investigation::class,

        // name of the graph (default is "default")
        'graph' => 'investigation',

        // property of your object holding the actual state (default is "state")
        'property_path' => 'status',

        'metadata' => [
            'title' => 'Different transistions for the cases/investigations',
        ],

        // list of all possible states
        'states' => [
            [
                'name' => 'new',
                'metadata' => ['title' => 'Saken har ingen status', 'slug' => 'no-status', 'resolution' => false],
            ],
            [
                'name' => 'lost',
                'metadata' => ['title' => 'Registrert tapt', 'slug' => 'registrert-tapt', 'resolution' => false],
            ],
            [
                'name' => 'found',
                'metadata' => ['title' => 'Registrert funnet', 'slug' => 'registrert-funnet', 'resolution' => false],
            ],
            [
                'name' => 'wait_for_arrangement',
                'metadata' => ['title' => 'Venter på avtale', 'slug' => 'waiting-arrangment', 'resolution' => false],
            ],
            [
                'name' => 'wait_for_send',
                'metadata' => ['title' => 'Venter på utsendelse', 'slug' => 'venter-utsendelse', 'resolution' => false],
            ],
            [
                'name' => 'sent',
                'metadata' => ['title' => 'Sendt', 'slug' => 'sendt', 'resolution' => true],
            ],
            [
                'name' => 'wait_for_delivery',
                'metadata' => ['title' => 'Venter på utlevering', 'slug' => 'venter-utlevering', 'resolution' => false],
            ],
            [
                'name' => 'delivered',
                'metadata' => ['title' => 'Utlevert', 'slug' => 'utlevert', 'resolution' => true],
            ],
            [
                'name' => 'evicted',
                'metadata' => ['title' => 'Kastet', 'slug' => 'kastet', 'resolution' => true],
            ],
            [
                'name' => 'police',
                'metadata' => ['title' => 'Sendt til politi', 'slug' => 'politi', 'resolution' => true],
            ],
            [
                'name' => 'canceled',
                'metadata' => ['title' => 'Avsluttet', 'slug' => 'avsluttet', 'resolution' => true],
            ],
        ],

        // list of all possible transitions
        'transitions' => [
            'lost' => [
                'from' => ['new'],
                'to' => 'lost',
                'metadata' => ['title' => 'Ny etterlysning']
            ],
            'found' => [
                'from' => ['new'],
                'to' => 'found',
                'metadata' => ['title' => 'Ny gjenstand']
            ],
            'cancel_lost' => [
                'from' => ['lost'],
                'to' => 'canceled',
                'metadata' => ['title' => 'Avbryt etterlysning']
            ],
            'regret_cancel_lost' => [
                'from' => ['canceled'],
                'to' => 'lost',
                'metadata' => ['title' => 'Angre "Avbryt etterlysning"']
            ],
            'evict_found' => [
                'from' => ['found'],
                'to' => 'evicted',
                'metadata' => ['title' => 'Kastet']
            ],
            'regret_evict_found' => [
                'from' => ['evicted'],
                'to' => 'found',
                'metadata' => ['title' => 'Angre "Kastet"']
            ],
            'police' => [
                'from' => ['found'],
                'to' => 'police',
                'metadata' => ['title' => 'Send til politi']
            ],
            'regret_police' => [
                'from' => ['police'],
                'to' => 'found',
                'metadata' => ['title' => 'Angre "Send til politi"']
            ],
            'arrangement' => [
                'from' => ['found', 'lost'],
                'to' => 'wait_for_arrangement',
                'metadata' => ['title' => 'Gjenstand og eier funnet']
            ],
            'regret_to_found' => [
                'from' => ['wait_for_arrangement'],
                'to' => 'found',
                'metadata' => ['title' => 'Angre "Gjenstand og eier funnet" til "funnet" status']
            ],
            'regret_to_lost' => [
                'from' => ['wait_for_arrangement'],
                'to' => 'lost',
                'metadata' => ['title' => 'Angre "Gjenstand og eier funnet" til "mistet" status']
            ],
            'wait_for_send' => [
                'from' => ['wait_for_arrangement'],
                'to' => 'wait_for_send',
                'metadata' => ['title' => 'Legg gjenstand til sending']
            ],
            'regret_wait_for_send' => [
                'from' => ['wait_for_send'],
                'to' => 'wait_for_arrangement',
                'metadata' => ['title' => 'Angre "Legg gjenstand til sending"']
            ],
            'sent' => [
                'from' => ['wait_for_send'],
                'to' => 'sent',
                'metadata' => ['title' => 'Sendt']
            ],
            'regret_sent' => [
                'from' => ['sent'],
                'to' => 'wait_for_send',
                'metadata' => ['title' => 'Angre "Sendt"']
            ],
            'wait_for_delivery' => [
                'from' => ['wait_for_arrangement'],
                'to' => 'wait_for_delivery',
                'metadata' => ['title' => 'Venter på utlevering']
            ],
            'delivered' => [
                'from' => ['wait_for_delivery'],
                'to' => 'delivered',
                'metadata' => ['title' => 'Utlevert']
            ],
            'evict' => [
                'from' => ['wait_for_arrangement'],
                'to' => 'evicted',
                'metadata' => ['title' => 'Kastet']
            ],
            'regret_final' => [
                'from' => ['evicted', 'delivered', 'wait_for_delivery'],
                'to' => 'wait_for_arrangment',
                'metadata' => ['title' => 'Angre "Kastet"']
            ],
        ],

        // list of all callbacks
        'callbacks' => [
            // will be called when testing a transition
            'guard' => [],

            // will be called before applying a transition
            'before' => [],

            // will be called after applying a transition
            'after' => [
                'history' => [
                    'do' => 'StateHistoryManager@storeHistory'
                ]
            ],
        ],
    ],
];
