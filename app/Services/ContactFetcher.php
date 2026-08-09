<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactFetcher
{
    /**
     * Loop over every configured API source and sync its contacts.
     * Returns a per-source summary of inserted/skipped items.
     *
     * @return array
     */
    public function fetchAll()
    {
        $summary = [];

        foreach (config('contacts.sources', []) as $source) {
            $summary[$source['name'] ?? 'unknown'] = $this->fetchSource($source);
        }

        return $summary;
    }

    /**
     * Fetch and store the contacts of a single source.
     *
     * @param array $source
     * @return array
     */
    protected function fetchSource(array $source)
    {
        $url = $source['url'] ?? null;

        if (empty($url)) {
            return ['inserted' => 0, 'skipped' => 0, 'error' => 'No URL configured for this source.'];
        }

        $response = $this->request($source);

        if ($response->failed()) {
            $error = "Contact sync failed ({$source['name']}): HTTP {$response->status()}";

            Log::error($error);

            return ['inserted' => 0, 'skipped' => 0, 'error' => $error];
        }

        $payload = $response->json();

        $items = $this->resolveItems($payload, $source['data_path'] ?? null);

        if (!is_array($items) || count($items) === 0) {
            return ['inserted' => 0, 'skipped' => 0];
        }

        return $this->store($items, $source);
    }

    /**
     * Perform the HTTP request for a source.
     *
     * @param array $source
     * @return \Illuminate\Http\Client\Response
     */
    protected function request(array $source)
    {
        $headers = [];
        $apiKey = $source['api_key'] ?? null;

        if (!empty($apiKey) && !empty($source['api_key_header'])) {
            $headers[$source['api_key_header']] = ($source['api_key_prefix'] ?? '') . $apiKey;
        }

        $url = $source['url'] ?? null;

        if (!empty($apiKey) && !empty($source['api_key_query'])) {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url .= $separator . $source['api_key_query'] . '=' . urlencode($apiKey);
        }

        $client = Http::withHeaders($headers)->timeout($source['timeout'] ?? 30);

        $method = strtolower($source['method'] ?? 'get');

        return $method === 'post' ? $client->post($url) : $client->get($url);
    }

    /**
     * Resolve the array of items from the response payload.
     *
     * @param mixed $payload
     * @param string|null $dataPath
     * @return array
     */
    protected function resolveItems($payload, $dataPath)
    {
        $items = $dataPath ? Arr::get($payload, $dataPath) : $payload;

        if (!is_array($items)) {
            return [];
        }

        return $items;
    }

    /**
     * Normalize each item into [name, email, phone, source] and bulk-insert
     * them, skipping duplicate emails. Data is written in chunks so memory
     * usage stays low even for very large API responses.
     *
     * @param array $items
     * @param array $source
     * @return array
     */
    protected function store(array $items, array $source)
    {
        $emailField = $source['email_field'] ?? 'email';
        $phoneField = $source['phone_field'] ?? 'phone';
        $nameField = $source['name_field'] ?? 'name';
        $sourceName = $source['name'] ?? null;
        $userType = $source['user_type'] ?? null;

        $rows = [];
        $seenEmails = [];
        $seenPhones = [];
        $skipped = 0;

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $email = $this->normalizeEmail(Arr::get($item, $emailField));
            $phone = $this->normalizePhone(Arr::get($item, $phoneField));
            $name = Arr::get($item, $nameField);

            if (empty($email) && empty($phone)) {
                $skipped++;
                continue;
            }

            // Skip duplicate emails and duplicate phone numbers within this run.
            $emailKey = $email ? strtolower($email) : null;
            $phoneKey = $phone ? $phone : null;

            if ($emailKey && isset($seenEmails[$emailKey])) {
                $skipped++;
                continue;
            }

            if ($phoneKey && isset($seenPhones[$phoneKey])) {
                $skipped++;
                continue;
            }

            $seenEmails[$emailKey] = true;
            $seenPhones[$phoneKey] = true;

            $rows[] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'source' => $sourceName,
                'user_type' => $userType,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Rows without an email can't rely on the unique index, so exclude any
        // phone numbers that are already stored in the database.
        $rows = $this->filterExistingPhoneOnly($rows);

        // Insert in chunks. The unique index on `email` makes insertOrIgnore
        // silently drop any email that already exists in the database.
        foreach (array_chunk($rows, 500) as $chunk) {
            Contact::insertOrIgnore($chunk);
        }

        return [
            'inserted' => count($rows),
            'skipped' => $skipped,
        ];
    }

    /**
     * Remove phone-only rows whose phone number already exists in the database.
     *
     * @param array $rows
     * @return array
     */
    protected function filterExistingPhoneOnly(array $rows)
    {
        $phoneOnly = array_filter($rows, function ($row) {
            return empty($row['email']) && !empty($row['phone']);
        });

        if (count($phoneOnly) === 0) {
            return $rows;
        }

        $phones = array_column($phoneOnly, 'phone');
        $existing = Contact::whereIn('phone', $phones)->pluck('phone')->flip();

        foreach ($rows as $key => $row) {
            if (empty($row['email']) && !empty($row['phone']) && isset($existing[$row['phone']])) {
                unset($rows[$key]);
            }
        }

        return array_values($rows);
    }

    /**
     * @param mixed $email
     * @return string|null
     */
    protected function normalizeEmail($email)
    {
        $email = trim((string) $email);

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return strtolower($email);
    }

    /**
     * @param mixed $phone
     * @return string|null
     */
    protected function normalizePhone($phone)
    {
        $phone = trim((string) $phone);

        if (empty($phone)) {
            return null;
        }

        return preg_replace('/[^0-9+]/', '', $phone);
    }
}
