<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class CommentsEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'post_comment' => [
			'email_user_from', 'hidden', 'mention'
		]
	];


	/**
	 * Getting comments of tasks or subtasks
	 *
	 * @param int $taskId      Required. Task ID
	 * @param string $extra    Optional. Returns information about files attached to comments. Possible value: `files`
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-comments.html
	 */
	public function get_comments(int $taskId, string $extra = ''): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id_task' => $taskId,
			'extra' => $extra
		];
		$params = array_filter($params);

		return $this->request($params);
	}


	/**
	 * Creating comments in tasks or subtasks
	 * This request allows comment file attaching (see https://worksection.com/en/faq/api-files.html)
	 *
	 * @param int $taskId             Required. Task ID
	 * @param string $text            Required. Comment text
	 * @param array $optional         Optional. Optional parameters in array, possible keys and values:
	 *                                email_user_from - comment author email (when use access token - will be set automatically) <br>
	 *                                hidden - comma separated list of user emails, who will have access to this comment, while it will be hidden for others <br>
	 *                                mention - comma separated list of user emails, who will be mentioned at the end* of the comment <br>
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-comments.html
	 */
	public function post_comment(int $taskId, string $text, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'text' => $text,
			'id_task' => $taskId
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}
}