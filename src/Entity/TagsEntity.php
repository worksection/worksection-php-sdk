<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class TagsEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'update_tags' => [
			'plus', 'minus'
		],
		'update_project_tags' => [
			'plus', 'minus'
		]
	];


	/**
	 * Returns data on status and label groups
	 *
	 * @param string $type        Optional. Group type, possible values: `status` or `label`
	 * @param string $access      Optional. Visibility of labels of a certain label group, possible values:
	 *                                      public  - available to all teams (including external client teams)
	 *                                      private - available only for your company teams
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function get_tag_groups(string $type = '', string $access = ''): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'type', 'access');
		$params = array_filter($params);

		return $this->request($params);
	}


	/**
	 * Returns data on project status and label groups
	 *
	 * @param string $type        Optional. Group type, possible values: `status` or `label`
	 * @param string $access      Optional. Visibility of labels of a certain label group, possible values:
	 *                                      `public`  - available to all teams (including external client teams)
	 *                                      `private` - available only for your company teams
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function get_project_tag_groups(string $type = '', string $access = ''): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'type', 'access');
		$params = array_filter($params);

		return $this->request($params);
	}


	/**
	 * Returns data on all statuses and labels along with the groups they belong to
	 *
	 * @param string $group   Optional. Returns data of one specified group. You can specify the name of the group or its ID <br>
	 *                                  (can be obtained through the same method from the returned group parameter or through get_tag_groups method) <br>
	 * @param string $type    Optional. Group type, possible values: `status` or `label`
	 * @param string $access  Optional. Visibility of labels of a certain label group, possible values:
	 *                                  `public`  - available to all teams (including external client teams)
	 *                                  `private` - available only for your company teams
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function get_tags(string $group = '', string $type = '', string $access = ''): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'group', 'type', 'access');
		$params = array_filter($params);

		return $this->request($params);
	}


	/**
	 * Returns data on all project statuses and labels along with the groups they belong to
	 *
	 * @param string $group Optional. Returns data of one specified group. You can specify the name of the group or its ID <br>
	 *                                   (can be obtained through the same method from the returned group parameter or through get_tag_groups method) <br>
	 * @param string $type Optional. Group type, possible values: `status` or `label`
	 * @param string $access Optional. Visibility of labels of a certain label group, possible values:
	 *                                   `public`  - available to all teams (including external client teams)
	 *                                   `private` - available only for your company teams
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function get_project_tags(string $group = '', string $type = '', string $access = ''): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'group', 'type', 'access');

		return $this->request($params);
	}


	/**
	 * Checks for the possible existence of status or label groups with specified names and creates new ones if necessary
	 *
	 * @param string $type    Required. Group type, possible values: `status`, `label`
	 * @param string $access  Required. Visibility of labels of a certain label group (statuses are always visible and have public value), possible values: <br>
	 *                                  `public`  - available to all teams (including external client teams) <br>
	 *                                  `private` - available only for your company teams <br>
	 * @param string $title   Required. Comma separated list of group names
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function add_tag_groups(string $type, string $access, string $title): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'type', 'access', 'title');

		return $this->request($params);
	}


	/**
	 * Checks for the possible existence of project status or label groups with specified names and creates new ones if necessary
	 *
	 * @param string $type    Required. Group type, possible values: `status`, `label`
	 * @param string $access  Required. Visibility of labels of a certain label group (statuses are always visible and have public value), possible values: <br>
	 *                                  `public`  - available to all teams (including external client teams) <br>
	 *                                  `private` - available only for your company teams <br>
	 * @param string $title   Required. Comma separated list of group names
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function add_project_tag_groups(string $type, string $access, string $title): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'type', 'access', 'title');

		return $this->request($params);
	}


	/**
	 * Checks for the possible existence of statuses or labels with specified names and creates new ones if necessary
	 *
	 * @param string $group   Required. Group, where statutes or labels will be created. You can specify the name of the group or its ID (can be obtained through [get_tag_groups] method)
	 * @param string $title   Required. Comma separated list of status or label names
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function add_tags(string $group, string $title): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'group', 'title');

		return $this->request($params);
	}


	/**
	 * Sets new and removes previous task/subtask statuses and labels
	 * Statuses and labels can be specified by their names (full match) or ID (can be obtained through get_tags method)
	 *
	 * @param int $taskId       Required. Task/subtask ID
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          plus  - comma separated list of status and label names to be set <br>
	 *                          minus - comma separated list of status and label names to be removed <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function update_tags(int $taskId, array $optional = []): array
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
	 * Checks for the possible existence of project statuses or labels with specified names and creates new ones if necessary
	 *
	 * @param string $group   Required. Group, where project statutes or labels will be created. You can specify the name of the group or its ID
	 * @param string $title   Required. Comma separated list of project status or label names
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function add_project_tags(string $group, string $title): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'group', 'title');

		return $this->request($params);
	}


	/**
	 * Sets new and removes previous project statuses and labels
	 * Statuses and labels can be specified by their names (full match) or ID (can be obtained through get_project_tags method)
	 *
	 * @param int $projectId    Required. Project ID
	 * @param array $optional   Optional. Optional parameters in array, possible keys and values:
	 *                          plus  - comma separated list of project status and label names to be set <br>
	 *                          minus - comma separated list of project status and label names to be removed <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-tags.html
	 */
	public function update_project_tags(int $projectId, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'id_project' => $projectId
		];

		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}
}