<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-cache {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear defined cache name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (cache()->has($name)) {
            cache()->forget($name);
            $this->info("Cache $name cleared.");
        } else {
            $this->error("Cache $name not found.");
        }
    }
}
