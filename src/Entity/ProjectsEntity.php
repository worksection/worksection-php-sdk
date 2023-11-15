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
		]
	];

	/**
	 * @param string $filter   Optional. Returns data for a project in a specified status (active/sleeping/archived)
	 *                         Possible values: `active`, `pending`, `archive`
	 * @param string $extra    Optional. Returns additional project data (can be specified with commas)
	 *                         Possible values: `text`, `options`, `users`
	 * @return string
	 * @throws SdkException
	 */
	public function get_projects(string $filter = '', string $extra = ''): string
	{
		$action = __FUNCTION__;
		$params = compact('action', 'filter', 'extra');
		$params = array_filter($params);

		$response = $this->request($params);
		return $response->getBody()->getContents();
	}



	/**
	 * @param int $projectId   Required. Project ID
	 * @return string
	 * @throws SdkException
	 */
	public function close_project(int $projectId): string
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/';
		$params = compact('action', 'page');

		$response = $this->request($params);
		return $response->getBody()->getContents();
	}


	/**
	 * @param string $title     Required. Project name
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          email_user_from - project author email
	 *                          email_manager - project manager email
	 *                          email_user_to - user email, who will be set as a task executive by default
	 *                                          Possible values: `ANY` for "Anyone", `NOONE` or not specified for "Executive isn't assigned"
	 *                          members       - comma separated list of project member emails
	 *                          text          - project description
	 *                          company       - folder name, where the project will be located
	 *                          datestart     - start date in DD.MM.YYYY format
	 *                          dateend       - due date or end date in DD.MM.YYYY format
	 *                          extra         - returns a dataset on project restriction options.
	 *                                          Possible value: `options`
	 *                          max_time      - time estimates
	 *                          max_money     - financial estimates
	 *                          tags          - set project statuses/labels. You can specify names (if they are unique) or their IDs (can be obtained throught get_project_tags method)
	 *                                          Example value: `TAG1,TAG2`
	 *
	 * 							List of project restriction options (set value `1` to enable):
	 *                          options.allow_close        - mark task as done
	 *                          options.allow_give         - transfer the responsibility
	 *                          options.allow_term         - change the task terms
	 *                          options.allow_limit        - change the estimates
	 *                          options.require_term       - terms
	 *                          options.require_tag        - stages and labels
	 *                          options.require_limit      - estimates
	 *                          options.require_hidden     - visibility
	 *                          options.deny_comments_edit - edit and delete comments
	 *                          options.deny_task_edit     - edit and delete tasks
	 *                          options.deny_task_delete   - delete tasks
	 *                          options.time_require       - mark as done with costs only
	 *                          options.time_today         - add for today only
	 *                          options.timer_only         - add from timer only
	 * @return string
	 * @throws SdkException
	 */
	public function post_project(string $title, array $optional = []): string
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

		$response = $this->request($params);
		return $response->getBody()->getContents();
	}
}