<?php

Route::get('/', function () {
    return view('welcome');
});

/**
 * - Client Server này sẽ chạy trên port 8001
 * - API Server kia sẽ chạy trên port 8000
 * - 'client_id', 'client_secret': Tạo ở bên API Server bằng lệnh [php artisan passport:client]
        Which user ID should the client be assigned to?:
        > 1

        What should we name the client?:
        > Name of Client

        Where should we redirect the request after authorization? [http://localhost/auth/callback]:
        > http://127.0.0.1:8001/callback
 */

Route::get('/redirect', function () {

    $query = http_build_query([
        'client_id' => '4',
        'redirect_uri' => 'http://127.0.0.1:8001/callback',
        'response_type' => 'code',
        'scope' => ''
    ]);

    return redirect('http://127.0.0.1:8000/oauth/authorize?'.$query);
});

Route::get('/callback', function (Illuminate\Http\Request $request) {
    $http = new \GuzzleHttp\Client;

    $response = $http->post('http://127.0.0.1:8000/oauth/token', [
        'form_params' => [
            'client_id' => '4',
            'client_secret' => '4qgpKHlgOvKvWAjfBwhxLl9do8oJfW4xC00o7Wer',
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://127.0.0.1:8001/callback',
            'code' => $request->code,
        ],
    ]);
    return json_decode((string) $response->getBody(), true);
});