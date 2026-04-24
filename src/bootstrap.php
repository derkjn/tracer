<?php

use app\context\Context as AppContext;
use app\context\contract\Context;
use app\logger\CloudTraceLogger;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';


$projectId = getenv('GOOGLE_CLOUD_PROJECT');
if (!$projectId) {
    $projectId = getenv('GCLOUD_PROJECT');
}

app()->share(Context::class, AppContext::class);
app()->share(CloudTraceLogger::class, function () use ($projectId) {
    return new CloudTraceLogger(array(
        'projectId' => $projectId,
        'keyFilePath' => getenv('GOOGLE_KEYFILE_PATH'),
        ));
});
