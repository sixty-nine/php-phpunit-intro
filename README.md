# Introduction to PHPUnit

## Introduction

Yes, writing unit test is easy !

At least, in this repo I will try to show it is.

## Setup the infrastructure

First you have to include PHPUnit in your project.

```bash
composer require phpunit/phpunit
```

And then create a `phpunit.xml` config file for it. The following can be used as a generic template.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit backupGlobals="false"
         backupStaticAttributes="false"
         colors="true"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"
         processIsolation="false"
         stopOnFailure="false"
         bootstrap="vendor/autoload.php"
>
    <!-- http://www.phpunit.de/manual/current/en/appendixes.configuration.html -->

    <testsuites>
        <testsuite name="Unit tests">
            <directory suffix="Test.php">./tests</directory>
        </testsuite>
    </testsuites>

    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">src</directory>
        </whitelist>
    </filter>

</phpunit>
```

Important stuff to notice:

 * You can fine tune about every PHPUnit settings here.
 * It is always good to leave a comment with a reference to the current file format specification.
 * In the `bootstrap` attribute of the `phpunit` tag we can call a php script. Here we simply call the composer autoload.
 * The source code is supposed to be in the directory `src` as specified in the `<filter>`.
 * The tests are supposed to be places in the directory `tests` and their filename must end with `Test.php` as said in the `<testsuite>`.
 * This structure is typical of a Laravel project. Adapt to your need.

## Writing your first test

Create your first test case in `tests/MyFirstTest.php` with the following content.

```php
# tests/MyFirstTest.php
<?php

use PHPUnit\Framework\TestCase;

class MyFirstTest extends TestCase
{
    public function test_true_be_or_not_true_be()
    {
        $this->assertTrue(true);
    }

    public function this_is_not_a_test()
    {
        // This will never be executed!
        $this->assertTrue(false);
    }
}

```

We have written a wrong assertion: `$this->assertTrue(false)`. However it will not be executed. You will see below why.

## Executing your first test

Now run the tests and see everything is green.

```bash
vendor/bin/phpunit
```

This will result in

```
PHPUnit 6.5.13 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 50 ms, Memory: 4.00MB

OK (1 test, 1 assertion)
```

As you can see we have a single test and a single assertion executed.
The function `this_is_not_a_test` was not called at all. It's because its name does not start with `test`.

## Failing tests

Let's refactor the `this_is_not_a_test` function so that it becomes a test. Its name now starts with `test`.

```
public function test_this_is_not_a_test()
{
    $this->assertTrue(false);
}
```

What happens when we run the test case?

```
PHPUnit 6.5.13 by Sebastian Bergmann and contributors.

.F                                                                  2 / 2 (100%)

Time: 51 ms, Memory: 4.00MB

There was 1 failure:

1) MyFirstTest::test_this_is_not_a_test
Failed asserting that false is true.

/home/dev/doc/unit-test/src/tests/MyFirstTest.php:15

FAILURES!
Tests: 2, Assertions: 2, Failures: 1.
```

## Useful CLI parameters

### Running only some tests

You can specify on command line in which file or directory to look for tests.
The path must be in the PHPUnit whitelist (see config file).

```bash
vendor/bin/phpunit tests/Fibonacci/
vendor/bin/phpunit tests/Fibonacci/Fibonacci2Test.php
```

### Stop when shit happens

It is annoying to debug a test suite with lot of failing test.
To simplify our life it is possible to ask PHPUnit to stop an error or a failure occurred.
These are not the same, errors are PHP errors and exceptions, failures are assertions failures.

```bash
vendor/bin/phpunit --stop-on-error --stop-on-failure
```

### Make your PM happy

To have a "PM-friendly" display of the test suite execution use the `--testdox` parameter:

```bash
vendor/bin/phpunit --testdox
```

```
PHPUnit 6.5.13 by Sebastian Bergmann and contributors.

MyFirst
[x] true be or not true be
[ ] this is not a test
```

### Running only some tests in a test case

It is possible to assign test methods to groups and then only run specific groups of tests.

A real life use case: each time I have to debug a single test in a huge test case I do mark it as being part of the
`current` group:

```php
<?php

use PHPUnit\Framework\TestCase;

class HugeTestCase extends TestCase
{
    public function testUninterestingStuff() {}

    // ...

    /** @group current */
    public function testSomethingCool() {}

    // ...

    public function testSomeMoreUninterestingStuff() {}
}
```

Then you can run this single test like this:

```bash
vendor/bin/phpunit --group current
```

## Generating code coverage

Code coverage can help you to identify parts of your code that need to be tested.

To enable code coverage create a (git-ignored) directory `coverage` in your project.

Then lets tweak our phpunit.xml configuration a little:

```xml
<logging>
    <log type="coverage-html" target="coverage" lowUpperBound="35" highLowerBound="70"/>
</logging>

```
    </testsuite></filter>

Next time you will run the test you will see this notice in your console:

```
Generating code coverage report in HTML format ... done
```

The code coverage entry point will be in `coverage/index.html`. Explore it with a browser pointing it to
`file:///absolute/path/to/my/project/coverage/index.html`.
