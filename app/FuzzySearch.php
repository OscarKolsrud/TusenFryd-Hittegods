<?php

namespace App;

use ScoutElastic\SearchRule;

class FuzzySearch extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        $query = $this->builder->query;

        return [
            'must' => [
                'multi_match' => [
                    'query' => $this->builder->query,
                    'fuzziness' => 'auto',
                    'fields' => [
                        "reference",
                        "reference.edge_ngrams",
                        "item",
                        "item.edge_ngrams",
                        "description",
                        "description.edge_ngrams",
                        "condition",
                        "condition.edge_ngrams",
                        "lost_location",
                        "lost_location.edge_ngrams",
                        "owner_name",
                        "owner_name.edge_ngrams",
                        "owner_email",
                        "owner_email.raw",
                        "owner_email.edge_ngrams",
                        "owner_phone",
                        "owner_phone.raw",
                        "owner_phone.edge_ngrams",
                        "additional_info",
                        "additional_info.edge_ngrams",
                        "colors.color",
                        "colors.color.keyword",
                        "colors.color.edge_ngrams"
                    ]
                ],
                'multi_match' => [
                    'query' => $this->builder->query,
                    'boost' => 3,
                    'fields' => [
                        "reference",
                        "item",
                        "description",
                        "condition",
                        "lost_location",
                        "owner_name",
                        "owner_email",
                        "owner_email.raw",
                        "owner_phone",
                        "owner_phone.raw",
                        "additional_info",
                        "colors.color",
                        "colors.color.keyword",
                    ]
                ]
            ]
        ];
    }
}
