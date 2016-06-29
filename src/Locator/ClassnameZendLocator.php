<?php
namespace TacticianModule\Locator;

use Interop\Container\ContainerInterface;
use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ClassnameZendLocator implements HandlerLocator
{
    private $serviceLocator;

    public function __construct(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieves the handler for a specified command
     *
     * @param string $commandName
     *
     * @return mixed
     *
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand($commandName)
    {
        $handlerFQCN = $commandName . 'Handler';

        try {
            return $this->serviceLocator->get($handlerFQCN);
        } catch (ServiceNotFoundException $e) {
            // Further check exists for class availability.
            // If not, Exception will be thrown anyway.
        }

        if (class_exists($handlerFQCN)) {
            return new $handlerFQCN();
        }

        throw MissingHandlerException::forCommand($commandName);
    }
}
