<?php

declare(strict_types=1);

namespace PickIt;

use InvalidArgumentException;
use PickIt\Entities\Product;
use PickIt\Requests\BudgetPetitionRequest;
use PickIt\Requests\SimplifiedTransactionRequest;
use PickIt\Requests\TransactionRequest;
use PickIt\Responses\CreateBudgetResponse;
use PickIt\Responses\GetLabelResponse;
use PickIt\Responses\GetMapPointResponse;
use PickIt\Responses\GetShipmentStatusResponse;
use PickIt\Responses\RawResponse;
use PickIt\Responses\StartTransactionResponse;

class PickIt
{
    public const SERVICE_TYPE_STORE_PICKUP = 'SP';
    public const SERVICE_TYPE_PICKIT_POINT = 'PP';
    public const SERVICE_TYPE_LOCKER = 'PL';
    public const SERVICE_TYPE_STOCK = 'ST';

    public const WORKFLOW_DISPATCH = 'dispatch';
    public const WORKFLOW_REFUND = 'refund';
    public const WORKFLOW_RESTOCKING = 'restocking';

    public const OPERATION_TYPE_TO_POINT = 1;
    public const OPERATION_TYPE_TO_HOME = 2;
    public const OPERATION_TYPE_TO_RETAILER = 3;

    public const SLA_STANDARD = 1;
    public const SLA_EXPRESS = 2;
    public const SLA_PRIORITY = 3;
    public const SLA_AGREED_WITH_CLIENT = 4;
    public const SLA_WAREHOUSE = 5;

    public const START_TYPE_RETAILER = 1;
    public const START_TYPE_AVAILABLE_FOR_COLLECTION = 2;
    public const START_TYPE_COURIER = 3;
    public const START_TYPE_REQUESTED_DEVOLUTION = 4;
    public const START_TYPE_PROGRAMMED_DEVOLUTION = 5;

    private const METHOD_GET = 'get';
    private const METHOD_POST = 'post';
    private const METHOD_PUT = 'put';
    private const METHOD_PATCH = 'patch';

    private const HTTP_STATUS_OK = 200;

    private const VALID_SERVICE_TYPES = [
        self::SERVICE_TYPE_STORE_PICKUP,
        self::SERVICE_TYPE_PICKIT_POINT,
        self::SERVICE_TYPE_LOCKER,
        self::SERVICE_TYPE_STOCK
    ];

    private const VALID_OPERATION_TYPES = [
        self::OPERATION_TYPE_TO_POINT,
        self::OPERATION_TYPE_TO_HOME,
        self::OPERATION_TYPE_TO_RETAILER
    ];

    private const VALID_WORKFLOWS = [
        self::WORKFLOW_DISPATCH,
        self::WORKFLOW_REFUND,
        self::WORKFLOW_RESTOCKING,
    ];

    private const VALID_SLAS = [
        self::SLA_STANDARD,
        self::SLA_EXPRESS,
        self::SLA_PRIORITY,
        self::SLA_AGREED_WITH_CLIENT,
        self::SLA_WAREHOUSE
    ];

    private const COUNTRY_DOMAINS = [
        "ar" => [ // just to make things a bit harder
            "sandbox" => 'com.ar',
            "production" => 'net',
        ],
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

    public function __construct(string $apiKey, string $token, string $country, bool $sandBox = true)
    {
        if (!in_array($country, array_keys(self::COUNTRY_DOMAINS))) {
            throw new InvalidArgumentException("Invalid country");
        }

        $this->apiKey = $apiKey;
        $this->token = $token;
        $this->country = $country;
        $this->sandBox = $sandBox;

        $this->domain = $this->buildDomain();
    }

    private function buildDomain(): string
    {
        $url = $this->sandBox ? self::API_SANDBOX_HOST : self::API_HOST;
        $url .= is_array(self::COUNTRY_DOMAINS[$this->country]) ? self::COUNTRY_DOMAINS[$this->country][$this->sandBox ? 'sandbox' : 'production'] : self::COUNTRY_DOMAINS[$this->country];

        return $url;
    }

    public function getShipmentStatus(string $trackingCode): ?GetShipmentStatusResponse
    {
        $response = $this->query('/publicApiV2/tracking/base/' . $trackingCode, self::METHOD_GET);

        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }
        return new GetShipmentStatusResponse($response);
    }

    /**
     * @url https://dev.pickit.net/Metodos.html#Met_GET/apiV2/transaction/{transactionId}/label
     * @param int $transactionId
     * @return GetLabelResponse|null
     */
    public function getLabel(int $transactionId): ?GetLabelResponse
    {
        $response = $this->query('/apiV2/transaction/' . $transactionId . '/label', self::METHOD_GET);

        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }

        return new GetLabelResponse($response);
    }

    public function createTransaction(string $uuid, TransactionRequest $request)
    {
        $this->validateTransactionRequest($request);

        if (empty($uuid)) {
            throw new InvalidArgumentException("uuid is empty");
        }

        $response = $this->query('/apiV2/transaction/' . $uuid, self::METHOD_POST, $request->jsonSerialize());
        pd($request, $response->getRawResponse());
        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }

        return new CreateBudgetResponse($response);
    }

    /**
     * @url https://dev.pickit.net/Metodos.html#Met_POST/apiV2/budget
     * @param BudgetPetitionRequest $request
     * @return CreateBudgetResponse|null
     */
    public function createBudget(BudgetPetitionRequest $request): ?CreateBudgetResponse
    {
        $request->setTokenId($this->token);

        $this->validateBudgetPetitionRequest($request);
        $response = $this->query('/apiV2/budget', self::METHOD_POST, $request->jsonSerialize());
        pd(json_encode($request, JSON_PRETTY_PRINT), $response->getRawResponse());

        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }

        return new CreateBudgetResponse($response);
    }

    /**
     * @url https://dev.pickit.net/Metodos.html#Met_POST/apiV2/transaction
     * @param SimplifiedTransactionRequest $request
     * @return StartTransactionResponse|null
     */
    public function createSimplifiedTransaction(SimplifiedTransactionRequest $request): ?StartTransactionResponse
    {
        $request->getBudgetPetitionRequest()->setTokenId($this->token);

        $this->validateBudgetPetitionRequest($request->getBudgetPetitionRequest());
        $this->validateTransactionRequest($request->getTransactionRequest());
//pd(json_encode($request, JSON_PRETTY_PRINT));
        $response = $this->query('/apiV2/transaction', self::METHOD_POST, $request->jsonSerialize());
        pd($response->getRawResponse());
        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }

        return new StartTransactionResponse($response);
    }

    private function validateBudgetPetitionRequest(BudgetPetitionRequest $request): void
    {
        $requiredFields = [
            "serviceType" => $request->getServiceType(),
            "workflowTag" => $request->getWorkflowTag(),
            "products" => $request->getProducts(),
            "sla" => $request->getSlaId(),
            "customer" => $request->getCustomer(),
        ];

        if (
            in_array($request->getServiceType(), [
            self::SERVICE_TYPE_PICKIT_POINT,
            self::SERVICE_TYPE_LOCKER,
            self::SERVICE_TYPE_STOCK,
            ]) && empty($request->getPointId())
        ) {
            throw new InvalidArgumentException("PointId is mandatory for service type " . $request->getServiceType());
        }

        $this->validateRequiredFields($requiredFields);

        foreach ($request->getProducts() as $product) {
            if (!($product instanceof Product)) {
                throw new InvalidArgumentException("Products field must be a list of Product entities");
            }
        }

        if (
            $request->getOperationType() == self::OPERATION_TYPE_TO_HOME &&
            empty($request->getCustomer()->getAddress())
        ) {
            throw new InvalidArgumentException("Customer address is required when delivering home");
        }

        if (!in_array($request->getOperationType(), self::VALID_OPERATION_TYPES)) {
            throw new InvalidArgumentException("Invalid operationType received " . $request->getOperationType());
        }

        if (!in_array($request->getSlaId(), self::VALID_SLAS)) {
            throw new InvalidArgumentException("Invalid SLA received " . $request->getSlaId());
        }

        if (!in_array($request->getWorkflowTag(), self::VALID_WORKFLOWS)) {
            throw new InvalidArgumentException("Invalid workflow received " . $request->getWorkflowTag());
        }

        if (!in_array($request->getServiceType(), self::VALID_SERVICE_TYPES)) {
            throw new InvalidArgumentException("Invalid serviceType received " . $request->getServiceType());
        }
    }

    private function validateTransactionRequest(TransactionRequest $request): void
    {
        $requiredFields = [
            "firstState" => $request->getFirstState(),
        ];

        switch ($request->getFirstState()) {
            case self::START_TYPE_REQUESTED_DEVOLUTION:
            case self::START_TYPE_PROGRAMMED_DEVOLUTION:
                $requiredFields["deliveryTimeRangeStart"] = $request->getStartTime();
                $requiredFields["deliveryTimeRangeEnd"] = $request->getEndTime();
                break;
            case self::START_TYPE_COURIER:
                $requiredFields["shipmentTrackingCode"] = $request->getShipmentTrackingCode();
                break;
        }

        $this->validateRequiredFields($requiredFields);
    }

    private function validateRequiredFields(array $requiredFields): void
    {
        foreach ($requiredFields as $fieldName => $value) {
            if (empty($value)) {
                throw new InvalidArgumentException($fieldName . " is empty");
            }
        }
    }

    /**
     * @url https://dev.pickit.net/Metodos.html#Met_GET/apiV2/map/point?page={page_number}&perPage={results_per_page}
     * @param int $page
     * @param int $limit
     * @return GetMapPointResponse|null
     */
    public function getMapPoint(int $page, int $limit): ?GetMapPointResponse
    {
        $response = $this->query('/apiV2/map/point', self::METHOD_GET, [
            "page" => $page,
            "perPage" => $limit,
        ]);


        if (empty($response) || $response->getHeaders()["status"] != self::HTTP_STATUS_OK) {
            return null;
        }

        return new GetMapPointResponse($response);
    }

    private function query(string $path, string $method, array $data = [], array $headers = []): ?RawResponse
    {
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
    private function curl(string $url, string $method = "get", array $data = [], array $headers = []): RawResponse
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
