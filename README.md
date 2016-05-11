Getting Started With ChapleanLocationBundle
=======================================

# Prerequisites

This version of the bundle requires Symfony 2.8+.

### Installation

Include ChapleanLocationBundle in `composer.json`

``` json
composer require chaplean/location-bundle
```

Add bundle in `AppKernel.php`

```php
<?php
    //...
    public function registerBundles()
    {
        return array (
            //...
            new Chaplean\Bundle\LocationBundle\ChapleanLocationBundle(),
        );
    }
```
