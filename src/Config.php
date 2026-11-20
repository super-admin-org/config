<?php

namespace SuperAdmin\Admin\Config;

use SuperAdmin\Admin\Admin;
use SuperAdmin\Admin\Extension;

class Config extends Extension
{
    /**
     * Migrations run by `php artisan migrate`; super-admin also lists pending ones in `admin:upgrade`.
     *
     * @var string
     */
    public $migrations = __DIR__.'/../database/migrations';

    /**
     * Load configure into laravel from database.
     *
     * @return void
     */
    public static function load()
    {
        foreach (ConfigModel::all(['name', 'value']) as $config) {
            config([$config['name'] => $config['value']]);
        }
    }

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('config', __CLASS__);
    }

    /**
     * Register routes for super-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->resource(
                config('admin.extensions.config.name', 'config'),
                config('admin.extensions.config.controller', 'SuperAdmin\Admin\Config\ConfigController')
            );
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Config', 'config', 'icon-toggle-on');

        parent::createPermission('Admin Config', 'ext.config', 'config*');
    }
}
