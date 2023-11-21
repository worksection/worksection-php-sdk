<?php
namespace Worksection\SDK;

use Worksection\SDK\Entity\CommentsEntity;
use Worksection\SDK\Entity\CostsEntity;
use Worksection\SDK\Entity\FilesEntity;
use Worksection\SDK\Entity\MembersEntity;
use Worksection\SDK\Entity\ProjectsEntity;
use Worksection\SDK\Entity\TagsEntity;
use Worksection\SDK\Entity\TasksEntity;
use Worksection\SDK\Exception\SdkException;

class EntityBuilder
{
	/**
	 * @var string
	 */
	private $_baseUri;


	/**
	 * @var string
	 */
	private $_adminToken;

	/**
	 * @var string
	 */
	private $_accessToken;


	/**
	 * @var string
	 */
	private $_refreshToken;


	/**
	 * @var string
	 */
	private $_clientId;


	/**
	 * @var string
	 */
	private $_clientSecret;


	/**
	 * @var bool
	 */
	private $_autoRefreshToken = false;


	/**
	 * @var EntityBuilder
	 */
	private static $_instance;


	/**
	 * @throws SdkException
	 */
	public function __construct(string $baseUri)
	{
		if (filter_var($baseUri, FILTER_VALIDATE_URL) === FALSE) {
			throw new SdkException('Invalid Url format.');
		}
		$this->_baseUri = $baseUri;
	}


	/**
	 * Set admin token for api authorization
	 *
	 * @param string $token
	 * @return void
	 */
	public function setAdminToken(string $token)
	{
		$this->_adminToken = $token;
	}


	/**
	 * Set access token for api oauth2 authorization
	 *
	 * @param string $token
	 * @return void
	 */
	public function setAccessToken(string $token)
	{
		$this->_accessToken = $token;
	}


	/**
	 * Set auto refreshing token for first no-authorization response (401 code)
	 *
	 * @param string $clientId      Required. Client id of oauth2 app
	 * @param string $clientSecret  Required. Client secret of oauth2 app
	 * @param string $token         Required. Refresh token for update access token
	 */
	public function setAutoRefreshToken(string $clientId, string $clientSecret, string $token): void
	{
		$this->_clientId = $clientId;
		$this->_clientSecret = $clientSecret;
		$this->_refreshToken = $token;
		$this->_autoRefreshToken = true;
	}


	/**
	 * @return ProjectsEntity
	 * @throws SdkException
	 */
	public function createProjectsEntity(): ProjectsEntity
	{
		return new ProjectsEntity($this->configWrap());
	}


	/**
	 * @return TasksEntity
	 * @throws SdkException
	 */
	public function createTasksEntity(): TasksEntity
	{
		return new TasksEntity($this->configWrap());
	}


	/**
	 * @return MembersEntity
	 * @throws SdkException
	 */
	public function createMembersEntity(): MembersEntity
	{
		return new MembersEntity($this->configWrap());
	}


	/**
	 * @return CommentsEntity
	 * @throws SdkException
	 */
	public function createCommentsEntity(): CommentsEntity
	{
		return new CommentsEntity($this->configWrap());
	}


	/**
	 * @return CostsEntity
	 * @throws SdkException
	 */
	public function createCostsEntity(): CostsEntity
	{
		return new CostsEntity($this->configWrap());
	}


	/**
	 * @return TagsEntity
	 * @throws SdkException
	 */
	public function createTagsEntity(): TagsEntity
	{
		return new TagsEntity($this->configWrap());
	}


	/**
	 * @return FilesEntity
	 * @throws SdkException
	 */
	public function createFilesEntity(): FilesEntity
	{
		return new FilesEntity($this->configWrap());
	}


	/**
	 * Return config array for entity building
	 *
	 * @return array
	 */
	private function configWrap(): array
	{
		$config = [
			'base_uri' => $this->_baseUri
		];
		if (isset($this->_adminToken)) {
			$config['admin_token'] = $this->_adminToken;
		} elseif (isset($this->_accessToken)) {
			$config['access_token'] = $this->_accessToken;
			if (isset($this->_autoRefreshToken)) {
				$config['auto_refresh_token'] = $this->_autoRefreshToken;
				$config['refresh_token'] = $this->_refreshToken;
				$config['client_id'] = $this->_clientId;
				$config['client_secret'] = $this->_clientSecret;
			}
		}

		return $config;
	}


	/**
	 * Gets the static instance of this class.
	 *
	 * @param string $baseUri
	 * @return EntityBuilder
	 * @throws SdkException
	 */
	public static function getInstance(string $baseUri): EntityBuilder
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new self($baseUri);
		}

		return self::$_instance;
	}


	/**
	 * Refreshing access token via OAuth2
	 *
	 * @param string $clientId      Required. Client id of oauth2 app
	 * @param string $clientSecret  Required. Client secret of oauth2 app
	 * @param string $token         Required. Refresh token for update access token
	 * @return array
	 * @throws SdkException
	 */
	public static function refreshToken(string $clientId, string $clientSecret, string $token): array
	{
		[$exec, $code] = Entity::curl(Entity::$oAuth2Url . '/oauth2/refresh', [
			'client_id' => $clientId,
			'client_secret' => $clientSecret,
			'grant_type' => 'refresh_token',
			'refresh_token' => $token
		]);
		return json_decode($exec, true);
	}
}