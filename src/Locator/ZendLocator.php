<?php
namespace TacticianModule\Locator;

use Interop\Container\ContainerInterface;
use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ZendLocator implements HandlerLocator
{
    /** @var array */
    protected $handlerMap;

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
        if (!$this->commandExists($commandName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        $serviceNameOrFQCN = $this->handlerMap[$commandName];

        try {
            return $this->serviceLocator->get($serviceNameOrFQCN);
        } catch (ServiceNotFoundException $e) {
            // Further check exists for class availability.
            // If not, Exception will be thrown anyway.
        }

        if (class_exists($serviceNameOrFQCN)) {
            return new $serviceNameOrFQCN();
        }

        throw MissingHandlerException::forCommand($commandName);
    }

    /**
     * @param string $commandName
     * @return bool
     */
    protected function commandExists($commandName)
    {
        if (!$this->handlerMap) {
            $this->handlerMap = $this->serviceLocator->get('config')['tactician']['handler-map'];
        }

        return isset($this->handlerMap[$commandName]);
    }
}
