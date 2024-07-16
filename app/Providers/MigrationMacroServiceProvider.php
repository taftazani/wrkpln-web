<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class MigrationMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blueprint::macro('timestampsWithUser', function () {
            $this->timestamp('created_at')->nullable();
            $this->unsignedBigInteger('created_by')->nullable();
            $this->timestamp('updated_at')->nullable();
            $this->unsignedBigInteger('updated_by')->nullable();
        });
    }
}