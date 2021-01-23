<?php

return [
    'models' => [
        'translation' => LaTevaWeb\Translatable\Models\Translation::class,
    ],

    'table_names' => [
        'translatables' => 'latevaweb_translatables',
        'translations' => 'latevaweb_translations',
    ],

    'column_names' => [
        'model_morph_key' => 'model_id',
    ],
];
