<?php

declare(strict_types=1);

namespace models\interfaces;

interface Observer
{
    public function notify(object $obj): void;
}