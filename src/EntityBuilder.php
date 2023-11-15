<?php
namespace Worksection\SDK;

use Worksection\SDK\Entity\ProjectsEntity;
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
	 * Set refresh token for api oauth2 reauthorization
	 *
	 * @param string $token
	 * @return void
	 */
	public function setRefreshToken(string $token)
	{
		$this->_refreshToken = $token;
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
	 * Return config array for entity building
	 *
	 * @return array
	 */
	private function configWrap(): array
	{
		$config = [
			'base_uri' => $this->_baseUri
		];
		if ($this->_adminToken) {
			$config['admin_token'] = $this->_adminToken;
		} elseif ($this->_accessToken) {
			$config['access_token'] = $this->_accessToken;
			if ($this->_refreshToken) {
				$config['refresh_token'] = $this->_refreshToken;
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
}