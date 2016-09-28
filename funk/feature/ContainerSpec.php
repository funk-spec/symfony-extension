<?php

namespace funk\feature;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerSpec implements \Funk\Spec
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function it_has_container()
    {
        var_dump(get_class($this->container));
    }
}
