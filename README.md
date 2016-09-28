# funk-spec/symfony-extension

     composer require --dev funk-spec/symfony-extension

## What ?

A [funk-spec](https://github.com/funk-spec/funk-spec) extension that integrates your symfony application
with your specs.

It will:

 - resolve constructor arguments typehinted with `ContainerInterface`
 - wrap each example with a doctrine transaction and rollback afterwards

## Why ?

For the same exact reason than the [behat's one](https://github.com/Behat/Symfony2Extension/) exists.  
Both are relying on the TestWork framework.  
Unfortunately, a lot of duplication exists between both, but no real tentative has been done yet
to abstract away the few differences.  

## How ?

In your funk.yml:

```yml
default:
    autoload:
        tests: '%paths.base%'

    suites:
        default: ~

    extensions:
        FunkSpec\Extension\Symfony\Extension:
            kernel:
                class: App\Symfony\Kernel # or AppKernel (must be autoloadable)
                env: test
```

Now your spec classes can have the container injected:

```php
<?php

namespace tests\Doctrine\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class Products implements \Funk\Spec
{
    public function __construct(ContainerInterface $container)
    {
        $this->products = $container->get('products'); // this is a repository
    }

    function it_works()
    {
        $this->products->find('77c4bb2e-2c18-4164-a899-7f969dec5c9d')->getId();
    }
}
```

### automatic transaction wrapping and rollback

Every example is ran in a transaction and rollbacked after each execution, except if you explicitely disable it:

```yml
default:
    extensions:
        FunkSpec\Extension\Symfony\Extension:
            doctrine:
                rollback: false
```

This depends on the presence of a `doctrine` service in the application Kernel, insanceof `ManagerRegistry`.

