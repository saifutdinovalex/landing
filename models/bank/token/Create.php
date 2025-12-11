<?php

namespace landing\models\bank\token;

use Yii;
use landing\models\bank\receipt\Create AS Receipt;

class Create
{
    public function __construct(private IInvoice $invoice = null)
    {
        $this->build();
    }

    /**
     * @var string|null
     */
    private ?string $result = null;

    /**
     * @return string|null
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @var string|null
     */
    private ?string $error = null;

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @var string|null
     */
    private ?string $status = null;

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
    private ?object $response;
    /**
     * @return void
     */
    private function build(): void
    {
        try {
            $client = Yii::$app->yookassa->getBase();
            $this->response = $client->createPayment(
                [
                    'amount' => [
                        'value' => (string) $this->invoice->getAmount(),
                        'currency' => 'RUB',
                    ],
                    'confirmation' => [
                        'type' => 'embedded',
                        'locale' => 'ru_RU',
                        'return_url' => $this->invoice->getUrlSuccess(),
                    ],
                    'capture' => true,
                    'description' => \Yii::t('error', 'Invoice №') . $this->invoice->getNumber(),
                    'metadata' => [
                        'invoice_id' => $this->invoice->getId(),
                        'hash' => $this->invoice->getToken(),
                    ],
                    'receipt' => $this->getReceipt(),
                ],
                uniqid('', true)
            );

            $this->result = $this->response
                ->getConfirmation()
                ->getConfirmationToken();

            if ($this->result) {
                $this->status = $this->response->getStatus();
                $this->id = $this->response->getId();
            }

        } catch (\Throwable $e) {
            Yii::error($e->getMessage());
            $this->error = $this->response->getCancellationDetails()->getReason();
        }
    }

    /**
     * @return array
     */
    private function getReceipt(): array
    {
        return (new Receipt($this->invoice))->getResult();
    }
}