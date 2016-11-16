<?php
/**
 * Created by PhpStorm.
 * User: Serhii Shnurenko
 * Date: 11/14/16
 * Time: 3:46 PM
 */

namespace DataGrid;


use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig(){
        return include __DIR__.'/../config/module.config.php';
    }

    public function getServiceConfig(){
        return include __DIR__.'/../config/service.config.php';
    }

    public function getControllerConfig(){
        return include __DIR__.'/../config/controller.config.php';

    }

}