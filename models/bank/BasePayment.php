<?php

namespace models\bank;

use Yii;
use models\AbstractModelBase;
use yii\helpers\ArrayHelper;
use helpers\Hash;

class BasePayment extends AbstractModelBase
{    
    public $name;
    public $surname;
    public $email;
    public $utm_source;
    public $utm_medium;
    public $utm_campaign;
    public $utm_content;
    public $utm_term;
    public $utm_param;
    public $token;

    protected $confirmation_token;
    protected $invoice_id;
    protected $invoice_hash;
    protected $promo_id = 0;

    protected $create_utm;
    protected $create_invoice;
    protected $create_payment;
    protected $read_promo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['email', 'token'],'required'],
            [['email', 'promocode', 'name', 'surname'], 'trim'],
            [['name', 'surname'], 'string', 'length' => [1, 255]],
            ['email', 'email', 'enableIDN' => true, 'message' => Yii::t('error', 'Email is incorrect. ')],
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
                'value' => NULL,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (parent::execute()) {
            try {
                $this->createInvoice();
                $this->createUtm();
                $this->paymentCreate();
                $this->setDataResponse(['confirmation_token' => $this->confirmation_token, 'invoice_id' => $this->invoice_hash]);
                return true;    
            } catch (\Exception $e) {
                $this->addError('error', $e->getMessage());
                $this->sendError();   
            }
        }
        return false;
    }

    /**
     * create invoice
     */
    protected function createInvoice()
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
            $this->invoice_hash = Hash::get($this->invoice_id, $object->getModelAr()->token_payment);
        } else {
            $this->addError('error', $object->getErrors());
            $this->sendError();
        }
    }

    /**
     * create payment yookassa
     */
    protected function paymentCreate()
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
     * create utm metriks
     */
    protected function createUtm()
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