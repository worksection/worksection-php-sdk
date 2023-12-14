<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class TimersEntity extends Entity
{
	/**
	 * Returns authorized user's (oauth2) timer
	 * !! User method (only for access token) !!
	 *
	 * @return array
	 * @throws SdkException
	 */
	public function get_my_timer(): array
	{
		$this->checkAccessToken();
		$params = ['action' => __FUNCTION__];

		return $this->request($params);
	}


	/**
	 * Starts authorized user's (oauth2) timer in selected task
	 * !! User method (only for access token) !!
	 *
	 * @param int $taskId   Required. Task ID
	 * @return array
	 * @throws SdkException
	 */
	public function start_my_timer(int $taskId): array
	{
		$this->checkAccessToken();
		$params = [
			'action' => __FUNCTION__,
			'id_task' => $taskId
		];

		return $this->request($params);
	}


	/**
	 * Stops authorized user's (oauth2) active timer
	 * !! User method (only for access token) !!
	 *
	 * @param string $comment   Optional. Message for timer logs
	 * @return array
	 * @throws SdkException
	 */
	public function stop_my_timer(string $comment = ''): array
	{
		$this->checkAccessToken();
		$params = [
			'action' => __FUNCTION__,
			'comment' => $comment
		];

		return $this->request($params);
	}


	/**
	 * Delete authorized user's (oauth2) active timer
	 * !! User method (only for access token) !!
	 *
	 * @return array
	 * @throws SdkException
	 */
	public function delete_my_timer(): array
	{
		$this->checkAccessToken();
		$params = ['action' => __FUNCTION__];

		return $this->request($params);
	}


	/**
	 * Returns data on running timers: their ID, start time, timer value and who started them
	 * !! Admin method (only for admin token) !!
	 *
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-costs.html
	 */
	public function get_timers(): array
	{
		$this->checkAdminToken();
		$action = __FUNCTION__;
		$params = compact('action');

		return $this->request($params);
	}


	/**
	 * Stops the specified running timer and saves its data
	 * !! Admin method (only for admin token) !!
	 *
	 * @param int $timer    Required. Timer ID (can be obtained through get_timers method)
	 * @return array
	 * @throws SdkException
	 * @link https://worksection.com/en/faq/api-costs.html
	 */
	public function stop_timer(int $timer): array
	{
		$this->checkAdminToken();
		$action = __FUNCTION__;
		$params = compact('action', 'timer');

		return $this->request($params);
	}


	/**
	 * @throws SdkException
	 */
	private function checkAccessToken(): void
	{
		if (!isset($this->_accessToken)) {
			throw new SdkException('Method ' . __FUNCTION__ . ' is available for OAuth2 only.');
		}
	}


	/**
	 * @throws SdkException
	 */
	private function checkAdminToken(): void
	{
		if (!isset($this->_adminToken)) {
			throw new SdkException('Method ' . __FUNCTION__ . ' is available for admin token only.');
		}
	}
}
