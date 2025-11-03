<?php

declare(strict_types=1);

namespace models\tariffs;

use models\AbstractModel;
use models\invoice\Config;
use models\response\Response;
use Yii;

class Read extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    public function getResponse(): array
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