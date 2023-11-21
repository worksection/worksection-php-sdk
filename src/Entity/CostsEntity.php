<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class CostsEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'get_costs' => [
			'datestart', 'dateend', 'is_timer', 'filter'
		],
		'get_costs_total' => [
			'datestart', 'dateend', 'is_timer'
		],
		'add_costs' => [
			'time', 'money', 'is_rate', 'comment', 'date'
		],
		'update_costs' => [
			'time', 'money', 'is_rate', 'comment', 'date'
		]
	];



	/**
	 * Returns data on entered time and financial costs for tasks and subtasks
	 * If page parameter is not specified, costs for all account projects will be received
	 *
	 * @param int $projectId    Optional. Project ID
	 * @param int $taskId       Optional. Task ID
	 * @param int $subtaskId    Optional. Subtask ID
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          datestart - date range for searching data in DD.MM.YYYY format (inclusive)
	 *                          dateend   - date range for searching data in DD.MM.YYYY format (inclusive)
	 *                          is_timer  - returns only those cost lines, where time is possible:
	 *                                      `1` - received from the timer
	 *                                      `0` - entered manually
	 *                          filter    - additional parameter for searching (see search_tasks in TasksEntity for a list of operators to work with data used in filter)
	 *                          Possible data for use in filter:
	 *                          task=<TASK_ID>       - returns data of a specific task/subtask by its ID (can be obtained through get_all_tasks or get_tasks method in TasksEntity)
	 *                          project=<PROJECT_ID> - returns data on tasks of a specific project by its ID (can be obtained through get_projects in projectsEntity method)
	 *                          comment              - user comment to a separate cost line (String)
	 *                          dateadd              - date the costs were added in the 'DD.MM. YYYY' format
	 * @return array
	 * @throws SdkException
	 */
	public function get_costs(int $projectId = 0, int $taskId = 0, int $subtaskId = 0, array $optional = []): array
	{
		$page = '';
		$action = __FUNCTION__;
		if ($projectId && $taskId) {
			$page = '/project/' . $projectId . '/' . $taskId . '/';
		}
		if ($page && $subtaskId) $page .= $subtaskId . '/';
		$params = ['action' => $action];
		if ($page) $params['page'] = $page;

		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Returns data on total entered time and financial costs of tasks and subtasks for a specified project
	 * If page parameter is not specified, costs for all account projects will be received
	 *
	 * @param int $projectId    Optional. Project ID
	 * @param int $taskId       Optional. Task ID
	 * @param int $subtaskId    Optional. Subtask ID
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          datestart - date range for searching data in DD.MM.YYYY format (inclusive)
	 *                          dateend   - date range for searching data in DD.MM.YYYY format (inclusive)
	 *                          is_timer  - returns total amount only for those cost lines, where time is possible:
	 *                                      `1` - received from the timer
	 *                                      `0` - entered manually
	 * @return array
	 * @throws SdkException
	 */
	public function get_costs_total(int $projectId = 0, int $taskId = 0, int $subtaskId = 0, array $optional = []): array
	{
		$page = '';
		$action = __FUNCTION__;
		if ($projectId && $taskId) {
			$page = '/project/' . $projectId . '/' . $taskId . '/';
		}
		if ($page && $subtaskId) $page .= $subtaskId . '/';
		$params = ['action' => $action];
		if ($page) $params['page'] = $page;

		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Adds a time/financial cost line for a specified task/subtask
	 *
	 * @param string $emailUserFrom   Required. User email, who added an individual cost line
	 * @param int $projectId          Required. Project ID
	 * @param int $taskId             Required. Task ID
	 * @param int $subtaskId          Optional. Subtask ID
	 * @param array $optional         Optional. Optional parameters in array, possible keys and values:
	 *                                time    - time costs in one of the following formats: 0.15 / 0,15 / 0:09
	 *                                money   - financial costs in account currency (if it needs to be specified without reference to an hourly rate)
	 *                                is_rate - financial costs are calculated on an hourly rate (money parameter is ignored). Possible value: `1`
	 *                                comment - comment to a separate cost line
	 *                                date    - date the costs were added
	 * @return array
	 * @throws SdkException
	 */
	public function add_costs(string $emailUserFrom, int $projectId, int $taskId, int $subtaskId = 0, array $optional = []): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = [
			'action' => $action,
			'page' => $page,
			'email_user_from' => $emailUserFrom
		];

		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Updates number of parameters for a specified cost line
	 * All optional parameters are available for updating
	 *
	 * @param int $id                 Required. Cost line unique identifier (can be obtained through get_costs method)
	 * @param int $projectId          Required. Project ID
	 * @param int $taskId             Required. Task ID
	 * @param int $subtaskId          Optional. Subtask ID
	 * @param array $optional         Optional. Optional parameters in array, possible keys and values:
	 *                                time    - time costs in one of the following formats: 0.15 / 0,15 / 0:09
	 *                                money   - financial costs in account currency (if it needs to be specified without reference to an hourly rate)
	 *                                is_rate - financial costs are calculated on an hourly rate (money parameter is ignored). Possible value: `1`
	 *                                comment - comment to a separate cost line
	 *                                date    - date the costs were added
	 * @return array
	 * @throws SdkException
	 */
	public function update_costs(int $id, int $projectId, int $taskId, int $subtaskId = 0, array $optional = []): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = [
			'action' => $action,
			'page' => $page,
			'id' => $id
		];

		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Deletes a specified cost line from a specific task/subtask
	 *
	 * @param int $id           Required. Cost line unique identifier (can be obtained through get_costs method)
	 * @param int $projectId    Required. Project ID
	 * @param int $taskId       Required. Task ID
	 * @param int $subtaskId    Optional. Subtask ID
	 * @return array
	 * @throws SdkException
	 */
	public function delete_costs(int $id, int $projectId, int $taskId, int $subtaskId = 0): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = [
			'action' => $action,
			'page' => $page,
			'id' => $id
		];

		return $this->request($params);
	}



	/**
	 * Returns data on running timers: their ID, start time, timer value and who started them
	 *
	 * @return array
	 * @throws SdkException
	 */
	public function get_timers(): array
	{
		$action = __FUNCTION__;
		$params = compact('action');

		return $this->request($params);
	}



	/**
	 * Stops the specified running timer and saves its data
	 *
	 * @param int $timer    Required. Timer ID (can be obtained through get_timers method)
	 * @return array
	 * @throws SdkException
	 */
	public function stop_timer(int $timer): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'timer');

		return $this->request($params);
	}
}