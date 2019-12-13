<?php declare(strict_types = 1);

namespace App\Tasks;

class TaskWorker
{

	public function __invoke(
		\Swoole\Http\Server $server,
		int $taskId,
		int $fromId,
		$data
	)
	{
		echo "Task id: $taskId\n";

		// Notify the server that processing of the task has finished:
		$server->finish('');
	}
}
