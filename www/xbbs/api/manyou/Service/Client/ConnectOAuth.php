<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ConnectOAuth.php 32196 2012-11-28 02:34:36Z liudongdong $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

Cloud::loadFile('Service_Connect');
Cloud::loadFile('Service_Client_OAuth');

class Cloud_Service_Client_ConnectOAuth extends Cloud_Service_Client_OAuth {

	private $_requestTokenURL = 'http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token';

	private $_oAuthAuthorizeURL = 'http://openapi.qzone.qq.com/oauth/qzoneoauth_authorize';

	private $_accessTokenURL = 'http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token';

	private $_getUserInfoURL = 'http://openapi.qzone.qq.com/user/get_user_info';

	private $_addShareURL = 'http://openapi.qzone.qq.com/share/add_share';

	private $_addWeiBoURL = 'http://openapi.qzone.qq.com/wb/add_weibo';

	private $_addTURL = 'http://openapi.qzone.qq.com/t/add_t';

	private $_addPicTURL = 'http://openapi.qzone.qq.com/t/add_pic_t';

	private $_getReportListURL = 'http://openapi.qzone.qq.com/t/get_repost_list';

	// ��������ʧ�ܻ򷵻ؿ�ʱ�׳����쳣
	const RESPONSE_ERROR = 999;
	const RESPONSE_ERROR_MSG = 'request failed';

	/**
	 * $_instance
	 */
	protected static $_instance;

	/**
	 * getInstance
	 *
	 * @return self
	 */
	public static function getInstance($connectAppId = '', $connectAppKey = '', $apiIp = '') {

		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self($connectAppId = '', $connectAppKey = '', $apiIp = '');
		}

		return self::$_instance;
	}

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct($connectAppId = '', $connectAppKey = '', $apiIp = '') {

		if(!$connectAppId || !$connectAppKey) {
			global $_G;
			$connectAppId = $_G['setting']['connectappid'];
			$connectAppKey = $_G['setting']['connectappkey'];
		}
		$this->setAppkey($connectAppId, $connectAppKey);
		if(!$this->_appKey || !$this->_appSecret) {
			throw new Exception('connectAppId/connectAppKey Invalid', __LINE__);
		}

		if(!$apiIp) {
			global $_G;
			$apiIp = $_G['setting']['connect_api_ip'] ? $_G['setting']['connect_api_ip'] : '';
		}

		if($apiIp) {
			$this->setApiIp($apiIp);
		}
	}

	/**
	 * connectGetRequestToken
	 * 	��Qzone����request��������ʱtoken
	 *
	 * @param string $clientIp �û���IP��ַ����ѡ��
	 *
	 * @return array
	 *  + oauth_token => *************	δ��Ȩ����ʱtoken
	 *  + oauth_token_secret => *************	δ��Ȩ����ʱtoken��Ӧ����Կ
	 */
	public function connectGetRequestToken($callback, $clientIp = '') {

		//$extra = $clientIp ? array('oauth_client_ip' => $clientIp) : array();
		$extra = array();

		$extra['oauth_callback'] = rawurlencode($callback);

		if ($clientIp) {
			$extra['oauth_client_ip'] = $clientIp;
		}

		$this->setTokenSecret('');
		$response = $this->_request($this->_requestTokenURL, $extra);

		parse_str($response, $params);
		if($params['oauth_token'] && $params['oauth_token_secret']) {
			return $params;
		} else {
			$params['error_code'] = $params['error_code'] ? $params['error_code'] : self::RESPONSE_ERROR;
			throw new Exception($params['error_code'], __LINE__);
		}

	}

	/**
	 * getOAuthAuthorizeURL
	 * ��ȡOAuthAuthorizeURL
	 *
	 * @param string $requestToken ͨ��connectGetRequestToken()��ȡ��requestToken
	 *
	 * @return string �����û���Ȩҳ��OAuthAuthorizeURL
	 */
	public function getOAuthAuthorizeURL($requestToken) {

		$params = array(
			'oauth_consumer_key' => $this->_appKey,
			'oauth_token' => $requestToken,
			//'oauth_callback' => rawurlencode($callback),
		);
		$utilService = Cloud::loadClass('Service_Util');
		$oAuthAuthorizeURL = $this->_oAuthAuthorizeURL.'?'.$utilService->httpBuildQuery($params, '', '&');

		return $oAuthAuthorizeURL;
	}

	private function _connectIsValidOpenid($openId, $timestamp, $sig) {

		$key = $this->_appSecret;
		$str = $openId.$timestamp;
		$signature = $this->customHmac($str, $key);
		return $sig == $signature;
	}

	/**
	 * connectGetAccessToken
	 * ��ȡ����Qzone����Ȩ�޵�Access Token
	 *
	 * @param array $params Qzone�����û���ת��callback���صĲ�����ɵ�����
	 *  + oauth_token => *************	�û���Ȩ����requestToken
	 *  + openid => *************	��QQ����һһ��Ӧ������OpenAPIʱ�����OpenID
	 *  + oauth_signature => *************	��֤openid�Լ���Դ�Ŀɿ��Ե�ǩ��ֵ
	 *  + timestamp => *************	openid��timestamp
	 *  + oauth_vericode => *************	�û���ȨrequestToken�ش���������֤��
	 * @param string $requestTokenSecret requestToken��Ӧ��requestTokenSecret
	 *
	 * @return array
	 *  + oauth_signature => *************
	 *  + oauth_token => *************	���з���Ȩ�޵�access_token
	 *  + oauth_token_secret => *************	access_token����Կ
	 *  + openid => *************	��QQ����һһ��Ӧ������OpenAPIʱ�����OpenID
	 *  + timestamp => *************	openid��ʱ���
	 */
	public function connectGetAccessToken($params, $requestTokenSecret) {

		// debug ��֤��Դ�ɿ�
		if(!$this->_connectIsValidOpenid($params['openid'], $params['timestamp'], $params['oauth_signature'])) {
			throw new Exception('openId signature invalid', __LINE__);
		}

		if(!$params['oauth_token'] || !$params['oauth_vericode']) {
			throw new Exception('requestToken/vericode invalid', __LINE__);
		}

		$extra = array(
			'oauth_token' => $params['oauth_token'],
			'oauth_vericode' => $params['oauth_vericode'],
		);
		$this->setTokenSecret($requestTokenSecret);
		$response = $this->_request($this->_accessTokenURL, $extra);

		parse_str($response, $result);
		if($result['oauth_token'] && $result['oauth_token_secret'] && $result['openid']) {
			return $result;
		} else {
			$result['error_code'] = $result['error_code'] ? $result['error_code'] : self::RESPONSE_ERROR;
			throw new Exception($result['error_code'], __LINE__);
		}
	}

	/**
	 * connectGetUserInfo
	 * ��ȡ��¼�û���Ϣ��Ŀǰ�ɻ�ȡ�û��ǳƼ�ͷ����Ϣ
	 *
	 * @param string $openId ����OpenAPIʱ�����OpenID
	 * @param string $accessToken ���з���Ȩ�޵�access_token
	 * @param string $accessTokenSecret access_token����Կ
	 *
	 * @return array
	 *  + nickname => *************		QQ�ǳ�
	 *  + figureurl => *************	ͷ����Ϣ���ߴ�30
	 *  + figureurl_1 => *************	ͷ����Ϣ���ߴ�50
	 *  + figureurl_2 => *************	ͷ����Ϣ���ߴ�100
	 *  + gender => *************	�Ա�
	 */
	public function connectGetUserInfo($openId, $accessToken, $accessTokenSecret) {

		$extra = array(
			'oauth_token' => $accessToken,
			'openid' => $openId,
			'format' => 'xml',
		);
		$this->setTokenSecret($accessTokenSecret);
		$response = $this->_request($this->_getUserInfoURL, $extra);

		$data = $this->_xmlParse($response);
		if(isset($data['ret']) && $data['ret'] == 0) {
			return $data;
		} else {
			throw new Exception($data['msg'], $data['ret']);
		}
	}

	private function _request($requestURL, $extra = array(), $oauthMethod = 'GET', $multi) {

		if(!$this->_appKey || !$this->_appSecret) {
			throw new Exception('appKey or appSecret not init');
		}

		if(strtoupper(CHARSET) != 'UTF-8') {
			foreach((array)$extra as $k => $v) {
				$extra[$k] = diconv($v, CHARSET, 'UTF-8');
			}
		}

		return $this->getRequest($requestURL, $extra, $oauthMethod, $multi);
	}

	private function _xmlParse($data) {

		$connectService = Cloud::loadClass('Service_Connect');
		$data = $connectService->connectParseXml($data);
		if (strtoupper(CHARSET) != 'UTF-8') {
			$data = $this->_iconv($data, 'UTF-8', CHARSET);
		}

		if(!isset($data['ret']) && !isset($data['errcode'])) {
			$data = array(
				'ret' => self::RESPONSE_ERROR,
				'msg' => self::RESPONSE_ERROR_MSG
			);
		}

		return $data;
	}

	private function _iconv($data, $inputCharset, $outputCharset) {
		if (is_array($data)) {
			foreach($data as $key => $val) {
				$value = array_map(array(__CLASS__, '_iconv'), array($val), array($inputCharset), array($outputCharset));
				$result[$key] = $value[0];
			}
		} else {
			$result = diconv($data, $inputCharset, $outputCharset);
		}
		return $result;

	}

	/**
	 * connectAddShare
	 * ���û���Ȩ������£��������û������巢��һ����̬��feeds����QQ�ռ��У�չ�ָ�����
	 *
	 * @param string $openId ����OpenAPIʱ�����OpenID
	 * @param string $accessToken ���з���Ȩ�޵�access_token
	 * @param string $accessTokenSecret access_token����Կ
	 * @param array $params ˽�в�����ɵ�����
	 *  + title		���룬feed�ı���
	 *  + url		���룬��http:// ��ͷ�ķ���������ҳ��Դ������
	 *  + comment	�û��������ݣ�Ҳ�з������ʱ�ķ������ɣ��40�������֣��������ֻᱻ�ضϡ�
	 *  + summary	���������ҳ��Դ��ժҪ���ݣ���������ҳ�ĸ�Ҫ�������80�������֣��������ֻᱻ�ضϡ�
	 *  + images	���������ҳ��Դ�Ĵ�����ͼƬ���ӣ�����http://��ͷ����������255�ַ���
	 *  + source	����ĳ�����ȡֵ˵����1.ͨ����ҳ 2.ͨ���ֻ� 3.ͨ����� 4.ͨ��IPHONE 5.ͨ�� IPAD��
	 *  + type		�������ݵ����͡�4��ʾ��ҳ��5��ʾ��Ƶ��type=5ʱ�����봫��playurl����
	 *  + playurl	��������Ϊ256�ֽڡ�����type=5��ʱ����Ч��
	 *  + nswb		ֵΪ1ʱ����ʾ����Ĭ��ͬ����΢��������ֵ���߲����˲�����ʾĬ��ͬ����΢����
	 *
	 * @return array
	 */
	public function connectAddShare($openId, $accessToken, $accessTokenSecret, $params) {
		if(!$params['title'] || !$params['url']) {
			throw new Exception('Required Parameter Missing');
		}

		$paramsName = array('title', 'url', 'comment', 'summary', 'images', 'source', 'type', 'playurl', 'nswb');

		if($params['title']) {
			$params['title'] = cutstr($params['title'], 72, '');
		}

		if($params['comment']) {
			$params['comment'] = cutstr($params['comment'], 80, '');
		}

		if($params['summary']) {
			$params['summary'] = cutstr($params['summary'], 160, '');
		}

		if($params['images']) {
			$params['images'] = cutstr($params['images'], 255, '');
		}

		if($params['playurl']) {
			$params['playurl'] = cutstr($params['playurl'], 256, '');
		}

		$extra = array(
			'oauth_token' => $accessToken,
			'openid' => $openId,
			'format' => 'xml',
		);

		foreach($paramsName as $name) {
			if($params[$name]) {
				$extra[$name] = $params[$name];
			}
		}

		$this->setTokenSecret($accessTokenSecret);
		$response = $this->_request($this->_addShareURL, $extra, 'POST');

		$data = $this->_xmlParse($response);
		if(isset($data['ret']) && $data['ret'] == 0) {
			return $data;
		} else {
			throw new Exception($data['msg'], $data['ret']);
		}

	}

	/**
	 * connectAddPicT
	 * �ϴ�һ��ͼƬ��������һ����Ϣ����Ѷ΢��ƽ̨�ϡ�
	 *
	 * @param string $openId ����OpenAPIʱ�����OpenID
	 * @param string $accessToken ���з���Ȩ�޵�access_token
	 * @param string $accessTokenSecret access_token����Կ
	 * @param array $params ˽�в�����ɵ�����
	 *  + content		���룬��ʾҪ�����΢������
	 *  + pic			���룬ͼƬ·��
	 *  + remote		boolean ��ʶͼƬΪԶ�̵�ַ���Ǳ���·��
	 *  + clientip		�û�ip
	 *  + jing			�û����ڵ���λ�õľ���
	 *  + wei			�û����ڵ���λ�õ�γ��
	 *  + syncflag		��ʶ�Ƿ񽫷�����΢��ͬ����QQ�ռ䣨0��ͬ���� 1����ͬ��������Ĭ��Ϊ0��
	 *
	 * @return array
	 */
	public function connectAddPicT($openId, $accessToken, $accessTokenSecret, $params) {
		if(!$params['content'] || !$params['pic']) {
			throw new Exception('Required Parameter Missing');
		}

		$paramsName = array('content', 'pic', 'clientip', 'jing', 'wei', 'syncflag');
		$extra = array(
			'oauth_token' => $accessToken,
			'openid' => $openId,
			'format' => 'xml',
		);

		foreach($paramsName as $name) {
			if($params[$name]) {
				$extra[$name] = $params[$name];
			}
		}
		$pic = $extra['pic'];
		unset($extra['pic']);

		$this->setTokenSecret($accessTokenSecret);
		$response = $this->_request($this->_addPicTURL, $extra, 'POST', array('pic' => $pic, 'remote' => $params['remote'] ? true : false));

		$data = $this->_xmlParse($response);
		if(isset($data['ret']) && $data['ret'] == 0) {
			return $data;
		} else {
			throw new Exception($data['msg'], $data['ret']);
		}
	}


	public function connectAddT($openId, $accessToken, $accessTokenSecret, $params) {
		if(!$params['content']) {
			throw new Exception('Required Parameter Missing');
		}

		$paramsName = array('content', 'clientip', 'jing', 'wei');
		$extra = array(
			'oauth_token' => $accessToken,
			'openid' => $openId,
			'format' => 'xml',
		);

		foreach($paramsName as $name) {
			if($params[$name]) {
				$extra[$name] = $params[$name];
			}
		}

		$this->setTokenSecret($accessTokenSecret);
		$response = $this->_request($this->_addTURL, $extra, 'POST');

		$data = $this->_xmlParse($response);
		if(isset($data['ret']) && $data['ret'] == 0) {
			return $data;
		} else {
			throw new Exception($data['msg'], $data['ret']);
		}

	}

	/**
	 * connectGetRepostList
	 * ��ȡһ��΢����ת����������Ϣ�б�
	 *
	 * @param string $openId ����OpenAPIʱ�����OpenID
	 * @param string $accessToken ���з���Ȩ�޵�access_token
	 * @param string $accessTokenSecret access_token����Կ
	 * @param array $params ˽�в�����ɵ�����
	 *  + flag		���룬��ʶ��ȡ����ת���б��ǵ����б�0����ȡת���б�1����ȡ�����б�2��ת���б�͵����б���ȡ��
	 *  + rootid		���룬ת���������Դ΢����ID��
	 *  + pageflag		���룬��ҳ��ʶ��0����һҳ��1�����·�ҳ��2�����Ϸ�ҳ��
	 *  + pagetime		���룬��ҳ��ʼʱ�䡣��һҳ��0�����·�ҳ����һ�����󷵻ص����һ����¼ʱ�䣻���Ϸ�ҳ����һ�����󷵻صĵ�һ����¼��ʱ�䡣
	 *  + reqnum		���룬ÿ�������¼��������ȡֵΪ1-100����
	 *  + twitterid		���룬��ҳʱʹ�á���1-100����0���������·�ҳ����һ�����󷵻ص����һ����¼id��
	 *
	 * @return array
	 */
	public function connectGetRepostList($openId, $accessToken, $accessTokenSecret, $params) {
		if(!isset($params['flag']) || !$params['rootid'] || !isset($params['pageflag']) || !isset($params['pagetime']) || !$params['reqnum'] || !isset($params['twitterid'])) {
			throw new Exception('Required Parameter Missing');
		}

		$paramsName = array('flag', 'rootid', 'pageflag', 'pagetime', 'reqnum', 'twitterid');
		$extra = array(
			'oauth_token' => $accessToken,
			'openid' => $openId,
			'format' => 'xml',
		);

		foreach($paramsName as $name) {
			if($params[$name]) {
				$extra[$name] = $params[$name];
			}
		}
		$this->setTokenSecret($accessTokenSecret);
		$response = $this->_request($this->_getReportListURL, $extra, 'GET');
		$data = $this->_xmlParse($response);
		if(isset($data['ret']) && $data['ret'] == 0) {
			return $data;
		} else {
			throw new Exception($data['msg'], $data['ret']);
		}
	}

}
