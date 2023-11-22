<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class CommentsEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'post_comment' => [
			'hidden', 'mention'
		]
	];


	/**
	 * Getting comments of tasks or subtasks
	 *
	 * @param int $projectId   Required. Project ID
	 * @param int $taskId      Required. Task ID
	 * @param int $subtaskId   Optional. Subtask ID
	 * @param string $extra    Optional. Returns information about files attached to comments. Possible value: `files`
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-comments.html
	 */
	public function get_comments(int $projectId, int $taskId, int $subtaskId = 0, string $extra = ''): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = compact('action', 'page', 'extra');
		$params = array_filter($params);

		return $this->request($params);
	}



	/**
	 * Creating comments in tasks or subtasks
	 * This request allows comment file attaching (see https://worksection.com/en/faq/api-files.html)
	 *
	 * @param string $emailUserFrom   Required. Comment author email
	 * @param string $text            Required. Comment text
	 * @param int $projectId          Required. Project ID
	 * @param int $taskId             Required. Task ID
	 * @param int $subtaskId          Optional. Subtask ID
	 * @param array $optional         Optional. Optional parameters in array, possible keys and values:
	 *                                hidden - comma separated list of user emails, who will have access to this comment, while it will be hidden for others <br>
	 *                                mention - comma separated list of user emails, who will be mentioned at the end* of the comment <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-comments.html
	 */
	public function post_comment(string $emailUserFrom, string $text, int $projectId, int $taskId, int $subtaskId = 0, array $optional = []): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		if ($subtaskId) $page .= $subtaskId . '/';
		$params = [
			'action' => $action,
			'page'   => $page,
			'email_user_from' => $emailUserFrom,
			'text' => $text
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}
}