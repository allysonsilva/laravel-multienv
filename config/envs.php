<?php

return [

    /*
     * Name of the custom folder where the domains `.env` files are located.
     */
    'folder' => 'envs',

    /*
     * Regex with the names of the `.env` files that will be ignored on
     * application startup.
     */
    'ignored' => '/(?:\.env)\.(example|testing|staging)/',

    /*
     * - Order of .envs files to know precedence.
     * - Only the envs files in the root of the application, that is, in the same location as the `.env`.
     * - Envs items at the top of the array have lower precedence than files at
     *   the end of the array, that is, envs items at the end of the array will override all others.
     */
    'sorted' => [],

    /*
     * List of cache environment variables by domain and custom .env file.
     */
    'domains' => [
        // 'domain.tld' => [
        //     'APP_CONFIG_CACHE' => 'config-domain-tld.php',
        //     'APP_ROUTES_CACHE' => 'routes-v7-domain-tld.php',
        //     'APP_EVENTS_CACHE' => 'events-domain-tld.php',
        //     'env' => '.env.domain.tld',
        // ],
    ],
];
