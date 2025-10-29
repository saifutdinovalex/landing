<?php

namespace models\tariffs;

use models\response\Response;
use models\AbstractModel;
use Yii;
use models\invoice\Config;

class Read extends AbstractModel
{
    /**
     * {@inheritdoc}
     * @return array
     */
    public function getResponse()
    {
        $response = new Response();
        $result = Config::get();
        $response
            ->setData($result)
            ->setCount(count($result))
            ->build();

        return $response->get();
    }
}
