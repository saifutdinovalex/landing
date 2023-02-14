<?php

namespace models;

use Yii;
use yii\helpers\ArrayHelper;
use models\response\Response;

abstract class AbstractModelBase extends AbstractModel
{
    public $user_id;
    protected $data_response = [];

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            [['user_id'], 'integer'],
            [['user_id'], 'default', 'value' => 0],
        ]);
    }

    public function getResponse()
    {
        $response = new Response();
        $response
            ->setStatus(200)
            ->setData(array_merge(['message' => 'Completed successfully'], $this->data_response))
            ->build();
        return $response->get();
    }

    protected function initData()
    {
        $this->user_id = ($this->user_id && $this->checkIsManager())?:\Yii::$app->getUser()->getId();
    }

    protected function setDataResponse($value)
    {
        $this->data_response = $value;
        return $this;
    }

}