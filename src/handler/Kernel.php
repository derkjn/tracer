<?php

namespace app\handler;

use app\context\contract\Context as ContractContext;
use app\logger\Logger;

class Kernel
{
    public static function start()
    {
		$context = app()->get(ContractContext::class);
		$context->set('userID', uuid4());
		$start = time();
        Logger::log('Here is a trace', []);

		// very important op here
		sleep(10);
		
		$end = time();
		Logger::log('op finished', [
			'duration' => $end - $start,
		]);
    }
}
