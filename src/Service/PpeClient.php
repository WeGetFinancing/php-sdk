<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class PpeClient
{
    public const MERCHANT_TOKEN_REPLACE = '%MERCHANT_TOKEN%';
    public const URL_TEST_PPE_PROD = 'https://partner.wegetfinancing.com/integration/%MERCHANT_TOKEN%/ppe';
    public const URL_TEST_PPE_SANDBOX = 'https://partner.sandbox.wegetfinancing.com/integration/%MERCHANT_TOKEN%/ppe';
    public const TEST_ERROR_RESPONSE = 'error';
    public const TEST_EMPTY_RESPONSE = 'empty';
    public const TEST_SUCCESS_RESPONSE = 'success';
    public const EMPTY_LENDERS_MESSAGE =
        "The merchant account you've selected isn't properly configured for PPE use yet. " .
        "Please reach out to our support team to get your account set up correctly.";

    public function __construct(
        protected string $merchantToken,
        protected bool $isProd,
        protected ClientInterface $client
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param  string $merchantToken
     * @param  bool $isProd
     * @return PpeClient
     */
    public static function make(string $merchantToken, bool $isProd): PpeClient
    {
        return new PpeClient(
            $merchantToken,
            $isProd,
            new Client()
        );
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     * @return array<string, string>
     */
    public function testPpe(): array
    {
        $response = $this->client->request('GET', $this->getTestPpePath());
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);
        $error = json_last_error();
        if (JSON_ERROR_NONE !== $error) {
            throw new Exception(
                self::class . "::testPpe() json error: " . json_last_error_msg(),
                $error
            );
        }

        if (true === array_key_exists(self::TEST_ERROR_RESPONSE, $data)) {
            return [
                'status' => self::TEST_ERROR_RESPONSE,
                'message' => $data[self::TEST_ERROR_RESPONSE],
            ];
        }

        if (true === array_key_exists(0, $data) && true === array_key_exists('max_amount', $data[0])) {
            return [
                'status' => self::TEST_SUCCESS_RESPONSE,
            ];
        }

        return [
            'status' => self::TEST_EMPTY_RESPONSE,
            'message' => self::EMPTY_LENDERS_MESSAGE,
        ];
    }

    protected function getTestPpePath(): string
    {
        return str_replace(
            self::MERCHANT_TOKEN_REPLACE,
            $this->merchantToken,
            (true === $this->isProd) ? self::URL_TEST_PPE_PROD : self::URL_TEST_PPE_SANDBOX
        );
    }
}
