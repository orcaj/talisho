<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('props', function ($key = null) {
            $props = json_decode(json_encode($this->original->getData()['page']['props']), JSON_OBJECT_AS_ARRAY);

            if ($key) {
                return Arr::get($props, $key);
            }

            return $props;
        });

        TestResponse::macro('assertContainsProp', function ($key) {
            Assert::assertTrue(Arr::has($this->props(), $key));
            return $this;
        });

        TestResponse::macro('assertPropEquals', function ($key, $value) {
            if (is_callable($value)) {
                $value($this->props($key));
            } else {
                Assert::assertEquals($this->props($key), $value);
            }

            return $this;
        });

        TestResponse::macro('assertPropCount', function ($key, $count) {
            Assert::assertCount($count, $this->props($key));
            return $this;
        });
    }

    protected function signIn($user = null)
    {
        $user = $user ?? factory('App\User')->states('registered')->create();

        $this->actingAs($user);

        return $user;
    }

    protected function getWithQueryString($uri, array $params)
    {
        return $this->call('GET', $uri, $params);
    }
}
