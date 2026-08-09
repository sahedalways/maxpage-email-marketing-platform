<?php

namespace App\Console\Commands;

use App\Services\ContactFetcher;
use Illuminate\Console\Command;

class FetchContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch emails and phone numbers from the configured contact APIs and store them in the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ContactFetcher $fetcher)
    {
        $summary = $fetcher->fetchAll();

        foreach ($summary as $source => $stats) {
            $this->info("{$source}: inserted={$stats['inserted']} skipped={$stats['skipped']}" . (isset($stats['error']) ? " error={$stats['error']}" : ''));
        }

        return 0;
    }
}
