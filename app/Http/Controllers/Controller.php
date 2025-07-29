<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function requestData($url, $method = 'GET', $data = null)
    {
        $option = [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ];

        if ($data != null) {
            $option['form_params'] = $data;
        }

        $client = new Client();
        $res = $client->request($method, $url, $option);
        $stream = $res->getBody()->getContents();

        $hasil = json_decode($stream);
        return $hasil;
    }
}
