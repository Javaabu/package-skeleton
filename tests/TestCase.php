<?php

namespace Javaabu\{YourPackage}\Tests;

use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;
use Javaabu\{YourPackage}\{YourPackage}ServiceProvider;

abstract class TestCase extends BaseTestCase
{

    protected $baseUrl = 'http://localhost';

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.key', 'base64:yWa/ByhLC/GUvfToOuaPD7zDwB64qkc/QkaQOrT5IpE=');

        $this->app['config']->set('session.serialization', 'php');

    }

    protected function getPackageProviders($app)
    {
        return [{YourPackage}ServiceProvider::class];
    }
}
