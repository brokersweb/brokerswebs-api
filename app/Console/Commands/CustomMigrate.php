<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class CustomMigrate extends Command
{
    protected $signature = 'migrate:except {tables*}';
    protected $description = 'Run migrations excluding specific tables';

    public function handle()
    {
        $tables = $this->argument('tables');
        $path = database_path('migrations');
        $files = scandir($path);

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $shouldExclude = false;
                foreach ($tables as $table) {
                    if (strpos($file, $table) !== false) {
                        $shouldExclude = true;
                        break;
                    }
                }

                if (!$shouldExclude) {
                    Artisan::call('migrate', [
                        '--path' => 'database/migrations/' . $file
                    ]);
                }
            }
        }
    }
}
