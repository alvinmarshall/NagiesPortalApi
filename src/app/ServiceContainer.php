<?php


namespace App;


use App\common\AppConstant;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ServiceContainer
{
    static function inject()
    {
        $container = new ContainerBuilder();
        $container->register(AppConstant::IOC_REQUEST, 'App\routes\Request');
        $container->register(AppConstant::IOC_DATABASE, 'App\config\Database');
        $container->register(AppConstant::IOC_STUDENTS_MODEL, 'App\data\model\Students')
            ->addArgument(new Reference(AppConstant::IOC_DATABASE));
        $container->register(AppConstant::IOC_USER_MODEL, 'App\data\model\Users')
            ->addArgument(new Reference(AppConstant::IOC_DATABASE));
        $container->register(AppConstant::IOC_TEACHERS_MODEL, 'App\data\model\Teachers')
            ->addArgument(new Reference(AppConstant::IOC_DATABASE));
        $container->register(AppConstant::IOC_FCM_SERVICE, 'App\notification\FirebaseMessagingService');
        return $container;
    }
}
