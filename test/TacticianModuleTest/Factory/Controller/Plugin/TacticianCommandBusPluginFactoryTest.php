<?php
namespace TacticianModuleTest\Factory\Controller\Plugin;

use Interop\Container\ContainerInterface;
use League\Tactician\CommandBus;
use TacticianModule\Controller\Plugin\TacticianCommandBusPlugin;
use TacticianModule\Factory\Controller\Plugin\TacticianCommandBusPluginFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\AbstractPluginManager;

class TacticianCommandBusPluginFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateServiceWithAbstractPluginManager()
    {
        /** @var CommandBus|\PHPUnit_Framework_MockObject_MockObject $commandBus */
        $commandBus = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();


        /** @var AbstractPluginManager|\PHPUnit_Framework_MockObject_MockObject $pluginManager */
        $pluginManager = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();


        $pluginManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo(CommandBus::class))
            ->will($this->returnValue($commandBus));

        $factory = new TacticianCommandBusPluginFactory();
        $this->assertInstanceOf(TacticianCommandBusPlugin::class, $factory($pluginManager, ''));
    }

    public function testCreateServiceWithServiceManager()
    {
        /** @var CommandBus|\PHPUnit_Framework_MockObject_MockObject $commandBus */
        $commandBus = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ServiceManager|\PHPUnit_Framework_MockObject_MockObject $sm */
        $sm = $this->getMock(ServiceManager::class, ['get']);
        $sm->expects($this->once())
            ->method('get')
            ->with($this->equalTo(CommandBus::class))
            ->will($this->returnValue($commandBus));

        $factory = new TacticianCommandBusPluginFactory();
        $this->assertInstanceOf(TacticianCommandBusPlugin::class, $factory($sm,''));
    }
}
