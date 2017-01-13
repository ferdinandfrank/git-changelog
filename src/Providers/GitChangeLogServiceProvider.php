<?php

namespace EpicArrow\GitChangeLog\Providers;

use EpicArrow\GitChangeLog\GitChangeLog;
use Illuminate\Support\ServiceProvider;
use View;

/**
 * GitChangelogServiceProvider
 * -----------------------
 * Provides the necessary files to use the Git Changelog extension in the application.
 *
 * @author  Ferdinand Frank
 * @version 1.0
 * @package App\Providers
 */
class GitChangeLogServiceProvider extends ServiceProvider {

    /**
     * Bootstraps the application services.
     *
     * @return void
     */
    public function boot() {
        View::share('gitVersion', GitChangeLog::version());
    }

    /**
     * Registers the application services.
     *
     * @return void
     */
    public function register() {
        // Define package base path
        if (!defined('VUE_FORMS_BASE_PATH')) {
            define('VUE_FORMS_BASE_PATH', realpath(__DIR__ . '/../../'));
        }
    }
}
