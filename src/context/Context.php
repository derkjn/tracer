<?php

namespace app\context;

use app\context\contract\Context as ContextContract;
use Exception;

class Context implements ContextContract
{
    /**
    * @var string - readonly
    */
    private $correlation_id;

    private $data;
    public function __construct()
    {
        $this->correlation_id = bin2hex(random_bytes(16));
        $this->data = [];
    }

    public function serialize()
    {
        return array_merge([
            'correlation_id' => $this->correlation_id,
        ], $this->data);
    }

    public function getCorrelationId()
    {
        return $this->correlation_id;
    }

    public function set($key, $val)
    {
        if (empty($key)) {
            throw new Exception("cannot set empty keys");
        }

        if (empty($val)) {
            throw new Exception("cannot set empty values");
        }

        $this->data[$key] = $val;
    }
}
