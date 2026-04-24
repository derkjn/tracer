<?php

namespace app\logger;

use app\context\contract\Context as ContextContract;

class Logger
{
    public static function log($message, array $context = array())
    {
        $container = app();

        /** @var ContextContract $requestContext */
        $requestContext = $container->get(ContextContract::class);

        /** @var CloudTraceLogger $traceLogger */
        $traceLogger = $container->get(CloudTraceLogger::class);

        return $traceLogger->log($message, $requestContext, $context);
    }
}
