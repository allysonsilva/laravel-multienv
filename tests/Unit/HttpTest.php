<?php

namespace Allyson\MultiEnv\Tests\Unit;

use Allyson\MultiEnv\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class HttpTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Route::domain('site2.test')->group(function () {
            Route::get('domain-route', function () {
                return response()->json(config('domain'));
            })->middleware(['api']);
        });
    }

    /**
     * @test
     * @testdox Using the `env` key in the domain settings to customize the `.env` filename
     */
    public function custom_domain_env_filename(): void
    {
        config()->set('envs.domains', [
            'site2.test' => [
                'env' => '.env.custom.site2',
            ],
        ]);

        $this->updateEnvsConfigFile();

        $this->copyEnvsToEnvsWithCustomName('.env.site2.test', '.env.custom.site2');

        // Required to load new .env files
        // $this->refreshConfiguration();

        $this->getJson('http://site2.test/domain-route')
             ->assertJson($this->getFixture('env.site2')['domain']);
    }
}
