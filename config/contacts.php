<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contact API Sources
    |--------------------------------------------------------------------------
    |
    | Contacts (emails / phone numbers) are fetched from one or more external
    | APIs and stored in the `contacts` table. Duplicate emails are skipped.
    |
    | To add another API in the future, simply append another array below.
    | Each source accepts:
    |
    |   'name'            Unique identifier for this source.
    |   'url'             Endpoint that returns JSON.
    |   'method'          HTTP method: GET or POST.
    |   'api_key'         Optional token sent via 'api_key_header'.
    |   'api_key_header'  Header name for the token (e.g. 'Authorization').
    |   'api_key_prefix'  Optional prefix, e.g. 'Bearer '.
    |   'api_key_query'   Query string parameter name for the token (e.g.
    |                     'api_key'), when the API expects it in the URL.
    |   'data_path'       Dot-notation path to the array of items, or null
    |                     when the root of the response is the array.
    |   'email_field'     Field name of the email in each item.
    |   'phone_field'     Field name of the phone number in each item.
    |   'name_field'      Field name of the name in each item (nullable).
    |   'user_type'       Optional label stored on every contact fetched from
    |                     this source, e.g. 'customer' or 'affiliate'.
    |   'timeout'         Request timeout in seconds.
    |
    */

    'sources' => [
        [
            'name' => 'Identity Search Customer',
            'url' => env('CONTACTS_API_CUSTOMERS_URL', 'https://identitysearch.ai/api/customers-affiliates.php?source=Identity%20Search&user_type=customer'),
            'method' => 'GET',
            'api_key' => env('CONTACTS_API_KEY'),
            'api_key_query' => 'api_key',
            'data_path' => 'data',
            'email_field' => 'email',
            'phone_field' => 'phone',
            'name_field' => 'name',
            'user_type' => 'customer',
            'timeout' => 30,
        ],
        [
            'name' => 'Identity Search Affiliate',
            'url' => env('CONTACTS_API_AFFILIATES_URL', 'https://identitysearch.ai/api/customers-affiliates.php?source=Identity%20Search&user_type=affilliate'),
            'method' => 'GET',
            'api_key' => env('CONTACTS_API_KEY'),
            'api_key_query' => 'api_key',
            'data_path' => 'data',
            'email_field' => 'email',
            'phone_field' => 'phone',
            'name_field' => 'name',
            'user_type' => 'affiliate',
            'timeout' => 30,
        ],
    ],

];
