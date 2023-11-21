<?php
namespace Worksection\SDK\Entity;

use Worksection\SDK\Entity;
use Worksection\SDK\Exception\SdkException;

class MembersEntity extends Entity
{
	public const ENTITY_PARAMS = [
		'add_user' => [
			'first_name', 'last_name', 'title', 'group', 'department', 'role'
		],
		'add_contact' => [
			'title', 'group', 'phone', 'phone2', 'phone3', 'phone4', 'address', 'address2'
		]
	];



	/**
	 * Returns account users data
	 *
	 * @return array
	 * @throws SdkException
	 */
	public function get_users(): array
	{
		$action = __FUNCTION__;
		$params = compact('action');

		return $this->request($params);
	}



	/**
	 * Returns account contacts data
	 *
	 * @return array
	 * @throws SdkException
	 */
	public function get_contacts(): array
	{
		$action = __FUNCTION__;
		$params = compact('action');

		return $this->request($params);
	}



	/**
	 * A new user will be invited to the account (to the main team if group parameter is not specified)
	 *
	 * @param string $email    Required. User email
	 * @param array $optional  Optional. Optional parameters in array, possible keys and values:
	 *                         first_name - user first name
	 *                         last_name  - user last name
	 *                         title      - user position
	 *                         group      - team name
	 *                         department - department name
	 *                         role       - user role. Available options: `user`, `guest`, `reader`
	 * @return array
	 * @throws SdkException
	 */
	public function add_user(string $email, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'email' => $email
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * The contact does not receive an invitation to the account
	 *
	 * @param string $email    Required. Email of the contact
	 * @param string $name     Required. First and last name of the contact
	 * @param array $optional  Optional. Optional parameters in array, possible keys and values:
	 *                         title - position
	 *                         group - folder name
	 *                         phone - phone number
	 *                         phone2 - office phone number
	 *                         phone3 - mobile phone number
	 *                         phone4 - home phone number
	 *                         address - address
	 *                         address2 - additional address
	 * @return array
	 * @throws SdkException
	 */
	public function add_contact(string $email, string $name, array $optional = []): array
	{
		$action = __FUNCTION__;
		$params = [
			'action' => $action,
			'email' => $email,
			'name' => $name
		];
		foreach (self::ENTITY_PARAMS[$action] as $value) {
			if (isset($optional[$value]) && $optional[$value]) {
				$params[$value] = $optional[$value];
			}
		}

		return $this->request($params);
	}



	/**
	 * Subscribing a user from a task
	 *
	 * @param int $projectId      Required. Project ID
	 * @param int $taskId         Required. Task ID
	 * @param string $emailUser   Required. User email, who needs to be subscribed from a task
	 * @return array
	 * @throws SdkException
	 */
	public function subscribe(int $projectId, int $taskId, string $emailUser): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		$params = compact('action', 'page', 'emailUser');

		return $this->request($params);
	}



	/**
	 * Unsubscribing a user from a task
	 *
	 * @param int $projectId      Required. Project ID
	 * @param int $taskId         Required. Task ID
	 * @param string $emailUser   Required. User email, who needs to be unsubscribed from a task
	 * @return array
	 * @throws SdkException
	 */
	public function unsubscribe(int $projectId, int $taskId, string $emailUser): array
	{
		$action = __FUNCTION__;
		$page = '/project/' . $projectId . '/' . $taskId . '/';
		$params = compact('action', 'page', 'emailUser');

		return $this->request($params);
	}



	/**
	 * Checks for the possible existence of such team and creates a new one if necessary
	 *
	 * @param string $title   Required. Team name
	 * @param mixed $client   Optional. `1` for an external client team and `0` for your company team
	 * @return array
	 * @throws SdkException
	 */
	public function add_user_group(string $title, int $client = null): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'title');
		if ($client !== null) $params['client'] = $client;

		return $this->request($params);
	}



	/**
	 * Checks for the possible existence of such folder and creates a new one if necessary
	 *
	 * @param string $title   Required. Folder name for contacts
	 * @return array
	 * @throws SdkException
	 */
	public function add_contact_group(string $title): array
	{
		$action = __FUNCTION__;
		$params = compact('action', 'title');

		return $this->request($params);
	}



	/**
	 * Returns data on account user teams
	 *
	 * @return array
	 * @throws SdkException
	 */
	public function get_user_groups(): array
	{
		$action = __FUNCTION__;
		$params = compact('action');

		return $this->request($params);
	}



	/**
	 * Returns data on account contact folders
	 *
	 * @return array
	 * @throws SdkException
	 */
	public function get_contact_groups(): array
	{
		$action = __FUNCTION__;
		$params = compact('action');

		return $this->request($params);
	}
}
