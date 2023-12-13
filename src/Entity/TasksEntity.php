<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class TasksEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'post_task' => [
			'email_user_from', 'email_user_to', 'priority', 'text', 'todo', 'datestart',
			'dateend', 'subscribe', 'hidden', 'mention', 'max_time', 'max_money', 'tags'
		],
		'update_task' => [
			'email_user_to', 'priority', 'title', 'datestart',
			'dateend', 'dateclosed', 'max_time', 'max_money'
		]
	];



	/**
	 * Returns data on all open and closed account tasks/subtasks
	 * Except tasks with delayed publication
	 *
	 * @param string $filter   Optional. Returns data only for open project tasks/subtasks. A separate value only for closed tasks is not provided <br>
	 *                         Possible value: `active` <br>
	 * @param string $extra    Optional. Returns additional data on tasks/subtasks (can be specified with commas) <br>
	 *                         Possible values: <br>
	 *                         `text` or `html` - description in text or html format, respectively <br>
	 *                         `files`     - information about the files attached to the description <br>
	 *                         `relations` - information about dependencies with other tasks <br>
	 *                         `subtasks`  - returns child field (if available) with a list of subtasks in a similar format. There are 2 nesting levels available: task / subtask / sub-subtask <br>
	 *                         `archive`   - returns tasks of archived projects <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function get_all_tasks(string $filter = '', string $extra = ''): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'filter', 'extra');
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Returns data on a selected open or closed project task/subtask
	 * Except tasks with delayed publication
	 *
	 * @param int $taskId      Required. Task/subtask ID
	 * @param string $filter   Optional. Returns data only for open subtasks (when using extra=subtasks parameter). A separate value only for closed subtasks is not provided <br>
	 *                         Possible value: `active` <br>
	 * @param string $extra    Optional. Returns additional data on tasks/subtasks (can be specified with commas) <br>
	 *                         Possible values: <br>
	 *                         `text` or `html` - description in text or html format, respectively <br>
	 *                         `files`       - information about the files attached to the description <br>
	 *                         `relations`   - information about dependencies with other tasks <br>
	 *                         `subtasks`    - for a task returns child field (if available) with a list of subtasks. There are 2 nesting levels available: task / subtask / sub-subtask <br>
	 *                         `subscribers` - subscriber list with user information <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function get_task(int $taskId, string $filter = '', string $extra = ''): array
	{
		$params = [
			'action'	 => __FUNCTION__,
			'id_task'    => $taskId,
			'filter'     => $filter,
			'extra'      => $extra
		];
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Returns data on a selected open or closed project task/subtask
	 * Except tasks with delayed publication
	 *
	 * @param int $projectId   Required. Project ID
	 * @param string $filter   Optional. Returns data only for open subtasks (when using extra=subtasks parameter). A separate value only for closed subtasks is not provided <br>
	 *                         Possible value: `active` <br>
	 * @param string $extra    Optional. Returns additional data on tasks/subtasks (can be specified with commas) <br>
	 *                         Possible values: <br>
	 *                         `text` or `html` - description in text or html format, respectively <br>
	 *                         `files`       - information about the files attached to the description <br>
	 *                         `relations`   - information about dependencies with other tasks <br>
	 *                         `subtasks`    - returns child field (if available) with a list of subtasks in a similar format. There are 2 nesting levels available: task / subtask / sub-subtask <br>
	 *                         `subscribers` - subscriber list with user information <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function get_tasks(int $projectId, string $filter = '', string $extra = ''): array
	{
		$params = [
			'action'	 => __FUNCTION__,
			'id_project' => $projectId,
			'filter'     => $filter,
			'extra'      => $extra
		];
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Creates a task/subtask in a specified project, regardless of its status (active, sleeping, archived)
	 *
	 * @param string $title             Required. Task name
	 * @param int $projectId            Required. Project ID
	 * @param array $optional           Optional. Optional parameters in array, possible keys and values:
	 *                                  id_parent       - task id for creating subtask (parent task) <br>
	 *                                  email_user_from - task author email (when use access token - will be set automatically) <br>
	 *                                  email_user_to - task executive email <br>
	 *                                                  Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned" <br>
	 *                                  priority      - priority (value range: 0..10) <br>
	 *                                  text          - task description <br>
	 *                                  todo          - checklist (for example: todo[]=case1&todo[]=case2) <br>
	 *                                  datestart     - start date in DD.MM.YYYY format <br>
	 *                                  dateend       - due date or end date in DD.MM.YYYY format <br>
	 *                                  subscribe     - comma separated list of user emails, who will be subscribed to a task <br>
	 *                                  hidden        - comma separated list of user emails, who will have access to this task, while it will be hidden for others <br>
	 *                                  mention       - comma separated list of user emails, who will be mentioned at the end* of the task description <br>
	 *                                                  Random mention location is not supported <br>
	 *                                  max_time      - time estimates <br>
	 *                                  max_money     - financial estimates <br>
	 *                                  tags          - set task statuses/labels. You can specify names (if they are unique) or their IDs (can be obtained through get_tags method) <br>
	 *                                                  Example value: `TAG1,TAG2` <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function post_task(string $title, int $projectId, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'title'  => $title,
			'id_project' => $projectId
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Closes the specified task/subtask
	 * Response will show corresponding error if the task is already closed or contains open subtasks
	 *
	 * @param int $taskId      Required. Task ID
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function complete_task(int $taskId): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id_task' => $taskId
		];

		return $this->request($params);
	}



	/**
	 * Reopens the specified task
	 *
	 * @param int $taskId      Required. Task ID
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function reopen_task(int $taskId): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id_task' => $taskId
		];

		return $this->request($params);
	}



	/**
	 * Updates number of parameters for a specified open or closed task
	 * All optional parameters are available for updating
	 *
	 * @param int $taskId       Required. Task ID
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          email_user_to - task executive email <br>
	 *                                          Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned" <br>
	 *                          priority      - priority (value range: 0..10) <br>
	 *                          title         - task name <br>
	 *                          datestart     - start date in DD.MM.YYYY format <br>
	 *                          dateend       - due date or end date in DD.MM.YYYY format <br>
	 *                          dateclosed    - closing date in DD.MM.YYYY format <br>
	 *                          max_time      - time estimates <br>
	 *                          max_money     - financial estimates <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function update_task(int $taskId, array $optional = []): array
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
	 * Searches for tasks by one or several parameters
	 * If page parameter is not specified, data of archive projects will be excluded from search results
	 *
	 * At least one of the following parameters is required:
	 * @param int $projectId            Required. Project ID
	 * @param int $taskId               Optional. Task ID
	 * @param string $emailUserFrom     Optional. Task author email
	 * @param string $emailUserTo       Optional. Task executive email
	 * @param string $filter            Optional. Search query (see description below): <br>
	 *                                  Possible data for use in filter (for search_tasks method) <br>
	 *									Integer fields: <br>
	 *									    id=<TASK_ID>         - returns data of a specific task/subtask by its ID (can be obtained through get_all_tasks or get_tasks methods) <br>
	 *									    project=<PROJECT_ID> - returns data on tasks of a certain project by its ID (can be obtained through the get_projects method) <br>
	 *									    parent=<TASK_ID>     - returns data on subtasks of a certain parent task by its ID (can be obtained through get_all_tasks or get_tasks methods) <br>
	 *                                  Equality and range operators for integer fields: =, in, example: `project{=}2456`, `id {in} (1234, 1240)` <br>
	 *                                  String fields: <br>
	 *                                      name - task name <br>
	 *                                  Full or partial match for string fields: =, has, example: `name{=}'Task Report'`, `name {has} 'Report'` <br>
	 *                                  Date fields: <br>
	 *                                      dateadd or date_added    - task creation date in 'DD.MM.YYYY' format <br>
	 *									    datestart or date_start  - task start date in 'DD.MM.YYYY' format <br>
	 *									    dateend or date_end      - task due date or end date in 'DD.MM.YYYY' format <br>
	 *									    dateclose or date_closed - task closing date in 'DD.MM.YYYY' format <br>
	 *                                  Relational operators for date fields: >, <, >=, <=, ! =, =, example: `dateadd{=}'01.05.2021'` <br>
	 *                                  Query conditions can be combined with parentheses () and logical operations and, or (only in lowercase) <br>
	 *                                  Example filter query: `&filter=(name has 'Report' or name has 'Approval') and (dateend>'25.05.2021' and dateend<'31.05.2021')` <br>
	 * @param string $status            Optional. Status (`active` or `done` - open/closed)
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function search_tasks(int $projectId, int $taskId = 0, string $emailUserFrom = '', string $emailUserTo = '', string $filter = '', string $status = ''): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id_project' => $projectId,
			'email_user_from' => $emailUserFrom,
			'email_user_to' => $emailUserTo,
			'filter' => $filter,
			'status' => $status
		];
		if ($taskId) $params['id_task'] = $taskId;
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Returns data on events (performed actions) in projects for a specified period of time (with the information on who and when made changes, as well as versions before and after changes)
	 * If page parameter is not specified, data for all account projects will be received
	 *
	 * @param string $period   Required. Time period (possible ranges: 1m..360m in minutes, 1h .. 72h in hours, 1d..30d in days) <br>
	 *                         Example value: `3d` <br>
	 * @param int $projectId   Optional. Project ID for returns the data of the project
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-task.html
	 */
	public function get_events(string $period, int $projectId = 0): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'period');
		if ($projectId) {
			$params['id_project'] = $projectId;
		}
		$params = array_filter($params);

		return $this->request($params);
	}
}