<?php declare(strict_types=1);

namespace funk\fixture;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Kernel implements KernelInterface
{
    public function registerBundles()
    {
        return [];
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return new Response;
    }

    public function serialize()
    {
    }

    public function unserialize($data)
    {
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }

    public function boot()
    {
    }

    public function shutdown()
    {
    }

    public function getBundles()
    {
        return [];
    }

    public function getBundle($name, $first = true)
    {
        throw new \InvalidArgumentException;
    }

    public function locateResource($name, $dir = null, $first = true)
    {
    }

    public function getName()
    {
        return 'app';
    }

    public function getEnvironment()
    {
        return 'test';
    }

    public function isDebug()
    {
        return true;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getContainer()
    {
        return new Container;
    }

    public function getStartTime()
    {
        return time();
    }

    public function getCacheDir()
    {
        return __DIR__;
    }

    public function getLogDir()
    {
        return __DIR__;
    }

    public function getCharset()
    {
        return 'utf-8';
    }
}
