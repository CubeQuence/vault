<?php

// https://pestphp.com/docs/expectations

it('asserts true is true', function () {
    $this->assertTrue(true);
});

it('asserts false is false', function () {
    $this->assertFalse(false);
});

it('asserts array contains 4 elements', function () {
    $array = [1, 2, 3, 4];

    $this->assertCount(4, $array);
});

it('asserts equals', function () {
    $array = [1, 2, 3, 4];

    $this->assertEquals([1, 2, 3, 4], $array);
});

it('asserts empty', function () {
    $array = [];

    $this->assertEmpty($array);
});

it('asserts string contains substring', function () {
    $this->assertStringContainsString('Star', 'Star Wars');
});

it('throws exception', function () {
    throw new Exception('Something happened.');
})->throws(Exception::class);

it('throws exception with matching message', function () {
    throw new Exception('Something happened.');
})->throws(Exception::class, 'Something happened.');
