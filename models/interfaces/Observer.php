<?php

namespace models\interfaces;

interface Observer
{
    public function notify($obj);
}