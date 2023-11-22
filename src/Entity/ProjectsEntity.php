<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class ProjectsEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'post_project' => [
			'email_user_from', 'email_manager', 'emaul_user_to', 'members',
			'text', 'company', 'datestart', 'dateend', 'options.allow_close',
			'options.allow_give', 'options.allow_term', 'options.allow_limit',
			'options.require_term', 'options.require_tag', 'options.require_limit',
			'options.require_hidden', 'options.deny_comments_edit', 'options.deny_task_edit',
			'options.deny_task_delete', 'options.time_require', 'options.time_today',
			'options.timer_only', 'extra', 'max_time', 'max_money', 'tags'
		],
		'update_project' => [
			'email_manager', 'emaul_user_to', 'members', 'title',
			'datestart', 'dateend', 'options.allow_close', 'options.allow_give',
			'options.allow_term', 'options.allow_limit', 'options.require_term',
			'options.require_tag', 'options.require_limit', 'options.require_hidden',
			'options.deny_comments_edit', 'options.deny_task_edit',
			'options.deny_task_delete', 'options.time_require', 'options.time_today',
			'options.timer_only', 'extra', 'max_time', 'max_money', 'tags'
		]
	];



	/**
	 * Returns data on all projects
	 *
	 * @param string $filter   Optional. Returns data for a project in a specified status (active/sleeping/archived) <br>
	 *                         Possible values: `active`, `pending`, `archive` <br>
	 * @param string $extra    Optional. Returns additional project data (can be specified with commas) <br>
	 *                         Possible values: `text`, `options`, `users` <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function get_projects(string $filter = '', string $extra = ''): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'filter', 'extra');
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Archives the specified project
	 * Response will show corresponding error if the project is already archived
	 *
	 * @param int $projectId   Required. Project ID
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function close_project(int $projectId): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/';
		$params = compact('action', 'page');
		return $this->request($params);
	}



	/**
	 * Creates a project (only in active status)
	 * This request allows adding files to a project description
	 *
	 * @param string $title     Required. Project name
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          email_user_from - project author email <br>
	 *                          email_manager - project manager email <br>
	 *                          email_user_to - user email, who will be set as a task executive by default <br>
	 *                                          Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned" <br>
	 *                          members       - comma separated list of project member emails <br>
	 *                          text          - project description <br>
	 *                          company       - folder name, where the project will be located <br>
	 *                          datestart     - start date in DD.MM.YYYY format <br>
	 *                          dateend       - due date or end date in DD.MM.YYYY format <br>
	 *                          extra         - returns a dataset on project restriction options. Possible value: `options` <br>
	 *                          max_time      - time estimates <br>
	 *                          max_money     - financial estimates <br>
	 *                          tags          - set project statuses/labels. You can specify names (if they are unique) or their IDs (can be obtained through get_project_tags method). <br>
	 *                                          Example value: `TAG1,TAG2` <br>
	 *
	 * 							List of project restriction options (set value `1` to enable): <br>
	 *                          options.allow_close        - mark task as done <br>
	 *                          options.allow_give         - transfer the responsibility <br>
	 *                          options.allow_term         - change the task terms <br>
	 *                          options.allow_limit        - change the estimates <br>
	 *                          options.require_term       - terms <br>
	 *                          options.require_tag        - stages and labels <br>
	 *                          options.require_limit      - estimates <br>
	 *                          options.require_hidden     - visibility <br>
	 *                          options.deny_comments_edit - edit and delete comments <br>
	 *                          options.deny_task_edit     - edit and delete tasks <br>
	 *                          options.deny_task_delete   - delete tasks <br>
	 *                          options.time_require       - mark as done with costs only <br>
	 *                          options.time_today         - add for today only <br>
	 *                          options.timer_only         - add from timer only <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function post_project(string $title, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'title'  => $title
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Updates number of parameters for a specified active project
	 * All optional parameters are available for updating
	 *
	 * @param int $projectId     Required. Project ID
	 * @param array $optional    Optional. Optional parameters in array, possible keys and values:
	 *                           email_manager - project manager email <br>
	 *                           email_user_to - user email, who will be set as a task executive by default <br>
	 *                                           Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned" <br>
	 *                           members       - comma separated list of project member emails <br>
	 *                           title         - project name <br>
	 *                           datestart     - start date in DD.MM.YYYY format <br>
	 *                           dateend       - due date or end date in DD.MM.YYYY format <br>
	 *                           extra         - returns a dataset on project restriction options. <br>
	 *                                           Possible value: `options` <br>
	 *                           max_time      - time estimates <br>
	 *                           max_money     - financial estimates <br>
	 *
	 *                           List of project restriction options (set value `1` to enable): <br>
	 *                           options.allow_close        - mark task as done <br>
	 *                           options.allow_give         - transfer the responsibility <br>
	 *                           options.allow_term         - change the task terms <br>
	 *                           options.allow_limit        - change the estimates <br>
	 *                           options.require_term       - terms <br>
	 *                           options.require_tag        - stages and labels <br>
	 *                           options.require_limit      - estimates <br>
	 *                           options.require_hidden     - visibility <br>
	 *                           options.deny_comments_edit - edit and delete comments <br>
	 *                           options.deny_task_edit     - edit and delete tasks <br>
	 *                           options.deny_task_delete   - delete tasks <br>
	 *                           options.time_require       - mark as done with costs only <br>
	 *                           options.time_today         - add for today only <br>
	 *                           options.timer_only         - add from timer only <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function update_project(int $projectId, array $optional = []): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/';
		$params = [
			'action' => $action,
			'page'  => $page
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Returns data with folder names, their IDs and type
	 *
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function get_project_groups(): array
	{
		$action = __FUNCTION__;
		$params = compact('action');
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Checks for the possible existence of a folder with the specified name and creates a new one if necessary
	 *
	 * @param string $title   Required. Project folder name
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function add_project_group(string $title): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'title');

		return $this->request($params);
	}



	/**
	 * @param int $projectId  Required. Project ID
	 * @param string $extra   Optional. Returns additional project data (can be specified with commas) <br>
	 *                        Possible values: `text` or `html`, `options` <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function get_project(int $projectId, string $extra = ''): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/';
		$params = compact('action', 'page', 'extra');
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Adds account users to the project team
	 *
	 * @param int $projectId   Required. Project ID
	 * @param string $members  Required. Comma separated list of user emails to be added
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function add_project_members(int $projectId, string $members): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/';
		$params = compact('action', 'page', 'members');

		return $this->request($params);
	}



	/**
	 * Removes account users from the project team
	 *
	 * @param int $projectId   Required. Project ID
	 * @param string $members  Required. Comma separated list of user emails to be removed
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function delete_project_members(int $projectId, string $members): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/';
		$params = compact('action', 'page', 'members');

		return $this->request($params);
	}



	/**
	 * Activates the specified archived project
	 * Response will show corresponding error if the project is already active
	 *
	 * @param int $projectId   Required. Project ID
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-projects.html
	 */
	public function activate_project(int $projectId): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/';
		$params = compact('action', 'page');

		return $this->request($params);
	}
}