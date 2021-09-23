<?php

declare(strict_types=1);

namespace PickIt\Requests;

class SimplifiedTransactionRequest implements \JsonSerializable
{

    private BudgetPetitionRequest $budgetPetitionRequest;
    private TransactionRequest $transactionRequest;

    public function __construct(
        BudgetPetitionRequest $budgetPetitionRequest,
        TransactionRequest $transactionRequest
    ) {

        $this->budgetPetitionRequest = $budgetPetitionRequest;
        $this->transactionRequest = $transactionRequest;
    }

    public function getBudgetPetitionRequest(): BudgetPetitionRequest
    {
        return $this->budgetPetitionRequest;
    }

    public function getTransactionRequest(): TransactionRequest
    {
        return $this->transactionRequest;
    }

    public function jsonSerialize(): array
    {
        $fields = $this->transactionRequest->jsonSerialize();
        $fields["budgetPetition"] = $this->budgetPetitionRequest->jsonSerialize();

        return $fields;
    }
}
