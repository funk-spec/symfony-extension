<?php

namespace FunkSpec\Extension\Symfony\ArgumentResolver;

use Funk\Spec;
use Behat\Testwork\Suite\Suite;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class Container implements \Funk\Initializer\Spec
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function isSupported(Suite $suite, \ReflectionClass $reflect)
    {
        return true;
    }

    public function resolveArguments(Suite $suite, \ReflectionMethod $constructor)
    {
        $parameters = $constructor->getParameters();
        $arguments = [];
        foreach ($parameters as $parameter) {
            if ($parameter->getClass() && is_a($parameter->getClass()->name, ContainerInterface::class, true)) {
                $arguments[$parameter->name] = $this->kernel->getContainer();
            }
        }

        return $arguments;
    }

    public function initialize(Suite $suite, Spec $spec)
    {
    }
}
