<?php

namespace FunkSpec\Extension\Symfony\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class Rollback implements EventSubscriberInterface
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public static function getSubscribedEvents()
    {
        return [
            'beforeExample' => 'beginTransaction',
            'afterExample' => 'rollback',
        ];
    }

    public function beginTransaction()
    {
        $this->kernel->getContainer()->get('doctrine')->getManager()->beginTransaction();
    }

    public function rollback()
    {
        $em = $this->kernel->getContainer()->get('doctrine')->getManager();
        $em->rollback();
        $em->clear();
    }
}
