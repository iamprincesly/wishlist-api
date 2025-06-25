<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to refresh the app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting app refresh...');

        $this->info('Running migration...');
        $this->call('migrate:fresh', ['--seed' => true]);

        $this->info('Clearing logs and caching...');
        $this->call('logs:clear');
        $this->call('optimize');

        $this->info('App refresh successfully.');

        return Command::SUCCESS;
    }
}
