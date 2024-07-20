<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository class';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $this->createDirectory($name);
        $this->createRepository($name);
        $this->info("Repository {$name} created successfully.");
    }

    protected function createDirectory($name)
    {
        $directory = app_path("Repositories/{$name}");
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }

    protected function createRepository($name)
    {
        $repositoryTemplate = $this->getRepositoryTemplate($name);
        $path = app_path("Repositories/{$name}/{$name}.php");
        $this->files->put($path, $repositoryTemplate);
    }

    protected function getRepositoryTemplate($name)
    {
        return "<?php
        namespace App\Repositories\{$name};

        class {$name}
        {
        // Your repository methods go here
        }
        ";
    }
}
