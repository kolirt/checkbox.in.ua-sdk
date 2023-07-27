<?php

namespace Kolirt\Checkbox;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class Checkbox
{

    const SHIFT_STATUS_CREATED = 'CREATED';
    const SHIFT_STATUS_OPENING = 'OPENING';
    const SHIFT_STATUS_OPENED  = 'OPENED';
    const SHIFT_STATUS_CLOSING = 'CLOSING';
    const SHIFT_STATUS_CLOSED  = 'CLOSED';


    private $composer_config;

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

        $this->license_key = $options['license_key'];

        $this->composer_config = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

        $this->client = new Client([
            'base_uri' => $this->production ? $this->api : $this->api_dev,
            'headers'  => [
                //                'Accept'           => 'application/json',
                'X-Client-Name'    => $this->composer_config->name,
                'X-Client-Version' => $this->composer_config->version,
                'X-License-Key'    => $this->license_key
            ]
        ]);
    }

    /**
     * Вхід користувача (касира) за допомогою логіна та паролю
     *
     * @return object
     */
    public function singInCashier(string $login, string $password)
    {
        return $this->call(function () use ($login, $password) {
            $request = $this->client->post('/api/v1/cashier/signin', [
                'json' => [
                    'login'    => $login,
                    'password' => $password,
                ]
            ]);

            $response = $this->prepareResponse($request);

            if ($response->ok) {
                $this->access_token = $response->data->access_token;
            }

            return $response;
        });
    }

    /**
     * Вхід користувача (касира) за допомогою КЕП. Необхідно для касирів з типом підпису "API"
     *
     * @param $signature
     * @return object
     */
    public function singInSignatureCashier(string $signature)
    {
        return $this->call(function () use ($signature) {
            $request = $this->client->post('/api/v1/cashier/signinSignature', [
                'json' => [
                    'signature' => $signature,
                ]
            ]);

            $response = $this->prepareResponse($request);

            if ($response->ok) {
                $this->access_token = $response->data->access_token;
            }

            return $response;
        });
    }

    /**
     * Вхід користувача (касира) за допомогою пін-коду. Необхідно для касових реєстраторів типу "AGENT"
     *
     * @param $pin_code
     * @return object
     */
    public function singInPinCodeCashier(string $pin_code)
    {
        return $this->call(function () use ($pin_code) {
            $request = $this->client->post('/api/v1/cashier/signinPinCode', [
                'json' => [
                    'pin_code' => $pin_code,
                ]
            ]);

            $response = $this->prepareResponse($request);

            if ($response->ok) {
                $this->access_token = $response->data->access_token;
            }

            return $response;
        });
    }

    /**
     * Завершення сесії користувача (касира) з поточним токеном доступу
     *
     * @return object
     */
    public function signOutCashier()
    {
        return $this->call(function () {
            $request = $this->client->post('/api/v1/cashier/signout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ]
            ]);

            $response = $this->prepareResponse($request);

            if ($response->ok) {
                $this->access_token = null;
            }

            return $response;
        });
    }

    /**
     * Отримання інформації про поточного користувача (касира)
     *
     * @return object
     */
    public function getCashier()
    {
        return $this->call(function () {
            $request = $this->client->get('/api/v1/cashier/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ]
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання інформації про активну зміну користувача (касира)
     *
     * @return object
     */
    public function getCashierShift()
    {
        return $this->call(function () {
            $request = $this->client->get('/api/v1/cashier/shift', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ]
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання змін поточного касира
     *
     * @return object
     */
    public function getShifts(array $statuses = [], bool $desc = false, int $limit = 25, int $offset = 0)
    {
        return $this->call(function () use ($statuses, $desc, $limit, $offset) {
            $request = $this->client->get('/api/v1/shifts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
                'query'   => [
                    'statuses' => $statuses,
                    'desc'     => $desc,
                    'limit'    => $limit,
                    'offset'   => $offset
                ]
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Відкриття нової зміни касиром.
     *
     * @return object
     */
    public function createShift()
    {
        return $this->call(function () {
            $request = $this->client->post('/api/v1/shifts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ]
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання інформації про зміну
     *
     * @param $shift_id
     * @return object
     */
    public function getShift(string $shift_id)
    {
        return $this->call(function () use ($shift_id) {
            $request = $this->client->get('​/api​/v1​/shifts​/' . $shift_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ]
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Створення Z-Звіту та закриття поточної зміни користувачем (касиром).
     *
     * @return object
     */
    public function closeShift()
    {
        return $this->call(function () {
            $request = $this->client->post('/api/v1/shifts/close', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
                'json'    => [],
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання списку чеків в рамках поточної зміни або за параметрами фільтрів
     * Пошук за порядковим та фіскальним номерами одночасно неможливий.
     * Якщо у касира немає активної зміни виконується пошук за організацією, у іншому випадку пошук виконується за кассою.
     *
     * @param string|null $fiscal_code
     * @param string|null $serial
     * @param bool $desc
     * @param int $limit
     * @param int $offset
     * @return object
     */
    public function getReceipts(string $fiscal_code = null, string $serial = null, bool $desc = false, int $limit = 25, int $offset = 0)
    {
        return $this->call(function () use ($fiscal_code, $serial, $desc, $limit, $offset) {
            $request = $this->client->get('/api/v1/receipts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
                'query'   => [
                    'fiscal_code' => $fiscal_code,
                    'serial'      => $serial,
                    'desc'        => $desc,
                    'limit'       => $limit,
                    'offset'      => $offset
                ]
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання інформації про чек.
     *
     * @param string $receipt_id
     * @return object
     */
    public function getReceipt(string $receipt_id)
    {
        return $this->call(function () use ($receipt_id) {
            $request = $this->client->get('/api/v1/receipts/' . $receipt_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Створення чеку продажу/повернення, його фіскалізація та доставка клієнту по email.
     *
     * @param Receipt $receipt
     * @return object
     */
    public function createReceipt(Receipt $receipt)
    {
        return $this->call(function () use ($receipt) {
            $request = $this->client->post('/api/v1/receipts/sell', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
                'json'    => $receipt->render()
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * @return object
     */
    public function callApi($uri, $data)
    {
        return $this->call(function () use ($uri, $data) {
            $request = $this->client->post($uri, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
                'json'    => $data
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання HTML представлення чеку.
     *
     * @param string $receipt_id
     * @return object
     */
    public function getReceiptHtml(string $receipt_id)
    {
        return $this->call(function () use ($receipt_id) {
            $request = $this->client->get('/api/v1/receipts/' . $receipt_id . '/html', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання PDF представлення чеку.
     *
     * @param string $receipt_id
     * @return object
     */
    public function getReceiptPdf(string $receipt_id)
    {
        return $this->call(function () use ($receipt_id) {
            $request = $this->client->get('/api/v1/receipts/' . $receipt_id . '/pdf', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання текстового представлення чека для термопринтеру.
     *
     * @param string $receipt_id
     * @return object
     */
    public function getReceiptText(string $receipt_id)
    {
        return $this->call(function () use ($receipt_id) {
            $request = $this->client->get('/api/v1/receipts/' . $receipt_id . '/text', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
            ]);

            return $this->prepareResponse($request);
        });
    }

    /**
     * Отримання зображення QR-коду чеку.
     *
     * @param string $receipt_id
     * @return object
     */
    public function getReceiptQrcode(string $receipt_id)
    {
        return $this->call(function () use ($receipt_id) {
            $request = $this->client->get('/api/v1/receipts/' . $receipt_id . '/qrcode', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
            ]);

            return $this->prepareResponse($request);
        });
    }

    public function getGoods()
    {
        return $this->call(function () {
            $request = $this->client->get('/api/v1/goods', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->access_token,
                ],
            ]);

            return $this->prepareResponse($request);
        });
    }

    private function prepareResponse(Response $response)
    {
        $data = $response->getBody()->getContents();
        return (object)[
            'ok'          => $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299,
            'status_code' => $response->getStatusCode(),
            'response '   => $response,
            'data'        => is_json($data) ? json_decode($data) : $data
        ];
    }

    private function call($function)
    {
        try {
            return $function();
        } catch (\Exception $exception) {
            $response = $exception->getResponse();
            return $this->prepareResponse($response);
        }
    }

}
