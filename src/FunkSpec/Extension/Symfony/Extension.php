<?php

namespace FunkSpec\Extension\Symfony;

use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Funk\Tester\ServiceContainer\TesterExtension;
use FunkSpec\Extension\Symfony\ArgumentResolver\Container;
use FunkSpec\Extension\Symfony\Listener\Rollback;

final class Extension implements ExtensionInterface
{
    const KERNEL_ID = 'symfony_extension.kernel';

    public function getConfigKey()
    {
        return 'symfony';
    }

    public function initialize(ExtensionManager $extensionManager)
    {
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('kernel')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->isRequired()->end()
                        ->scalarNode('env')->defaultValue('test')->end()
                        ->booleanNode('debug')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('doctrine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('rollback')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }

    public function process(ContainerBuilder $container)
    {
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $this->loadKernel($container, $config['kernel']);
        $this->loadSpecInitializer($container, $config);
        if ($config['doctrine']['rollback']) {
            $this->loadRollbackListener($container, $config);
        }
    }

    private function loadKernel(ContainerBuilder $container, array $config)
    {
        $definition = new Definition($config['class'], array(
            $config['env'],
            $config['debug'],
        ));
        $definition->addMethodCall('boot');
        $container->setDefinition(self::KERNEL_ID, $definition);
    }

    private function loadSpecInitializer(ContainerBuilder $container, array $config)
    {
        $definition = new Definition(Container::class, [
            new Reference(self::KERNEL_ID),
        ]);
        $definition->addTag(TesterExtension::INITIALIZER_TAG);
        $container->setDefinition('symfony_extension.argument_resolver.container', $definition);
    }

    private function loadRollbackListener(ContainerBuilder $container, array $config)
    {
        $definition = new Definition(Rollback::class, [
            new Reference(self::KERNEL_ID),
        ]);
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, ['priority' => 0]);
        $container->setDefinition('symfony_extension.listener.rollback', $definition);
    }
}
