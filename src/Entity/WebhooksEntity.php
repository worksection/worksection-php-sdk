<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class WebhooksEntity extends Entity
{
	/**
	 * Returns list of webhooks for account
	 *
	 * @return array
	 * @throws SdkException
	 * @see https://worksection.com/en/faq/webhooks.html
	 */
	public function get_webhooks(): array
	{
		$params = ['action' => __FUNCTION__];

		return $this->request($params);
	}


	/**
	 * Add new webhooks into account with params
	 *
	 * @param string $url            Required. Webhook URL
	 * @param string $events         Required. The event settings that will send notifications to the specified URL are separated by commas.
	 *                                         Possible values: post_task, post_comment, post_project, update_task, update_comment, update_project, delete_task, delete_comment, close_task
	 * @param string $projectIds     Optional. Project IDs separated by commas, specify if you want to limit the sending of events for certain projects
	 * @param string $httpUser       Optional. User when using basic access authentication
	 * @param string $httpPassword   Optional. Password when using basic access authentication
	 * @return array
	 * @throws SdkException
	 * @see https://worksection.com/en/faq/webhooks.html
	 */
	public function add_webhook(string $url, string $events, string $projectIds = '', string $httpUser = '', string $httpPassword = ''): array
	{
		$params = [
			'action' => __FUNCTION__,
			'url' => $url,
			'events' => $events
		];
		if ($projectIds) $params['projects'] = $projectIds;
		if ($httpUser) $params['http_user'] = $httpUser;
		if ($httpPassword) $params['http_pass'] = $httpPassword;

		return $this->request($params);
	}


	/**
	 * Delete webhook via id
	 *
	 * @param int $webhookId
	 * @return array
	 * @throws SdkException
	 * @see https://worksection.com/en/faq/webhooks.html
	 */
	public function delete_webhook(int $webhookId): array
	{
		$params = [
			'action' => __FUNCTION__,
			'id' => $webhookId
		];

		return $this->request($params);
	}
}
