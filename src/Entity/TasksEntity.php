<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class TasksEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'post_task' => [
			'email_user_to', 'priority', 'text', 'todo', 'datestart', 'dateend',
			'subscribe', 'hidden', 'mention', 'max_time', 'max_money', 'tags'
		],
		'post_subtask' => [
			'email_user_to', 'priority', 'text', 'todo', 'datestart', 'dateend',
			'subscribe', 'hidden', 'max_time', 'max_money', 'tags'
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
	 * @param string $filter   Optional. Returns data only for open project tasks/subtasks. A separate value only for closed tasks is not provided
	 *                         Possible value: `active`
	 * @param string $extra    Optional. Returns additional data on tasks/subtasks (can be specified with commas)
	 *                         Possible values:
	 *                         `text` or `html` - description in text or html format, respectively
	 *                         `files`     - information about the files attached to the description
	 *                         `relations` - information about dependencies with other tasks
	 *                         `subtasks`  - returns child field (if available) with a list of subtasks in a similar format. There are 2 nesting levels available: task / subtask / sub-subtask
	 *                         `archive`   - returns tasks of archived projects
	 * @return array
	 * @throws SdkException
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
	 * @param int $projectId   Required. Project ID
	 * @param int $taskId      Required. Task ID
	 * @param int $subtaskId   Optional. Subtask ID (if needed subtask)
	 * @param string $filter   Optional. Returns data only for open subtasks (when using extra=subtasks parameter). A separate value only for closed subtasks is not provided
	 *                         Possible value: `active`
	 * @param string $extra    Optional. Returns additional data on tasks/subtasks (can be specified with commas)
	 *                         Possible values:
	 *                         `text` or `html` - description in text or html format, respectively
	 *                         `files`       - information about the files attached to the description
	 *                         `relations`   - information about dependencies with other tasks
	 *                         `subtasks`    - for a task returns child field (if available) with a list of subtasks. There are 2 nesting levels available: task / subtask / sub-subtask
	 *                         `subscribers` - subscriber list with user information
	 * @return array
	 * @throws SdkException
	 */
	public function get_task(int $projectId, int $taskId, int $subtaskId = 0, string $filter = '', string $extra = ''): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = compact('action', 'page', 'filter', 'extra');
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Returns data on a selected open or closed project task/subtask
	 * Except tasks with delayed publication
	 *
	 * @param int $projectId   Required. Project ID
	 * @param string $filter   Optional. Returns data only for open subtasks (when using extra=subtasks parameter). A separate value only for closed subtasks is not provided
	 *                         Possible value: `active`
	 * @param string $extra    Optional. Returns additional data on tasks/subtasks (can be specified with commas)
	 *                         Possible values:
	 *                         `text` or `html` - description in text or html format, respectively
	 *                         `files`       - information about the files attached to the description
	 *                         `relations`   - information about dependencies with other tasks
	 *                         `subtasks`    - returns child field (if available) with a list of subtasks in a similar format. There are 2 nesting levels available: task / subtask / sub-subtask
	 *                         `subscribers` - subscriber list with user information
	 * @return array
	 * @throws SdkException
	 */
	public function get_tasks(int $projectId, string $filter = '', string $extra = ''): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' ;
		$params = compact('action', 'page', 'filter', 'extra');
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Creates a task in a specified project, regardless of its status (active, sleeping, archived)
	 *
	 * @param string $title             Required. Task name
	 * @param string $email_user_from   Required. Task author email
	 * @param array $optional           Optional. Optional parameters in array, possible keys and values:
	 *                                  email_user_to - task executive email
	 *                                                  Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned"
	 *                                  priority      - priority (value range: 0..10)
	 *                                  text          - task description
	 *                                  todo          - checklist (for example: todo[]=case1&todo[]=case2)
	 *                                  datestart     - start date in DD.MM.YYYY format
	 *                                  dateend       - due date or end date in DD.MM.YYYY format
	 *                                  subscribe     - comma separated list of user emails, who will be subscribed to a task
	 *                                  hidden        - comma separated list of user emails, who will have access to this task, while it will be hidden for others
	 *                                  mention       - comma separated list of user emails, who will be mentioned at the end* of the task description
	 *                                                  Random mention location is not supported
	 *                                  max_time      - time estimates
	 *                                  max_money     - financial estimates
	 *                                  tags          - set task statuses/labels. You can specify names (if they are unique) or their IDs (can be obtained through get_tags method)
	 *                                                  Example value: `TAG1,TAG2`
	 * @return array
	 * @throws SdkException
	 */
	public function post_task(string $title, string $email_user_from, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'title'  => $title,
			'email_user_from' => $email_user_from
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Creates a subtask in a specified task/subtask
	 * Parent task/subtask should not be closed!
	 * This request allows adding files to a subtask description
	 *
	 * @param string $title             Required. Subtask name
	 * @param string $email_user_from   Required. Subtask author email
	 * @param array $optional           Optional. Optional parameters in array, possible keys and values:
	 *                                  email_user_to - subtask executive email
	 *                                                  Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned"
	 *                                  priority      - priority (value range: 0..10)
	 *                                  text          - subtask description
	 *                                  todo          - checklist (for example: todo[]=case1&todo[]=case2)
	 *                                  datestart     - start date in DD.MM.YYYY format
	 *                                  dateend       - due date or end date in DD.MM.YYYY format
	 *                                  subscribe     - comma separated list of user emails, who will be subscribed to a subtask
	 *                                  hidden        - comma separated list of user emails, who will have access to this subtask, while it will be hidden for others
	 *                                  max_time      - time estimates
	 *                                  max_money     - financial estimates
	 *                                  tags          - set subtask statuses/labels. You can specify names (if they are unique) or their IDs (can be obtained through get_tags method)
	 *                                                  Example value: `TAG1,TAG2`
	 * @return array
	 * @throws SdkException
	 */
	public function post_subtask(string $title, string $email_user_from, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'title'  => $title,
			'email_user_from' => $email_user_from
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Closes the specified task
	 * Response will show corresponding error if the task is already closed or contains open subtasks
	 *
	 * @param int $projectId   Required. Project ID
	 * @param int $taskId      Required. Task ID
	 * @return array
	 * @throws SdkException
	 */
	public function complete_task(int $projectId, int $taskId): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		$params = compact('action', 'page');

		return $this->request($params);
	}



	/**
	 * Closes the specified subtask/sub-subtask
	 * The task should not be already closed and, in the case of a subtask, it should not contain open nested sub-subtasks
	 *
	 * @param int $projectId   Required. Project ID
	 * @param int $taskId      Required. Task ID
	 * @param int $subtaskId   Required. Subtask ID
	 * @return array
	 * @throws SdkException
	 */
	public function complete_subtask(int $projectId, int $taskId, int $subtaskId): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = compact('action', 'page');

		return $this->request($params);
	}



	/**
	 * Reopens the specified task
	 *
	 * @param int $projectId   Required. Project ID
	 * @param int $taskId      Required. Task ID
	 * @return array
	 * @throws SdkException
	 */
	public function reopen_task(int $projectId, int $taskId): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		$params = compact('action', 'page');

		return $this->request($params);
	}



	/**
	 * Reopens the specified subtask
	 *
	 * @param int $projectId   Required. Project ID
	 * @param int $taskId      Required. Task ID
	 * @param int $subtaskId   Required. Subtask ID
	 * @return array
	 * @throws SdkException
	 */
	public function reopen_subtask(int $projectId, int $taskId, int $subtaskId): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = compact('action', 'page');

		return $this->request($params);
	}



	/**
	 * Updates number of parameters for a specified open or closed task
	 * All optional parameters are available for updating
	 *
	 * @param int $projectId    Required. Project ID
	 * @param int $taskId       Required. Task ID
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          email_user_to - task executive email
	 *                                            Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned"
	 *                          priority      - priority (value range: 0..10)
	 *                          title         - task name
	 *                          datestart     - start date in DD.MM.YYYY format
	 *                          dateend       - due date or end date in DD.MM.YYYY format
	 *                          dateclosed    - closing date in DD.MM.YYYY format
	 *                          max_time      - time estimates
	 *                          max_money     - financial estimates
	 * @return array
	 * @throws SdkException
	 */
	public function update_task(int $projectId, int $taskId, array $optional = []): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		$params = [
			'action' => $action,
			'page'   => $page
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Updates number of parameters for a specified open or closed subtask (sub-subtask)
	 * All optional parameters are available for updating
	 *
	 * @param int $projectId    Required. Project ID
	 * @param int $taskId       Required. Task ID
	 * @param int $subtaskId    Required. Subtask ID
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          email_user_to - subtask executive email
	 *                                            Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned"
	 *                          priority      - priority (value range: 0..10)
	 *                          title         - subtask name
	 *                          datestart     - start date in DD.MM.YYYY format
	 *                          dateend       - due date or end date in DD.MM.YYYY format
	 *                          dateclosed    - closing date in DD.MM.YYYY format
	 *                          max_time      - time estimates
	 *                          max_money     - financial estimates
	 * @return array
	 * @throws SdkException
	 */
	public function update_subtask(int $projectId, int $taskId, int $subtaskId, array $optional = []): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = [
			'action' => $action,
			'page'   => $page
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
	 * @param int $projectId            Optional. Project ID
	 * @param string $email_user_from   Optional. Task author email
	 * @param string $email_user_to     Optional. Task executive email
	 * @param string $filter            Optional. Search query (see description below):
	 *                                  Possible data for use in filter (for search_tasks method)
	 *									Integer fields:
	 *									    id=TASK_ID         - returns data of a specific task/subtask by its ID (can be obtained through get_all_tasks or get_tasks methods)
	 *									    project=PROJECT_ID - returns data on tasks of a certain project by its ID (can be obtained through the get_projects method)
	 *									    parent=TASK_ID     - returns data on subtasks of a certain parent task by its ID (can be obtained through get_all_tasks or get_tasks methods)
	 *                                  Equality and range operators for integer fields: =, in, example: `project{=}2456`, `id {in} (1234, 1240)`
	 *                                  String fields:
	 *                                      name - task name
	 *                                  Full or partial match for string fields: =, has, example: `name{=}'Task Report'`, `name {has} 'Report'`
	 *                                  Date fields:
	 *                                      dateadd or date_added    - task creation date in 'DD.MM.YYYY' format
	 *									    datestart or date_start  - task start date in 'DD.MM.YYYY' format
	 *									    dateend or date_end      - task due date or end date in 'DD.MM.YYYY' format
	 *									    dateclose or date_closed - task closing date in 'DD.MM.YYYY' format
	 *                                  Relational operators for date fields: >, <, >=, <=, ! =, =, example: `dateadd{=}'01.05.2021'`
	 *                                  Query conditions can be combined with parentheses () and logical operations and, or (only in lowercase)
	 *                                  Example filter query: `&filter=(name has 'Report' or name has 'Approval') and (dateend>'25.05.2021' and dateend<'31.05.2021')`
	 * @param string $status            Optional. Status (`active` or `done` - open/closed)
	 * @return array
	 * @throws SdkException
	 */
	public function search_tasks(int $projectId = 0, string $email_user_from = '', string $email_user_to = '', string $filter = '', string $status = ''): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'email_user_from', 'email_user_to', 'filter', 'status');
		if ($projectId) {
			$page = '/project/' . $projectId . '/';
			$params['page'] = $page;
		}
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Returns data on events (performed actions) in projects for a specified period of time (with the information on who and when made changes, as well as versions before and after changes)
	 * If page parameter is not specified, data for all account projects will be received
	 *
	 * @param string $period   Required. Time period (possible ranges: 1m..360m in minutes, 1h .. 72h in hours, 1d..30d in days)
	 *                         Example value: `3d`
	 * @param int $projectId   Optional. Project ID for returns the data of the project
	 * @return array
	 * @throws SdkException
	 */
	public function get_events(string $period, int $projectId = 0): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'period');
		if ($projectId) {
			$page = '/project/' . $projectId . '/';
			$params['page'] = $page;
		}
		$params = array_filter($params);

		return $this->request($params);
	}
}