<?php

namespace models\bank;

use Exception;
use helpers\Hash;
use Yii;
use models\AbstractModelBase;
use yii\helpers\ArrayHelper;

class BasePayment extends AbstractModelBase
{
    public ?string $name = null;
    public ?string $surname = null;
    public string $email;
    public ?string $utm_source = null;
    public ?string $utm_medium = null;
    public ?string $utm_campaign = null;
    public ?string $utm_content = null;
    public ?string $utm_term = null;
    public ?string $utm_param = null;
    public string $token;

    protected ?string $confirmation_token = null;
    protected ?int $invoice_id = null;
    protected ?string $invoice_hash = null;
    protected int $promo_id = 0;

    protected object $create_utm;
    protected object $create_invoice;
    protected object $create_payment;
    protected object $read_promo;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return ArrayHelper::merge(parent::rules(), [
            [['email', 'token'], 'required'],
            [['email', 'promocode', 'name', 'surname'], 'trim'],
            [['name', 'surname'], 'string', 'length' => [1, 255]],
            [
                'email',
                'email',
                'enableIDN' => true,
                'message' => Yii::t('error', 'Email is incorrect.')
            ],
            [
                [
                    'utm_source',
                    'utm_medium',
                    'utm_campaign',
                    'utm_content',
                    'utm_term',
                    'utm_param',
                ],
                'default',
                'value' => null,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): bool
    {
        if (parent::execute()) {
            try {
                $this->createInvoice();
                $this->createUtm();
                $this->paymentCreate();
                $this->setDataResponse([
                    'confirmation_token' => $this->confirmation_token,
                    'invoice_id' => $this->invoice_hash,
                ]);

                return true;
            } catch (Exception $e) {
                $this->addError('error', $e->getMessage());
                $this->sendError();
            }
        }

        return false;
    }

    /**
     * Create invoice
     */
    protected function createInvoice(): void
    {
        $object = $this->create_invoice;
        $object->setName($this->name);
        $object->setSurname($this->surname);
        $object->setEmail($this->email);
        $object->setPromoId($this->promo_id);
        $object->build();
        $result = $object->getResult();

        if ($result) {
            $this->invoice_id = $object->getModelAr()->id;
            $this->invoice_hash = Hash::get(
                $this->invoice_id,
                $object->getModelAr()->token_payment
            );
        } else {
            $this->addError('error', $object->getErrors());
            $this->sendError();
        }
    }

    /**
     * Create payment yookassa
     */
    protected function paymentCreate(): void
    {
        $object = $this->create_payment;
        $object->setAttributes(['invoice_id' => $this->invoice_id]);

        if ($object->validate() && $object->execute()) {
            $result = $object->getResponse();

            if (isset($result['status']) && $result['status'] == 200) {
                $this->confirmation_token = $result['data']['confirmation_token'];
            } else {
                $this->addError('error', $result['message']);
                $this->sendError();
            }
        } else {
            $this->addError('error', $object->getError());
            $this->sendError();
        }
    }

    /**
     * Create utm metriks
     */
    protected function createUtm(): void
    {
        $object = $this->create_utm;
        $object->setAttributes([
            'utm_param' => $this->utm_param,
            'utm_source' => $this->utm_source,
            'utm_term' => $this->utm_term,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
            'utm_param' => $this->utm_param,
            'invoice_id' => $this->invoice_id,
        ]);

        if ($object->validate() && $object->execute()) {
            $result = $object->getResponse();

            if (!isset($result['status']) || $result['status'] != 200) {
                Yii::error($result['message']);
            }
        }
    }
}