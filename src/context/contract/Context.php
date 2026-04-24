<?php

namespace app\context\contract;

interface Context
{
    /**
     *
     * @return string
     */
    public function getCorrelationId();
    
    /**
     *
     * @param mixed $key
     * @param mixed $val
     * @return void
     */
    public function set($key, $val);

    /**
     * @return array
     */
    public function serialize();
}
