<?php

namespace App\Jobs;

use App\Services\ContactFetcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class FetchContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [30, 60];

    public $timeout = 120;

    public function middleware()
    {
        return [(new WithoutOverlapping('contacts-fetch'))->releaseAfter(60)->expireAfter(300)];
    }

    public function handle(ContactFetcher $fetcher)
    {
        Cache::put('contacts_fetch_in_progress', true, now()->addMinutes(30));

        $summary = $fetcher->fetchAll();

        Cache::put('contacts_fetch_result', [
            'success' => true,
            'summary' => $summary,
            'finished_at' => now()->toDateTimeString(),
        ], now()->addHours(24));

        Cache::forget('contacts_fetch_in_progress');
    }

    public function failed(Throwable $e)
    {
        Cache::put('contacts_fetch_result', [
            'success' => false,
            'error' => $e->getMessage(),
            'finished_at' => now()->toDateTimeString(),
        ], now()->addHours(24));

        Cache::forget('contacts_fetch_in_progress');

        Log::error('Contact fetch job failed: ' . $e->getMessage());
    }
}
