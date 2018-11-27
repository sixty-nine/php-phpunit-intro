# Introduction to PHPUnit

Yes, writing unit test is easy!

> Testing your code makes you a better programmer.
> Making your code testable makes your code better and you an even better programmer.
>
> -An anonymous developer

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
 * The tests are supposed to be placed in the directory `tests` and their filename must end with `Test.php` as said in the `<testsuite>`.
 * This structure is typical of a Laravel project. Adapt to your need.

## Your first test

### Writing your first test

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

### Executing your first test

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

### Failing tests

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

First things to look at: 

 * the reason: Failed asserting that false is true.
 * the filename: tests/MyFirstTest.php
 * the line number: 15

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
To simplify our life it is possible to ask PHPUnit to stop when an error or a failure occur.
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

A real life use case: each time I have to debug a single test in a huge test case I mark it as being part of the
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

## Annotations

### Testing exceptions

If you have an exception in the code you are testing, it will make your test fail.

However it might be usefull and sometimes required to test that an exception was thrown.

This is possible with some annotations.

```
/**
 * @expectedException \InvalidArgumentException
 * @expectedExceptionMessage The number must be positive
 */
public function testArgumentMustBePositive()
{
    $fibonacci = new Fibonacci();
    $fibonacci->calc(-1);
}
```

Asserting an exception is thrown is right and very usefull.

On the other hand, you should avoid testing the exception messages. Those will very likely change making suddenly your
tests fail.

### Data providers

Data provider allow you to call the same test method multiple time with different parameters.

A data provider must return an *array of parameters arrays*.

For each of those parameters arrays the test method will be called and its parameters will be replaced by the one
provided.

```
/** @dataProvider myTestProvider */
public function testMyTest(int $number, int $expectedResult)
{
    $fibonacci = new Fibonacci();
    $this->assertSame($expectedResult, $fibonacci->calc($number));
}

public function myTestProvider()
{
    return [
        [0, 0],
        [1, 1],
        [2, 1],
        // ...
    ];
}

```

In the previous example the function `testMyTest` will be called 3 times with:

 * $number = 0, $expectedResult = 0
 * $number = 1, $expectedResult = 1
 * $number = 2, $expectedResult = 1

### Tests run order

By default it is not possible to predict in which order PHPUnit will run the test methods in a test case.

*It is not guaranteed that they will be run in the order they appear in the source code!*

If you need a test method to be run after another one you may use the `@depends` annotation.


```
public function testFirstTest() { }

/** @depends testFirstTest */
public function testSecondTest() { }

```

However please note that having tests that need to be run in a given order is a "code smell".

Ideally tests should be independent one from the other.

## Generating code coverage

Code coverage can help you to identify parts of your code that need to be tested.

To enable code coverage create a (git-ignored) directory `coverage` in your project.

Then lets tweak our phpunit.xml configuration a little:

```xml
<logging>
    <log type="coverage-html" target="coverage" lowUpperBound="35" highLowerBound="70"/>
</logging>

```

Next time you will run the test you will see this notice in your console:

```
Generating code coverage report in HTML format ... done
```

The code coverage entry point will be in `coverage/index.html`.

Explore it with a browser pointing it to `file:///absolute/path/to/my/project/coverage/index.html`.

It is important to understand that code coverage indicates which line of code was **executed**. It does not tells anything
about whether it was actually tested (assertions where made over it) or not.

Code coverage requires XDebug enabled!
