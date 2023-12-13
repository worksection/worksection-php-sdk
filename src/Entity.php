<?php
namespace Worksection\SDK;

use Worksection\SDK\Exception\SdkException;
use Exception;
use function json_decode;

class Entity
{
	/**
	 * @var string
	 */
	public static $oAuth2Url = 'https://worksection.com';


	/**
	 * @var string
	 */
	protected $_baseUri;


	/**
	 * @var string
	 */
	protected $_adminToken;


	/**
	 * @var string
	 */
	protected $_accessToken;


	/**
	 * @var string
	 */
	protected $_refreshToken;


	/**
	 * @var string
	 */
	protected $_clientId;


	/**
	 * @var string
	 */
	protected $_clientSecret;


	/**
	 * @var bool
	 */
	protected $_autoRefreshToken;


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
			if (isset($config['auto_refresh_token'])) {
				$this->_autoRefreshToken = $config['auto_refresh_token'];
				$this->_refreshToken = $config['refresh_token'];
				$this->_clientId = $config['client_id'];
				$this->_clientSecret = $config['client_secret'];
			}
		} else {
			throw new SdkException('admin_token or access_token are required, use setAdminToken, setAccessToken methods.');
		}
	}


	/**
	 * Return request response. For OAuth2 (using access_token), if autoRefreshToken is enabled:
	 *                          - make request with new access_token (obtained through oauth2/refresh method)
	 *                          - return new access_token and refresh_token in array response
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
			$params['hash'] = md5(http_build_query($params) . $this->_adminToken);
			$uri = '/api/admin/v2';
		} else {
			$params['access_token'] = $this->_accessToken;
			$uri = '/api/oauth2/';
		}

		[$exec, $code] = self::curl($this->_baseUri . $uri, $params);
		$request = json_decode($exec, true);

		if ($code == 401 && $request['message'] == 'Access token is expired' && $this->_autoRefreshToken) {
			[$exec, $code] = self::curl(self::$oAuth2Url . '/oauth2/refresh', [
				'client_id' => $this->_clientId,
				'client_secret' => $this->_clientSecret,
				'grant_type' => 'refresh_token',
				'refresh_token' => $this->_refreshToken
			]);
			$result = $request;

			$request = json_decode($exec, true);
			if ($request['access_token'] && $request['refresh_token']) {
				$params['access_token'] = $request['access_token'];
				$params['refresh_token'] = $request['refresh_token'];
				[$exec, $code] = self::curl($this->_baseUri . $uri, $params);
				$request = json_decode($exec, true);
				if (isset($request['status']) && ($request['status'] == 'ok' || $request['status'] == 'error')) {
					$result = $request;
					$result['access_token'] = $params['access_token'];
					$result['refresh_token'] = $params['refresh_token'];
				} else {
					throw new SdkException('Something wrong, return wrong string: ' . $exec);
				}
			} elseif ($request['error'] == 'invalid_request' && $request['errorDescription']) {
				$result['refresh_status'] = $request['error'];
				$result['refresh_message'] = $request['errorDescription'];
			}
		} elseif (isset($request['status']) && ($request['status'] == 'ok' || $request['status'] == 'error')) {
			$result = $request;
		} else {
			throw new SdkException('Something wrong, return wrong string: ' . $exec);
		}

		return $result;
	}


	/**
	 * @return string
	 * @throws SdkException
	 */
	protected function get_self_email(): string
	{
		$result = $this->request([
			'action' => 'get_users',
			'is_me_only' => true
		]);
		return $result['data']['email'] ?? '';
	}


	/**
	 * @param string $url
	 * @param array $params
	 * @return array
	 * @throws SdkException
	 */
	public static function curl(string $url, array $params): array
	{
		try {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			$exec = curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
		} catch (Exception $e) {
			throw new SdkException('CURL error, return wrong string: ' . $e->getMessage());
		}

		return [$exec, $code];
	}


	/**
	 * @param string $url
	 */
	public static function setOAuth2Url(string $url): void
	{
		self::$oAuth2Url = $url;
	}
}