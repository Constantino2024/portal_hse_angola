<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}.php");
        
        // Criar diretório se não existir
        if (!File::exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'));
        }
        
        // Conteúdo do service
        $content = "<?php\n\nnamespace App\Services;\n\nclass {$name}\n{\n    // Seus métodos aqui\n}\n";
        
        File::put($path, $content);
        
        $this->info("Service {$name} created successfully!");
    }
}