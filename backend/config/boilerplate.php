<?php


return [
    'create_army' => [
        'validation_rules' => [
            'name' => 'required|string|max:256',
            'units' => 'required|numeric|between:80,100',
            'strategy' => 'required|string|in:strongest,random,weakest',
            'game_id' => 'required|numeric'
        ]
    ]
];
