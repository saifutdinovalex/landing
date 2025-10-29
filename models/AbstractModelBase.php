<?php

namespace models;

use Yii;
use yii\helpers\ArrayHelper;
use models\response\Response;

abstract class AbstractModelBase extends AbstractModel
{
    protected $data_response = [];

    /**
     * {@inheritdoc}
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function getResponse()
    {
        $response = new Response();
        $response
            ->setStatus(200)
            ->setData(array_merge(['message' => 'Completed successfully'], $this->data_response))
            ->build();
        return $response->get();
    }

    /*
        value - array result for response
     */
    protected function setDataResponse($value)
    {
        $this->data_response = $value;
        return $this;
    }
}
