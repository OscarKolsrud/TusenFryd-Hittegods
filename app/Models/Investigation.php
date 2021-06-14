<?php

namespace App\Models;

use App\InvestigationIndexConfigurator;
use Illuminate\Database\Eloquent\Model;
Use Iben\Statable\Statable;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Investigation extends Model implements Auditable,HasMedia
{
    use Searchable;
    use Statable;
    use \OwenIt\Auditing\Auditable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['reference', 'item', 'description',
        'condition', 'lost_location', 'location_id',
        'category_id', 'status', 'lost_date',
        'owner_name', 'owner_email', 'owner_phone',
        'additional_info', 'delivered_too', 'user_id', 'initial_status'];

    protected $indexConfigurator = InvestigationIndexConfigurator::class;

    protected $searchRules = [
        //
    ];

    // Here you can specify a mapping for model fields
    /*
    protected $mapping = [
        'properties' => [
            'reference' => [
                'type' => 'keyword'
            ],
            'item' => [
                'type' => 'text'
            ],
            'description' => [
                'type' => 'text'
            ],
            'condition' => [
                'type' => 'text'
            ],
            'lost_location' => [
                'type' => 'text',
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ]
                ]
            ],
            'location' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text',
                    ]
                ]
            ],
            'category' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text',
                    ]
                ]
            ],
            'subcategory' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text',
                    ]
                ]
            ],
            'colors' => [
                'type' => 'object',
            ],
            'status' => [
                'type' => 'keyword'
            ],
            'lost_date' => [
                'type' => 'date',
                'fields' => [
                    'raw' => [
                        'type' => 'text',
                    ]
                ]
            ],
            'owner_name' => [
                'type' => 'text'
            ],
            'owner_email' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text',
                    ]
                ]
            ],
            'owner_phone' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text',
                    ]
                ]
            ],
            'additional_info' => [
                'type' => 'text'
            ],
            'delivered_too' => [
                'type' => 'text'
            ],
        ]
    ];
    */

    protected $mapping = [
        'properties' => [
            'additional_info' => [
                'type' => 'text',
                'fields' => [
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'category' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text',
                    ]
                ]
            ],
            'category_id' => [
                'type' => 'long'
            ],
            'colors' => [
                'properties' => [
                    'class' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword'
                            ]
                        ]
                    ],
                    'color' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword'
                            ],
                            'edge_ngrams' => [
                                'type' => 'text',
                                'analyzer' => 'edge_ngram_analyzer',
                                'search_analyzer' => 'standard'
                            ]
                        ]
                    ],
                    'created_at' => [
                        'type' => 'text'
                    ],
                    'id' => [
                        'type' => 'long'
                    ],
                    'pivot' => [
                        'properties' => [
                            'color_id' => [
                                'type' => 'long'
                            ],
                            'investigation_id' => [
                                'type' => 'long'
                            ]
                        ]
                    ],
                    'updated_at' => [
                        'type' => 'date'
                    ],
                    'visible' => [
                        'type' => 'long'
                    ],
                ]
            ],
            'condition' => [
                'type' => 'text',
                'fields' => [
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'created_at' => [
                'type' => 'date'
            ],
            'delivered_too' => [
                'type' => 'text'
            ],
            'description' => [
                'type' => 'text',
                'fields' => [
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'id' => [
                'type' => 'long'
            ],
            'initial_status' => [
                'type' => 'text',
                'fields' => [
                    'keyword' => [
                        'type' => 'keyword',
                        'ignore_above' => 256
                    ]
                ]
            ],
            'item' => [
                'type' => 'text',
                'fields' => [
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'location' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text'
                    ]
                ]
            ],
            'location_id' => [
                'type' => 'long'
            ],
            'lost_date' => [
                'type' => 'date',
                'fields' => [
                    'raw' => [
                        'type' => 'text'
                    ]
                ]
            ],
            'lost_location' => [
                'type' => 'text',
                'fields' => [
                    'raw' => [
                        'type' => 'keyword'
                    ],
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'owner_email' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text'
                    ],
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'owner_name' => [
                'type' => 'text',
                'fields' => [
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'owner_phone' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text'
                    ],
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'reference' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text'
                    ],
                    'edge_ngrams' => [
                        'type' => 'text',
                        'analyzer' => 'edge_ngram_analyzer',
                        'search_analyzer' => 'standard'
                    ]
                ]
            ],
            'status' => [
                'type' => 'keyword'
            ],
            'subcategory' => [
                'type' => 'keyword',
                'fields' => [
                    'raw' => [
                        'type' => 'text'
                    ]
                ]
            ],
            'updated_at' => [
                'type' => 'date'
            ],
            'user_id' => [
                'type' => 'long'
            ],
            'user' => [
                'properties' => [
                    'admin_ip_address' => [
                        'type' => 'ip',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword'
                            ]
                        ]
                    ],
                    'created_at' => [
                        'type' => 'date'
                    ],
                    'email' => [
                        'type' => 'keyword',
                        'fields' => [
                            'text' => [
                                'type' => 'text'
                            ]
                        ]
                    ],
                    'first_name' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword'
                            ]
                        ]
                    ],
                    'last_name' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword'
                            ]
                        ]
                    ],
                    'id' => [
                        'type' => 'long'
                    ],
                    'name' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword'
                            ]
                        ]
                    ],
                    'signup_confirmation_ip_address' => [
                        'type' => 'ip',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword'
                            ]
                        ]
                    ],
                    'updated_at' => [
                        'type' => 'date'
                    ]
                ]
            ]
        ]
    ];

    protected function getGraph()
    {
        return 'investigation'; // the SM config to use
    }

    //Add some more data to scout
    public function toSearchableArray()
    {
        $array = $this->toArray();

        if (isset($this->category['category_name'])) {
            $array['category'] = $this->category['category_name'];
        }

        if (isset($this->location['location_name'])) {
            $array['location'] = $this->location['location_name'];
        }

        if (isset($this->colors[0])) {
            $array['colors'] = $this->colors;
        }

        return $array;
    }

    public function generateTags(): array
    {
        return [
            $this->user->first_name,
        ];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('displayversion')
            ->width(450)
            ->height(450);
    }

    //Relationships
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function colors() {
        return $this->belongsToMany(Color::class);
    }

    public function conversations() {
        return $this->hasMany(Conversation::class)->orderBy('created_at', 'desc');
    }
}
