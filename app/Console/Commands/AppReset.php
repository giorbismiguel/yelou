<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate & Seed the App (Reset)';

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
        config(['debugbar.enabled' => false]);

        $this->call('app:clear');

        // Migrations & Seeds
        $this->call('migrate:fresh');

        $this->call('db:seed');
    }
}
