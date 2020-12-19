<?php

namespace Kolirt\Checkbox;

use GuzzleHttp\Client;

class Checkbox
{

    private $client;
    private $api = 'https://api.checkbox.in.ua/';
    private $api_dev = 'https://dev-api.checkbox.in.ua/';

    private $production = false;
    private $login;
    private $password;
    private $license_key;

    private $access_token;

    public function __construct($options = [])
    {
        if (!empty($options['production'])) {
            $this->production = $options['production'];
        }

        $this->login = $options['login'];
        $this->password = $options['password'];
        $this->license_key = $options['license_key'];

        $this->client = new Client([
            'base_uri' => $this->production ? $this->api : $this->api_dev,
            'headers'  => [
                'Accept' => 'application/json'
            ]
        ]);
    }

    public function singInCashier()
    {
        $request = $this->client->post('/api/v1/cashier/signin', [
            'json' => [
                'login'    => $this->login,
                'password' => $this->password,
            ]
        ]);

        if ($request->getStatusCode() == 200) {
            $response = json_decode($request->getBody()->getContents());
            $this->access_token = $response->access_token;
            return true;
        }

        return false;
    }

    public function signOutCashier()
    {
        $request = $this->client->post('/api/v1/cashier/signout', [
            'json'    => [],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->access_token
            ]
        ]);

        if ($request->getStatusCode() == 205) {
            $this->access_token = null;
            return true;
        }

        return false;
    }

    public function openShifts()
    {
        try {
            $request = $this->client->post('/api/v1/shifts', [
                'json'    => [],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'X-License-Key' => $this->license_key
                ]
            ]);

            dd($request->getStatusCode());
        } catch (\Exception $exception) {
            if ($exception->getResponse()->getStatusCode() == 400) {
                return true;
            }
            return [
                'ok'       => false,
                'response' => $exception->getResponse()
            ];
        }

        dd($request);
    }

}
