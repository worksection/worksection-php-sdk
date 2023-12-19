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
			'email_user_from', 'time', 'money', 'is_rate', 'comment', 'date'
		],
		'update_costs' => [
			'time', 'money', 'is_rate', 'comment', 'date'
		]
	];


	/**
	 * Returns data on entered time and financial costs for projects, tasks and subtasks
	 * If page parameter is not specified, costs for all account projects will be received
	 *
	 * @param int $projectId    Optional. Project ID (costs for all project)
	 * @param int $taskId       Optional. Task/subtask ID (costs for tasks/subtasks)
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          datestart - date range for searching data in DD.MM.YYYY format (inclusive) <br>
	 *                          dateend   - date range for searching data in DD.MM.YYYY format (inclusive) <br>
	 *                          is_timer  - returns only those cost lines, where time is possible: <br>
	 *                                      `1` - received from the timer <br>
	 *                                      `0` - entered manually <br>
	 *                          filter    - additional parameter for searching (see search_tasks in TasksEntity for a list of operators to work with data used in filter) <br>
	 *                          Possible data for use in filter: <br>
	 *                          task=<TASK_ID>       - returns data of a specific task/subtask by its ID (can be obtained through get_all_tasks or get_tasks method in TasksEntity) <br>
	 *                          project=<PROJECT_ID> - returns data on tasks of a specific project by its ID (can be obtained through get_projects in projectsEntity method) <br>
	 *                          comment              - user comment to a separate cost line (String) <br>
	 *                          dateadd              - date the costs were added in the 'DD.MM. YYYY' format <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-costs.html
	 */
	public function get_costs(int $projectId = 0, int $taskId = 0, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = ['action' => $action];
		if ($projectId) $params['id_project'] = $projectId;
		if ($taskId) $params['id_task'] = $taskId;

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
	 * @param int $projectId    Optional. Project ID (costs for all project)
	 * @param int $taskId       Optional. Task/subtask ID (costs for tasks/subtasks)
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          datestart - date range for searching data in DD.MM.YYYY format (inclusive) <br>
	 *                          dateend   - date range for searching data in DD.MM.YYYY format (inclusive) <br>
	 *                          is_timer  - returns total amount only for those cost lines, where time is possible: <br>
	 *                                      `1` - received from the timer <br>
	 *                                      `0` - entered manually <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-costs.html
	 */
	public function get_costs_total(int $projectId = 0, int $taskId = 0, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = ['action' => $action];
		if ($projectId) $params['id_project'] = $projectId;
		if ($taskId) $params['id_task'] = $taskId;

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
	 * @param int $taskId             Required. Task ID
	 * @param array $optional         Optional. Optional parameters in array, possible keys and values:
	 *                                email_user_from - comment author email (required when use admin token, when use access token - will be set automatically) <br>
	 *                                time    - time costs in one of the following formats: 0.15 / 0,15 / 0:09 <br>
	 *                                money   - financial costs in account currency (if it needs to be specified without reference to an hourly rate) <br>
	 *                                is_rate - financial costs are calculated on an hourly rate (money parameter is ignored). Possible value: `1` <br>
	 *                                comment - comment to a separate cost line <br>
	 *                                date    - date the costs were added <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-costs.html
	 */
	public function add_costs(int $taskId, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'id_task' => $taskId
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
	 * @param int $costsId            Required. Cost line unique identifier (can be obtained through get_costs method)
	 * @param array $optional         Optional. Optional parameters in array, possible keys and values:
	 *                                time    - time costs in one of the following formats: 0.15 / 0,15 / 0:09 <br>
	 *                                money   - financial costs in account currency (if it needs to be specified without reference to an hourly rate) <br>
	 *                                is_rate - financial costs are calculated on an hourly rate (money parameter is ignored). Possible value: `1` <br>
	 *                                comment - comment to a separate cost line <br>
	 *                                date    - date the costs were added <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-costs.html
	 */
	public function update_costs(int $costsId, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'id_costs' => $costsId
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
	 * @param int $costsId   Required. Cost line unique identifier (can be obtained through get_costs method)
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-costs.html
	 */
	public function delete_costs(int $costsId): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id_costs' => $costsId
		];

		return $this->request($params);
	}
}