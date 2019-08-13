<?php

namespace App;

use App\common\AppConstant;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ServiceContainerTest extends TestCase
{


    public function testInject()
    {
        self::assertNotNull(ServiceContainer::inject()->get(AppConstant::IOC_USER_MODEL));
    }

    function testInjectInvalid(){
        try {
            self::assertNotNull(ServiceContainer::inject()->get('TestClass'));
        } catch (Exception $e) {
            $this->expectException(ServiceNotFoundException::class);
            throw $e;
        }
    }
}
