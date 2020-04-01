[![License](https://poser.pugx.org/theimpossibleastronaut/php-sam/license)](https://packagist.org/packages/theimpossibleastronaut/php-sam)
[![SourceLevel](https://app.sourcelevel.io/github/theimpossibleastronaut/php-sam.svg)](https://app.sourcelevel.io/github/theimpossibleastronaut/php-sam)
[![Latest Stable Version](https://poser.pugx.org/theimpossibleastronaut/php-sam/v/stable)](https://packagist.org/packages/theimpossibleastronaut/php-sam)
[![Latest Unstable Version](https://poser.pugx.org/theimpossibleastronaut/php-sam/v/unstable)](https://packagist.org/packages/theimpossibleastronaut/php-sam)
[![PHP Stan](https://github.com/theimpossibleastronaut/php-sam/workflows/PHP%20Stan/badge.svg)](https://packagist.org/packages/theimpossibleastronaut/php-sam)
[![PHP Composer](https://github.com/theimpossibleastronaut/php-sam/workflows/PHP%20Composer/badge.svg)](https://packagist.org/packages/theimpossibleastronaut/php-sam)


# php-sam
Implementation for the SAMv3 bridge in PHP.

Based on the article "So you want to write a SAM library" on [geti2p.net](http://geti2p.net/en/blog/post/2019/06/23/sam-library-basics).

Most basic features are implemented. Go ahead and play with the examples.
It's very basic as well. Requires PHP 7.2+.

```php
<?php
require_once( "lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();
$sam->connect( false );
$sam->commandSAM( "HELLO VERSION MIN=3.0 MAX=3.1 \n" );
```

You can also use composer for this.
```
composer require theimpossibleastronaut/php-sam
```

And then use the autoloader as followed;
```php
<?php
require __DIR__ . '/vendor/autoload.php';

$sam = new \PHP_SAM\SAM3();
$sam->connect( false );
$sam->commandSAM( "HELLO VERSION MIN=3.0 MAX=3.1 \n" );
```

In order to run the tests, enable zend.assertions in your php.ini (don't do that in production) and run php test/assert.php .
