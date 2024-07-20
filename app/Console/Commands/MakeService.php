<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';
    protected $files;
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->createDirectory($name);
        $this->createService($name);
        $this->info("Service {$name} created successfully.");
    }
    protected function createDirectory($name)
    {
        $directory = app_path("Services/{$name}");
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    protected function createService($name)
    {
        $serviceTemplate = $this->getServiceTemplate($name);
        $path = app_path("Services/{$name}/{$name}.php");
        $this->files->put($path, $serviceTemplate);
    }
    protected function getServiceTemplate($name)
    {
        return "<?php
        namespace App\Services\'$name';
        class {$name}
        {
        // Your service methods go here
        }
        ";
    }
}
