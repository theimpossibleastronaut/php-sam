[![SourceLevel](https://app.sourcelevel.io/github/theimpossibleastronaut/php-sam.svg)](https://app.sourcelevel.io/github/theimpossibleastronaut/php-sam)

# php-sam
Implementation for the SAMv3 bridge in PHP.

Based on the article "So you want to write a SAM library" on [geti2p.net](http://geti2p.net/en/blog/post/2019/06/23/sam-library-basics).

This is a work in progress, but go ahead and give it a try.
It's very basic as well. Requires PHP 7.2+.

```php
<?php
require_once( "lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();
$sam->connect( false );
$sam->commandSAM( "HELLO VERSION MIN=3.0 MAX=3.1 \n" );
```

In order to run the tests, enable zend.assertions in your php.ini (don't do that in production) and run php test/assert.php .