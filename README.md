Getting Started With ChapleanLocationBundle
===========================================

# Prerequisites

This version of the bundle requires Symfony 2.8+.

# Installation

## 1. Composer

```
composer require chaplean/location-bundle
```

## 2. AppKernel.php

Add
```
new Chaplean\Bundle\LocationBundle\ChapleanLocationBundle(),
```

## 3. Inject locations

Run
```bash
bin/console doctrine:fixtures:load --fixtures vendor/chaplean/location-bundle/DataFixtures/ORM/ --append true
```
or
Add command in migration
```php
public function postUp(Schema $schema)
{
    /** @var Kernel $kernel */
    $kernel = $this->container->get('kernel');
    $application = new Application($kernel);
    $application->setAutoExit(false);
    
    $exitCode = $application->run(new ArrayInput([
        'command'    => 'doctrine:fixtures:load',
        '--fixtures' => 'vendor/chaplean/location-bundle/DataFixtures/ORM/',
        '--append'   => true
    ]));

    $this->abortIf($exitCode !== 0, 'see Exception above ^');
}
```

## 4. Resources

* Regions: ?
* Départements: https://www.insee.fr/fr/information/2114819 (cf "Liste des départements") 
* Villes: https://www.data.gouv.fr/fr/datasets/base-officielle-des-codes-postaux/