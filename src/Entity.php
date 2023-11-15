<?php
namespace Worksection\SDK;

use Worksection\SDK\Exception\SdkException;
use GuzzleHttp\Client as GuzzleHttpClient;

class Entity
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
	 * @var GuzzleHttpClient
	 */
	private $client;


	/**
	 * @var string
	 */
	protected $action;


	/**
	 * @throws SdkException
	 */
	public function __construct(array $config)
	{
		if (isset($config['base_uri'])) {
			$this->_baseUri = preg_replace('/\/$/', '', $config['base_uri']);
		} else {
			throw new SdkException('base_uri is required.');
		}
		if (isset($config['admin_token'])) {
			$this->_adminToken = $config['admin_token'];
		} elseif (isset($config['access_token'])) {
			$this->_accessToken = $config['access_token'];
			if (isset($config['refresh_token'])) {
				$this->_refreshToken = $config['refresh_token'];
			}
		} else {
			throw new SdkException('admin_token or access_token are required, use setAdminToken, setAccessToken methods.');
		}


		$this->client = new GuzzleHttpClient([
			'base_uri' => $this->_baseUri,
			'timeout' => 5.0
		]);
	}


	/**
	 * @param array $params
	 * @return array
	 * @throws SdkException
	 */
	protected function request(array $params): array
	{
		if (!isset($params['action'])) {
			throw new SdkException('action is required.');
		}

		if ($this->_adminToken) {
			if (isset($params['page'])) {
				$hash = md5($params['page'] . $params['action'] . $this->_adminToken);
			} else {
				$hash = md5($params['action'] . $this->_adminToken);
			}
			$params['hash'] = $hash;
			$response = $this->client->request('POST', '/api/admin/v2', [
				'query' => $params
			]);
		} else {
			$params['access_token'] = $this->_accessToken;
			$response = $this->client->request('POST', 'api/oauth2/', [
				'query' => $params
			]);
		}

		$content = $response->getBody()->getContents();
		$result = \json_decode($content, true);
		if ($result && isset($result['status']) && ($result['status'] == 'ok' || $result['status'] == 'error')) {
			return $result;
		} else {
			throw new SdkException('Something wrong, return wrong string: ' . $content);
		}
	}
}