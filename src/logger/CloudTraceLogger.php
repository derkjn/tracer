<?php

namespace app\logger;

use app\context\contract\Context as ContextContract;
use DateTime;
use DateTimeZone;
use Google\Cloud\Trace\TraceClient;

class CloudTraceLogger extends TraceClient
{
    public function log($message, ContextContract $requestContext, array $context = array())
    {
        $now = new DateTime('now', new DateTimeZone('UTC'));
        $mergedContext = array_merge($requestContext->serialize(), $context);

        $attributes = array_merge(
            array(
                'message' => (string) $message,
                'correlation_id' => (string) $requestContext->getCorrelationId(),
            ),
            $this->normalizeContext($mergedContext)
        );

        $trace = $this->trace($requestContext->getCorrelationId());
        $span = $trace->span(array(
            'name' => 'application.log',
            'startTime' => $now,
            'endTime' => $now,
            'attributes' => $attributes,
        ));

        $trace->setSpans(array($span));
        $this->insert($trace);

        return $trace->traceId();
    }

    private function normalizeContext(array $context)
    {
        $normalized = array();

        foreach ($context as $key => $value) {
            if (is_scalar($value) || $value === null) {
                $normalized[(string) $key] = (string) $value;
                continue;
            }

            $encoded = json_encode($value);
            $normalized[(string) $key] = ($encoded === false) ? '[unserializable]' : $encoded;
        }

        return $normalized;
    }
}
