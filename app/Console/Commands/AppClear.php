<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear App';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('config:clear');
        $this->call('view:clear');
        if (config('debugbar.enabled')) {
            $this->call('debugbar:clear');
        }
        $this->call('cache:clear');
        $this->call('route:clear');
    }
}
