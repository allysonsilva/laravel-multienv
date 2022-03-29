<?php

namespace Allyson\MultiEnv\Tests\Unit;

use Allyson\MultiEnv\Tests\TestCase;

class EnvsTest extends TestCase
{
    /** @test */
    public function when_have_another_env_file_must_overwrite_default_env()
    {
        $this->copyEnvsToRoot('.envA');

        // Required to load new .env files
        $this->refreshConfiguration();

        $this->assertEquals(config('domain'), $this->getFixture('envA'));
    }

    /** @test */
    public function when_have_multiple_env_files_the_latter_should_overwrite_all_others()
    {
        // laravel
        //     ├── .env
        //     ├── .envA
        //     └── .envB
        $this->copyEnvsToRoot('.envA', '.envB');

        // Required to load new .env files
        $this->refreshConfiguration();

        $this->assertEquals(config('domain'), $this->getFixture('envB'));

        $jsonFixtureEnv = $this->getFixture('env');

        $this->assertSame(config('app.url'), data_get($jsonFixtureEnv, 'app.url'));

        // laravel
        //     ├── .env
        //     ├── .envA
        //     ├── .envB
        //     └── .envC
        $this->copyEnvsToRoot('.envC');

        // Required to load new .env files
        $this->refreshConfiguration();

        $this->assertEquals(config('domain'), $this->getFixture('envC'));
    }

    /**
     * @test
     * @testdox `.env` files in `envs` folder should be ignored when not a domain env
     *
     * @return void
     */
    public function ignore_envs_folder_when_not_a_domain()
    {
        // laravel
        //     ├── envs
        //     │   └── .envC
        //     ├── .env
        //     ├── .envA
        //     └── .envB
        $this->copyEnvsToEnvs('.envC');
        $this->copyEnvsToRoot('.envA', '.envB');

        // Required to load new .env files
        $this->refreshConfiguration();

        $this->assertEquals(config('domain'), $this->getFixture('envB'));
    }

    /**
     * @test
     * @testdox Using `sorted` config to overwrite environment variables
     */
    public function sort_use_envs_files()
    {
        // laravel
        //     ├── .env
        //     ├── .envA
        //     ├── .envB
        //     └── .envC
        $this->copyEnvsToRoot();

        // Required to load new .env files
        $this->refreshConfiguration();

        $this->assertEquals(config('domain'), $this->getFixture('envC'));

        $this->setCustomEnvsSort("'.envB', '.envC', '.envA',");
        $this->assertEquals(config('domain'), $this->getFixture('envA'));

        $this->setCustomEnvsSort("'.envC', '.envA', '.envB',");
        $this->assertEquals(config('domain'), $this->getFixture('envB'));
    }
}
