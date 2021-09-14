<?php

namespace PickIt;

use \InvalidArgumentException;
use PickIt\Responses\GetLabelResponse;
use PickIt\Responses\GetMapPointResponse;
use PickIt\Responses\RawResponse;

class PickIt
{
    private const METHOD_GET = 'get';
    private const METHOD_POST = 'post';
    private const METHOD_PUT = 'put';
    private const METHOD_PATCH = 'patch';

    private const HTTP_STATUS_OK = 200;

    private const COUNTRY_DOMAINS = [
        "ar" => 'com.ar',
        "uy" => 'com.uy',
        "mx" => 'com.mx',
        "co" => 'com.co',
        "cl" => 'cl',
    ];

    private const API_HOST = "https://api.pickit.";
    private const API_SANDBOX_HOST = "https://api.uat.pickit.";

    private string $apiKey;
    private string $token;
    private string $country;
    private string $domain;
    private bool $sandBox;

    private array $lastRequestHeaders = [];

    public function __construct (string $apiKey, string $token, string $country, bool $sandBox = true) {

        if (!in_array($country, array_keys(self::COUNTRY_DOMAINS))) {
            throw new InvalidArgumentException("Invalid country");
        }

        $this->apiKey = $apiKey;
        $this->token = $token;
        $this->country = $country;
        $this->sandBox = $sandBox;

        $this->domain = $this->buildDomain();
    }

    private function buildDomain () : string {
        $url = $this->sandBox ? self::API_SANDBOX_HOST : self::API_HOST;
        $url .= self::COUNTRY_DOMAINS[$this->country];

        return $url;
    }

    public function getLabel (int $transactionId) : ?GetLabelResponse {
        $response = $this->query('/apiV2/transaction/' . $transactionId . '/label', self::METHOD_GET);

        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }

        return new GetLabelResponse($response);
    }

    public function getMapPoint (int $page, int $limit) : ?GetMapPointResponse {
        $response = $this->query('/apiV2/map/point', self::METHOD_GET, [
            "page" => $page,
            "perPage" => $limit,
        ]);


        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }

        return new GetMapPointResponse($response);
    }

    private function query (string $path, string $method, array $data = [], array $headers = []) : ?RawResponse {
        $headers = array_merge($headers, [
            "Content-Type" => "application/json",
            "apiKey" => $this->apiKey,
            "token" => $this->token
        ]);

        return $this->curl($this->domain . $path, $method, $data, $headers);
    }

    /**
     * Sends curls queries
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return mixed
     * @throws \Exception if received url is invalid
     */
    private function curl(string $url, string $method = "get", array $data = [], array $headers = []) : RawResponse
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception("Invalid URL received: " . $url);
        }

        $ch = curl_init($url);

        switch ($method) {
            case self::METHOD_POST:
                curl_setopt($ch, CURLOPT_POST, true);
                if (sizeof($data) > 0) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case self::METHOD_PUT:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                if (sizeof($data) > 0) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                }
                break;
            case self::METHOD_PATCH:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                if (sizeof($data) > 0) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            default:
                if (sizeof($data) > 0) {
                    $url = $url . "?" . http_build_query($data);
                }
                break;
        }

        $this->lastRequestHeaders = [];
        curl_setopt(
            $ch,
            CURLOPT_HEADERFUNCTION,
            function ($curl, $header) {
                $len = strlen($header);

                if (strpos($header, "HTTP/1.1 ") !== false) {
                    $this->lastRequestHeaders["status"] = (int)explode("HTTP/1.1 ", $header)[1];
                }

                $header = explode(':', $header, 2);
                if (count($header) < 2) { // ignore invalid headers
                    return $len;
                }

                $this->lastRequestHeaders[strtolower(trim($header[0]))][] = trim($header[1]);

                return $len;
            }
        );

        // setup headers
        $headerList = [];
        foreach ($headers as $k => $v) {
            $headerList[] = $k . ": " . $v;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerList);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);

        return new RawResponse($result, $this->lastRequestHeaders);
    }
}