<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PickIt\Requests\BudgetPetitionRequest;
use PickIt\PickItClient;
use PickIt\Entities\Person;
use PickIt\Entities\Address;
use PickIt\Entities\Product;
use PickIt\Entities\Measure;

final class BudgetTest extends TestCase
{
    private PickItClient $pickItClient;

    public function setup(): void
    {
        $this->pickItClient = new PickItClient("", "", "mx", true);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenProductListIsEmpty(array $products, Person $customer): void
    {
        $products = [];
        $request = new BudgetPetitionRequest(
            PickItClient::SERVICE_TYPE_PICKIT_POINT,
            PickItClient::WORKFLOW_DISPATCH,
            PickItClient::OPERATION_TYPE_TO_HOME,
            $products,
            PickItClient::SLA_STANDARD,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("products is empty");

        $this->pickItClient->createBudget($request);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenOperationTypeIsToPointAndPointIsNotSet(array $products, Person $customer): void
    {
        $request = new BudgetPetitionRequest(
            PickItClient::SERVICE_TYPE_PICKIT_POINT,
            PickItClient::WORKFLOW_DISPATCH,
            PickItClient::OPERATION_TYPE_TO_POINT,
            $products,
            PickItClient::SLA_STANDARD,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("PointId is mandatory");

        $this->pickItClient->createBudget($request);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenOperationTypeIsToHomeAndThereIsNoCustomerAddress(array $products, Person $customer): void
    {
        $customer = (new Person("Marta", "Fernandez"))
            ->setPid("345345")
            ->setEmail("wolool@gmail.com");

        $request = new BudgetPetitionRequest(
            PickItClient::SERVICE_TYPE_PICKIT_POINT,
            PickItClient::WORKFLOW_DISPATCH,
            PickItClient::OPERATION_TYPE_TO_HOME,
            $products,
            PickItClient::SLA_STANDARD,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Customer address is required");

        $this->pickItClient->createBudget($request);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenCustomerEmailIsInvalid(array $products, Person $customer): void
    {
        $customer->setEmail("woloolgmail.com");

        $request = new BudgetPetitionRequest(
            PickItClient::SERVICE_TYPE_PICKIT_POINT,
            PickItClient::WORKFLOW_DISPATCH,
            PickItClient::OPERATION_TYPE_TO_HOME,
            $products,
            PickItClient::SLA_STANDARD,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid email");

        $this->pickItClient->createBudget($request);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenSLAIsInvalid(array $products, Person $customer): void
    {
        $request = new BudgetPetitionRequest(
            PickItClient::SERVICE_TYPE_PICKIT_POINT,
            PickItClient::WORKFLOW_DISPATCH,
            PickItClient::OPERATION_TYPE_TO_HOME,
            $products,
            9999999,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid SLA received");

        $this->pickItClient->createBudget($request);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenServiceTypeIsInvalid(array $products, Person $customer): void
    {
        $request = new BudgetPetitionRequest(
            "INVALID_SERVICE_TYPE",
            PickItClient::WORKFLOW_DISPATCH,
            PickItClient::OPERATION_TYPE_TO_HOME,
            $products,
            PickItClient::SLA_STANDARD,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid serviceType received");

        $this->pickItClient->createBudget($request);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenOperationTypeIsInvalid(array $products, Person $customer): void
    {
        $request = new BudgetPetitionRequest(
            PickItClient::SERVICE_TYPE_PICKIT_POINT,
            'INVALID_WORKFLOW',
            PickItClient::OPERATION_TYPE_TO_HOME,
            $products,
            PickItClient::SLA_STANDARD,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid workflow received");

        $this->pickItClient->createBudget($request);
    }

    /**
     * @dataProvider getValidRequestDetails
     */
    public function testThrowAnExceptionWhenWorkflowIsInvalid(array $products, Person $customer): void
    {
        $request = new BudgetPetitionRequest(
            PickItClient::SERVICE_TYPE_PICKIT_POINT,
            PickItClient::WORKFLOW_DISPATCH,
            9999999,
            $products,
            PickItClient::SLA_STANDARD,
            $customer
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid operationType received");

        $this->pickItClient->createBudget($request);
    }

    public function getValidRequestDetails(): array
    {
        return [
            [
                [
                    new Product(
                        "Botines dulces",
                        new Measure(55, Measure::UNIT_G),
                        new Measure(150, Measure::UNIT_CM),
                        new Measure(30, Measure::UNIT_CM),
                        new Measure(30, Measure::UNIT_CM),
                        1
                    )
                ],
                (new Person("Marta", "Fernandez"))
                    ->setPid("345345")
                    ->setEmail("wolool@gmail.com")
                    ->setAddress(new Address(
                        "11320",
                        "LAGO COMO 21",
                        "ciudad de México",
                        "México"
                    ))
            ]
        ];
    }
}
