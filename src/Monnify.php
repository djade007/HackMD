<?php namespace HMO;

use GuzzleHttp\Client;

class Monnify
{
    protected $client;
    protected $token;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://sandbox.monnify.com/api/v1/'
        ]);
    }

    protected function auth()
    {
        if (!$this->token)
            $this->login(); // get and set token if it doesn't exists

        return ['Authorization' => 'Bearer ' . $this->token];
    }

    /**
     * @throws \Exception
     */
    public function login()
    {
        try {
            $res = $this->client->post('auth/login', [
                'auth' => [
                    getenv('MONNIFY_USERNAME'), getenv('MONNIFY_PASSWORD')
                ]
            ]);
            $body = json_decode($res->getBody());
        } catch (\Exception $exception) {
            throw new \Exception('Failed to Login');
        }
        if ($body->responseMessage != 'success') {
            throw new \Exception('Failed to Login');
        }

        $this->token = $body->responseBody->accessToken;

        return true;
    }

    public function reserveAccount($ref, $email, $name = "Test Reserved Account")
    {
        $data = [
            "accountReference" => $ref,
            "accountName" => $name,
            "customerEmail" => $email,
            "currencyCode" => "NGN",
            "contractCode" => "2957982769",
        ];

        $res = $this->client->post('bank-transfer/reserved-accounts', [
            'headers' => $this->auth(),
            'json' => $data
        ]);
        $body = json_decode($res->getBody());
        return $body->responseBody;
    }

    public function deactivateAccount($account_number) {
        $res = $this->client->delete('bank-transfer/reserved-accounts/'.$account_number, [
            'headers' => $this->auth()
        ]);
        $body = json_decode($res->getBody());
        return $body->responseBody;
    }

    public function transactions($ref) {
        $res = $this->client->get('bank-transfer/reserved-accounts/transactions', [
            'headers' => $this->auth(),
            'query' => [
                'accountReference' => $ref,
                'page' => 0,
                'size' => 10
            ]
        ]);
        $body = json_decode($res->getBody());
        return $body->responseBody;
    }
}
