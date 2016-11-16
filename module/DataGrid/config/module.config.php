<?php
namespace DataGrid;
use Zend\Router\Http\Segment;

return [

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'datagrid' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/datagrid[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controllers\DataGridController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'datagrid' => __DIR__ . '/../view',
        ],
    ],
];