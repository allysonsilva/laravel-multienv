<?php

namespace Allyson\MultiEnv\Tests\Unit;

use Mockery;
use Illuminate\Support\Env;
use Allyson\MultiEnv\Tests\TestCase;

class ArtisanTest extends TestCase
{
    /**
     * @test
     * @testdox Using `APP_ROUTES_CACHE` key in domain settings to cache routes
     */
    public function using_APP_ROUTES_CACHE_key_to_cache_domain_routes(): void
    {
        config(['envs.domains' => [
            'site1.test' => [
                'APP_ROUTES_CACHE' => 'routes-site1-test.php',
            ],
            'site2.test' => [
                'APP_ROUTES_CACHE' => 'routes-site2-test.php',
            ]
        ]]);

        $this->updateEnvsConfigFile();

        $this->artisan('route:cache', ['--domain' => 'site1.test'])
             ->expectsOutput(Mockery::pattern('/^Route cache cleared/'))
             ->expectsOutput(Mockery::pattern('/^Routes cached successfully/'));

        $cachedRoutesFilename = base_path('bootstrap/cache/routes-site1-test.php');

        self::assertTrue($this->app['files']->exists($cachedRoutesFilename));
        self::assertFalse(str_contains(file_get_contents($cachedRoutesFilename), 'site2.test'));

        $this->reloadApplication();

        self::assertTrue($this->app->routesAreCached());
        self::assertSame($this->app->getCachedRoutesPath(), $cachedRoutesFilename);

        $this->get('http://site1.test')
             ->assertSeeText('site1.test/');

        $this->get('http://site1.test/other')
             ->assertSeeText('site1.test/other');

        $this->get('http://site2.test')
             ->assertNotFound();

        $this->get('http://other-domain.test')
             ->assertNotFound();

        // $command = escapeshellcmd("{$this->getBasePath()}/artisan");

        // exec(
        //     sprintf(
        //         '%s %s %s --domain=site1.test',
        //         escapeshellarg(PHP_BINARY),
        //         $command,
        //         'route:list'
        //     ),
        //     $dataResult
        // );
    }

    /**
     * @test
     * @testdox When the domain of the `--domain` parameter does not exist in the config('envs.domains'), then all routes must be cached
     */
    public function cache_all_routes_when_domain_does_not_exist(): void
    {
        $this->artisan('route:cache')
             ->expectsOutput(Mockery::pattern('/^Route cache cleared/'))
             ->expectsOutput(Mockery::pattern('/^Routes cached successfully/'));

        $cachedRoutesFilename = base_path('bootstrap/cache/routes-v7.php');
        $cachedRoutesContent = file_get_contents($cachedRoutesFilename);

        self::assertTrue($this->app['files']->exists($cachedRoutesFilename));
        self::assertTrue(str_contains($cachedRoutesContent, 'site1.test'));
        self::assertTrue(str_contains($cachedRoutesContent, 'site2.test'));
        self::assertTrue(str_contains($cachedRoutesContent, 'other-domain.test'));
        self::assertTrue(str_contains($cachedRoutesContent, 'domain-not-found.test'));

        self::assertTrue($this->app->routesAreCached());
        self::assertSame($this->app->getCachedRoutesPath(), $cachedRoutesFilename);

        $this->get('http://site1.test')
             ->assertSeeText('site1.test/');

        $this->get('http://site2.test/')
             ->assertSeeText('site2.test/');

        $this->get('http://other-domain.test/')
             ->assertSeeText('other-domain.test/');
    }

    /**
     * @test
     * @testdox Using `APP_CONFIG_CACHE` key in domain to cache configs
     */
    public function using_APP_CONFIG_CACHE_key_to_cache_domain_configs(): void
    {
        config()->set('envs.domains', [
            'site1.test' => [
                'APP_CONFIG_CACHE' => 'config-site1-test.php',
            ],
            'site2.test' => [
                'APP_CONFIG_CACHE' => 'config-site2-test.php',
            ],
        ]);

        $this->updateEnvsConfigFile();

        $this->copyEnvsToEnvs('.env.site1.test', '.env.site2.test');
        // $this->refreshConfiguration();

        // $consoleOutput = $this->runArtisan('config:cache', '--domain=site-XYZ.test')->getOutput();

        $this->artisan('config:cache', ['--domain' => 'site2.test'])
             ->expectsOutput(Mockery::pattern('/^Configuration cache cleared/'))
             ->expectsOutput(Mockery::pattern('/^Configuration cached successfully/'))
             ->run();

        $this->assertTrue($this->app->configurationIsCached());
        $this->assertSame($this->app->getCachedConfigPath(), base_path('bootstrap/cache/config-site2-test.php'));

        $jsonFixtureSite2 = $this->getFixture('env.site2');

        $this->assertEquals(config('domain'), $jsonFixtureSite2['domain']);

        $this->assertSame(config('app.name'), data_get($jsonFixtureSite2, 'app.name'));
        $this->assertSame(config('app.env'), data_get($jsonFixtureSite2, 'app.env'));
        $this->assertSame(config('app.debug'), data_get($jsonFixtureSite2, 'app.debug'));
        $this->assertSame(config('app.url'), data_get($jsonFixtureSite2, 'app.url'));

        Env::getRepository()->clear('APP_CONFIG_CACHE');

        $this->artisan('config:cache', ['--domain' => 'site1.test'])
             ->expectsOutput(Mockery::pattern('/^Configuration cache cleared/'))
             ->expectsOutput(Mockery::pattern('/^Configuration cached successfully/'))
             ->run();

        $this->assertTrue($this->app->configurationIsCached());
        $this->assertSame($this->app->getCachedConfigPath(), base_path('bootstrap/cache/config-site1-test.php'));

        $jsonFixtureSite1 = $this->getFixture('env.site1');

        $this->assertEquals(config('domain'), $jsonFixtureSite1['domain']);

        $this->assertSame(config('app.name'), data_get($jsonFixtureSite1, 'app.name'));
        $this->assertSame(config('app.env'), data_get($jsonFixtureSite1, 'app.env'));
        $this->assertSame(config('app.debug'), data_get($jsonFixtureSite1, 'app.debug'));
        $this->assertSame(config('app.url'), data_get($jsonFixtureSite1, 'app.url'));
    }

    /**
     * @test
     * @testdox When the domain of the `--domain` parameter does not exist in the config('envs.domains'), then all configs must be cached
     */
    public function cache_all_configs_when_domain_does_not_exist(): void
    {
        $this->artisan('config:cache')
             ->expectsOutput(Mockery::pattern('/^Configuration cache cleared/'))
             ->expectsOutput(Mockery::pattern('/^Configuration cached successfully/'));

        $cachedConfigsFilename = base_path('bootstrap/cache/config.php');
        $cachedConfigs = require $cachedConfigsFilename;

        self::assertTrue($this->app['files']->exists($cachedConfigsFilename));
        self::assertArrayHasKey('domain', $cachedConfigs);
        self::assertArrayHasKey('app', $cachedConfigs);
        self::assertArrayHasKey('auth', $cachedConfigs);
        self::assertArrayHasKey('database', $cachedConfigs);

        $this->assertEquals($cachedConfigs['domain'], $this->getFixture('non-existent-domain'));
        $this->assertTrue($this->app->configurationIsCached());
        $this->assertSame($this->app->getCachedConfigPath(), base_path('bootstrap/cache/config.php'));
    }
}
