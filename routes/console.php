<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('make:service {name}', function ($name) {
    Artisan::call('make:service', ['name' => $name]);
})->describe('Create a new service class');

Artisan::command('make:repository {name}', function ($name) {
    Artisan::call('make:repository', ['name' => $name]);
})->describe('Create a new repository class');
