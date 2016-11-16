<?php
namespace DataGrid;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

return [
    'factories' => [
        Models\DataGridTable::class => function($container) {
            $tableGateway = $container->get(Models\DataGridTableGateway::class);
            return new Models\DataGridTable($tableGateway);
        },
        Models\DataGridTableGateway::class => function ($container) {
            $dbAdapter = $container->get(AdapterInterface::class);
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Models\DataGridItem());
            return new TableGateway('dishes', $dbAdapter, null, $resultSetPrototype);
        },
    ],
];