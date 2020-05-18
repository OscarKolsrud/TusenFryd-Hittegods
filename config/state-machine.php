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
                'name' => 'lost',
                'metadata' => ['title' => 'Registrert tapt', 'slug' => 'registrert-tapt', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3', 'resolution' => false],
            ],
            [
                'name' => 'found',
                'metadata' => ['title' => 'Registrert mistet', 'slug' => 'registrert-mistet', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3',  'resolution' => false],
            ],
            [
                'name' => 'evicted',
                'metadata' => ['title' => 'Kastet', 'slug' => 'kastet', 'button_color' => 'bg-danger', 'colorcode' => '#FF0000',  'resolution' => true],
            ],
            [
                'name' => 'police',
                'metadata' => ['title' => 'Sendt til politi', 'slug' => 'police', 'button_color' => 'bg-info', 'colorcode' => '#48D1CC',  'resolution' => true],
            ],
            [
                'name' => 'wait_for_police',
                'metadata' => ['title' => 'Venter på sending til politi', 'slug' => 'wait_police', 'button_color' => 'bg-danger', 'colorcode' => '#FF0000',  'resolution' => false],
            ],
            [
                'name' => 'canceled',
                'metadata' => ['title' => 'Avsluttet', 'slug' => 'canceled', 'button_color' => 'bg-danger', 'colorcode' => '#FF0000',  'resolution' => true],
            ],
            [
                'name' => 'wait_for_delivery',
                'metadata' => ['title' => 'Venter på å bli utlevert', 'slug' => 'wait-delivery', 'button_color' => 'bg-success', 'colorcode' => '#00FF00',  'resolution' => false],
            ],
            [
                'name' => 'wait_for_send',
                'metadata' => ['title' => 'Venter på sending', 'slug' => 'wait-send', 'button_color' => 'bg-primary', 'colorcode' => '#1E90FF',  'resolution' => false],
            ],
            [
                'name' => 'sent',
                'metadata' => ['title' => 'Sendt', 'slug' => 'sent', 'button_color' => 'bg-success', 'colorcode' => '#00FF00',  'resolution' => true],
            ],
            [
                'name' => 'wait_for_pickup',
                'metadata' => ['title' => 'Venter på henting', 'slug' => 'wait-pickup', 'button_color' => 'bg-primary', 'colorcode' => '#1E90FF',  'resolution' => false],
            ],
            [
                'name' => 'picked_up',
                'metadata' => ['title' => 'Hentet', 'slug' => 'picked-up', 'button_color' => 'bg-success', 'colorcode' => '#00FF00',  'resolution' => true],
            ],
        ],

        // list of all possible transitions
        'transitions' => [
            'cancel' => [
                'from' => ['wait_for_delivery', 'found'],
                'to' => 'canceled',
                'metadata' => ['title' => 'Avslutt', 'button_color' => 'bg-danger', 'colorcode' => '#FF0000']
            ],
            'evicted' => [
                'from' => ['wait_for_delivery', 'found', 'wait_for_send', 'wait_for_pickup'],
                'to' => 'canceled',
                'metadata' => ['title' => 'Kast', 'button_color' => 'bg-danger', 'colorcode' => '#FF0000']
            ],
            'wait_for_police' => [
                'from' => ['wait_for_delivery', 'found', 'wait_for_send', 'wait_for_pickup'],
                'to' => 'wait_for_police',
                'metadata' => ['title' => 'Venter: Politi', 'button_color' => 'bg-primary', 'colorcode' => '#1E90FF']
            ],
            'police' => [
                'from' => ['wait_for_police'],
                'to' => 'police',
                'metadata' => ['title' => 'Send til Politi', 'button_color' => 'bg-success', 'colorcode' => '#008000']
            ],
            'wait_for_delivery' => [
                'from' => ['lost', 'found'],
                'to' => 'wait_for_delivery',
                'metadata' => ['title' => 'Venter på avtale med gjest', 'button_color' => 'bg-success', 'colorcode' => '#008000']
            ],
            'wait_for_send' => [
                'from' => ['wait_for_delivery'],
                'to' => 'wait_for_send',
                'metadata' => ['title' => 'Venter på sending', 'button_color' => 'bg-primary', 'colorcode' => '#1E90FF']
            ],
            'wait_for_pickup' => [
                'from' => ['wait_for_delivery'],
                'to' => 'wait_for_pickup',
                'metadata' => ['title' => 'Venter på henting', 'button_color' => 'bg-primary', 'colorcode' => '#1E90FF']
            ],
            'sent' => [
                'from' => ['wait_for_send'],
                'to' => 'sent',
                'metadata' => ['title' => 'Sendt', 'button_color' => 'bg-success', 'colorcode' => '#008000']
            ],
            'picked_up' => [
                'from' => ['wait_for_pickup'],
                'to' => 'picked_up',
                'metadata' => ['title' => 'Hentet', 'button_color' => 'bg-success', 'colorcode' => '#008000']
            ],
            //These transitions here should not be normally called but are useful for getting a transition to pass when "regretting" a change thus not needing a direct db query
            'regret_to_lost' => [
                'from' => ['canceled', 'wait_for_delivery'],
                'to' => 'lost',
                'metadata' => ['title' => 'Angre', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3']
            ],
            'regret_to_found' => [
                'from' => ['evicted', 'wait_for_delivery', 'wait_for_police'],
                'to' => 'found',
                'metadata' => ['title' => 'Angre', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3']
            ],
            'regret_to_wait_for_police' => [
                'from' => ['police'],
                'to' => 'wait_for_police',
                'metadata' => ['title' => 'Angre', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3']
            ],
            'regret_to_wait_for_delivery' => [
                'from' => ['wait_for_send', 'wait_for_pickup', 'wait_for_police', 'evicted'],
                'to' => 'wait_for_delivery',
                'metadata' => ['title' => 'Angre', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3']
            ],
            'regret_to_wait_for_send' => [
                'from' => ['sent'],
                'to' => 'wait_for_send',
                'metadata' => ['title' => 'Angre', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3']
            ],
            'regret_to_wait_for_pickup' => [
                'from' => ['picked_up'],
                'to' => 'wait_for_pickup',
                'metadata' => ['title' => 'Angre', 'button_color' => 'bg-light', 'colorcode' => '#D3D3D3']
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
