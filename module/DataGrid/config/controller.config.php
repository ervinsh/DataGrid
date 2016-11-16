<?php
namespace DataGrid;

return [
    'factories' => [
        Controllers\DataGridController::class => function($container) {
            return new Controllers\DataGridController(
                $container->get(Models\DataGridTable::class)
            );
        },
    ],
];