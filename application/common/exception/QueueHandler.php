<?php

//decode by http://chiran.taobao.com/
namespace app\common\exception;

use app\common\library\Email;
use think\Log;
class QueueHandler
{
	const should_run_hook_callback = true;
	public function logAllFailedQueues(&$jobObject)
	{
		$failedJobLog = array('jobHandlerClassName' => $jobObject->getName(), 'queueName' => $jobObject->getQueue(), 'jobData' => $jobObject->getRawBody(), 'attempts' => $jobObject->attempts());
		$title = 'queue异常:目录' . __DIR__ . "消息队列" . $jobObject->getName() . '已经运行了' . $jobObject->attempts() . '次了，依然未完成。';
		Log::error($title . json_encode($failedJobLog, true));
		Email::sendMail($title, json_encode($failedJobLog, true));
		return self::should_run_hook_callback;
	}
}