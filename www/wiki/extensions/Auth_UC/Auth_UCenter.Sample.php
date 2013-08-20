<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file makes MediaWiki use a SMF user database to
 * authenticate with. This forces users to have a SMF account
 * in order to log into the wiki. This should also force the user to
 * be in a group called wiki.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @package MediaWiki
 * @subpackage Auth_SMF
 * @author Nicholas Dunnaway
 * @copyright 2004-2006 php|uber.leet
 * @license http://www.gnu.org/copyleft/gpl.html
 * @CVS: $Id: Auth_SMF.php,v 1.3 2007/04/05 22:17:56 nkd Exp $
 * @link http://uber.leetphp.com
 * @version $Revision: 1.3 $
 *
 * 
 * @Modified By Hopesoft
 * @link http://www.51ajax.com
 * @2007-11-11
 *
 *
 * @Modified By outcrop
 * @email outcrop@163.com
 * @2008-04-07
 */

error_reporting(E_ALL); // Debug
include './extensions/Auth_UC/config.inc.php';
include './extensions/Auth_UC/uc_client/client.php';
// First check if class has already been defined.
if (!class_exists('AuthPlugin'))
{
	/**
	 * Auth Plugin
	 *
	 */
	require_once './includes/AuthPlugin.php';
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	
	$discuz_auth_key = md5("fae418gwxyaAOXJw".$_SERVER['HTTP_USER_AGENT']);

	$ckey_length = 4;
	$key = md5($key ? $key : $discuz_auth_key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

$wgExtensionFunctions[] = 'uc_login_hook';
function uc_login_hook() {
	global $wgUser, $wgRequest, $wgAuth;


	// 获取当前用户的 UID 和 用户名 
	$_config = array();
	$_config['cookie']['cookiepre'] = 'ygclub_';
	$_config['cookie']['cookiedomain'] = '';
	$_config['cookie']['cookiepath'] = '/';
	$_config['security']['authkey'] = '67fe8eMA9PX9Lno1';

	$dbhost = 'localhost';			// 数据库服务器
	$dbuser = '';			// 数据库用户名
	$dbpw = '';				// 数据库密码
	$dbname = '';			// 数据库名
	$tablepre="ygclub_";

	$user = User::newFromSession();

	if(substr($_config['cookie']['cookiepath'], 0, 1) != '/') {
		$_config['cookie']['cookiepath']= '/' . $_config['cookie']['cookiepath'];
	}
	$cookiepre =  $_config['cookie']['cookiepre'] . substr(md5($_config['cookie']['cookiepath'] . '|' .  $_config['cookie']['cookiedomain']), 0, 4) . '_';//COOKIE前缀

	$auth = 'ygclub_auth';//存储用户信息的COOKIE名
	$saltkey = $_COOKIE[ $cookiepre . 'saltkey'];//解密auth用到的key


	//$auth_value = uc_authcode($_COOKIE[$auth],'DECODE',$discuz_auth_key);
	$cookie_decode = authcode($_COOKIE[$auth], 'DECODE');

	list($discuz_pw, $discuz_secques, $discuz_uid) = empty($_COOKIE[$auth]) ? array('', '', 0) : daddslashes(explode("\t", authcode($_COOKIE[$auth], 'DECODE')), 1);
	#echo "<script>alert('cookie=".$_COOKIE[$auth]."');</script>";
	#echo "<script>alert('cookie_decode=".$cookie_decode."');</script>";
	#echo "<script>alert('discuz_uid=".$discuz_uid."');</script>";

	if(!empty($discuz_uid)) { 
		$ygclub_password=$discuz_pw;
		$ygclub_uid=$discuz_uid;
	} else { 
		$user->doLogout(); // Logout mismatched user.
		return ;
	}

	// Connect to database.
	$connect = mysql_connect($dbhost, $dbuser, $dbpw, true);

	mysql_select_db($dbname,$connect);
	mysql_query("set names utf8");

	$result = mysql_query("SELECT username FROM ".$tablepre."members  WHERE uid = '".$ygclub_uid."'");
	if (!$result) {
		return ;
	}
	$row = mysql_fetch_row($result);

	$ygclub_username= $row[0];

//		   echo "<script>alert('ygclub_username=".$ygclub_username."');</script>";


	//	$ygclub_username=uc_id2name($ygclub_uid);


	// For a few special pages, don't do anything.
	$title = $wgRequest->getVal( 'title' );
	if ( ( $title == Title::makeName( NS_SPECIAL, 'UserLogout' ) ) ||
			( $title == Title::makeName( NS_SPECIAL, 'UserLogin' ) ) ) {
		return;
	}

	if ( !$user->isAnon() ) {
		if ( strtolower($user->getName()) == strtolower($wgAuth->getCanonicalName($ygclub_username)) ) {
			return; // Correct user is already logged in.
		} else {
			$user->doLogout(); // Logout mismatched user.
		}
	}


	// Copied from includes/SpecialUserlogin.php
	if ( !isset( $wgCommandLineMode ) && !isset( $_COOKIE[session_name()] ) ) {
		wfSetupSession();
	}


	//        echo "<script>alert('start login');</script>";

	// If the login form returns NEED_TOKEN try once more with the right token
	$trycount = 0;
	$token = '';
	$errormessage = '';
	do {
		$tryagain = false;
		// Submit a fake login form to authenticate the user.
		$params = new FauxRequest( array(
					'wpName' => urlencode($wgAuth->getCanonicalName($ygclub_username)),
					'wpPassword' => 'SUMMERBEGIN',
					'wpDomain' => '',
					'wpLoginToken' => $token,
					'wpRemember' => ''
					) );


		// Authenticate user data will automatically create new users.
		$loginForm = new LoginForm( $params );
		$result = $loginForm->authenticateUserData();
		//echo "<script>alert('wpName=".urlencode($wgAuth->getCanonicalName($ygclub_username))."auth complete:".$result."');</script>";

		switch ( $result ) {
			case LoginForm :: SUCCESS :
				$wgUser->setOption( 'rememberpassword', 1 );
				$wgUser->setCookies();
				break;
			case LoginForm :: NEED_TOKEN:
				$token = $loginForm->getLoginToken();
				$tryagain = ( $trycount == 0 );
				break;
			case LoginForm :: WRONG_TOKEN:
				$errormessage = 'WrongToken';
				break;
			case LoginForm :: NO_NAME :
				$errormessage = 'NoName';
				break;
			case LoginForm :: ILLEGAL :
				$errormessage = 'Illegal';
				break;
			case LoginForm :: WRONG_PLUGIN_PASS :
				$errormessage = 'WrongPluginPass';
				break;
			case LoginForm :: NOT_EXISTS :
				$errormessage = 'NotExists|'.$ygclub_username."|";
				break;
			case LoginForm :: WRONG_PASS :
				$errormessage = 'WrongPass';
				break;
			case LoginForm :: EMPTY_PASS :
				$errormessage = 'EmptyPass';
				break;
			default:
				$errormessage = 'Unknown';
				break;
		}

		if ( $result != LoginForm::SUCCESS && $result != LoginForm::NEED_TOKEN ) {
			error_log( 'Unexpected REMOTE_USER authentication failure. Login Error was:' . $errormessage );
		}
		$trycount++;
	} while ( $tryagain );

//	echo "<script>alert('auth done, refresh page!');</script>";
//	echo "<script>location.reload();</script>";
	return;
}

/**
 * Handles the Authentication with the Discuz database.
 *
 * @package MediaWiki
 * @subpackage Auth_UCenter
 */
class Auth_UCenter extends AuthPlugin
{

	/**
	 * Add a user to the external authentication database.
	 * Return true if successful.
	 *
	 * NOTE: We are not allowed to add users to Discuz from the
	 * wiki so this always returns false.
	 *
	 * @param User $user
	 * @param string $password
	 * @return bool
	 * @access public
	 */
	function addUser( $user, $password )
	{
		return false;
	}

	/**
	 * Can users change their passwords?
	 *
	 * @return bool
	 */
	function allowPasswordChange()
	{
		return true;
	}

	/**
	 * Check if a username+password pair is a valid login.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param string $username
	 * @param string $password
	 * @return bool
	 * @access public
	 * @todo Check if the password is being changed when it contains a slash or an escape char.
	 */
	function authenticate($username, $password)
	{


		// Clean $username and force lowercase username.
		//echo "<br> Authenticate Start";
		$username = htmlentities(strtolower($username), ENT_QUOTES, 'UTF-8');
		$username = str_replace('&#039;', '\\\'', $username); // Allow apostrophes (Escape them though)
		//调用client的uc_user_login判断用户密码，由于MediaWiki为utf8编码，因此转换为GBK后判断，主要是中文ID


		//		echo "<script>alert('username(".$username.")auth passed!');</script>";
		//如果来自同步登陆则通过认证
		if ($password == "SUMMERBEGIN") return true;

		list($uid, $username1, $password, $email) = uc_user_login(iconv("UTF-8", "UTF-8", $username), $password);

		if ($uid > 0 ) {
			uc_user_synlogin($uid);
			return true;
		}

		return false;

	}

	/**
	 * Return true if the wiki should create a new local account automatically
	 * when asked to login a user who doesn't exist locally but does in the
	 * external auth database.
	 *
	 * If you don't automatically create accounts, you must still create
	 * accounts in some way. It's not possible to authenticate without
	 * a local account.
	 *
	 * This is just a question, and shouldn't perform any actions.
	 *
	 * NOTE: I have set this to true to allow the wiki to create accounts.
	 *       Without an accout in the wiki database a user will never be
	 *       able to login and use the wiki. I think the password does not
	 *       matter as long as authenticate() returns true.
	 *
	 * @return bool
	 * @access public
	 */
	function autoCreate()
	{
		return true;
	}

	/**
	 * Check to see if external accounts can be created.
	 * Return true if external accounts can be created.
	 *
	 * NOTE: We are not allowed to add users to Discuz from the
	 * wiki so this always returns false.
	 *
	 * @return bool
	 * @access public
	 */
	function canCreateAccounts()
	{
		return false;
	}

	/**
	 * Connect to the database. All of these settings are from the
	 * LocalSettings.php file. This assumes that the Discuz uses the same
	 * database/server as the wiki.
	 *
	 * {@source }
	 * @return resource
	 */
	function connect()
	{

		//echo "<br> ----connecting----";
		$dbcharset = $GLOBALS['wgSMF_Charset'];
		//echo "Charset: ".$dbcharset;

		// Check if the SMF tables are in a different database then the Wiki.
		if ($GLOBALS['wgSMF_UseExtDatabase'] == true) {

			// Connect to database. I supress the error here.
			$fresMySQLConnection = @mysql_connect($GLOBALS['wgSMF_MySQL_Host'],
					$GLOBALS['wgSMF_MySQL_Username'],
					$GLOBALS['wgSMF_MySQL_Password'],
					true);
			if (mysql_get_server_info()>= 4.1)			
				if($dbcharset) {			    
					mysql_query("SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=binary");
				}		

			// Check if we are connected to the database.
			if (!$fresMySQLConnection)
			{
				$this->mySQLError('There was a problem when connecting to the SMF database.<br />' .
						'Check your Host, Username, and Password settings.<br />');
			}

			// Select Database
			$db_selected = mysql_select_db($GLOBALS['wgSMF_MySQL_Database'], $fresMySQLConnection);

			// Check if we were able to select the database.
			if (!$db_selected)
			{
				$this->mySQLError('There was a problem when connecting to the SMF database.<br />' .
						'The database ' . $GLOBALS['wgSMF_MySQL_Database'] .
						' was not found.<br />');
			}

		}
		else
		{

			// Connect to database.
			$fresMySQLConnection = mysql_connect($GLOBALS['wgDBserver'],
					$GLOBALS['wgDBuser'],
					$GLOBALS['wgDBpassword'],
					true);

			// Check if we are connected to the database.
			if (!$fresMySQLConnection)
			{
				$this->mySQLError('There was a problem when connecting to the SMF database.<br />' .
						'Check your Host, Username, and Password settings.<br />');
			}

			// Select Database: This assumes the wiki and SMF are in the same database.
			$db_selected = mysql_select_db($GLOBALS['wgDBname']);

			// Check if we were able to select the database.
			if (!$db_selected)
			{
				$this->mySQLError('There was a problem when connecting to the SMF database.<br />' .
						'The database ' . $GLOBALS['wgDBname'] . ' was not found.<br />');
			}

		}

		$GLOBALS['gstrMySQLVersion'] = substr(mysql_get_server_info(), 0, 3); // Get the mysql version.

		return $fresMySQLConnection;
	}

	/**
	 * If you want to munge the case of an account name before the final
	 * check, now is your chance.
	 */
	function getCanonicalName( $username )
	{
		return $username;
	}

	/**
	 * When creating a user account, optionally fill in preferences and such.
	 * For instance, you might pull the email address or real name from the
	 * external user database.
	 *
	 * The User object is passed by reference so it can be modified; don't
	 * forget the & on your function declaration.
	 *
	 * NOTE: This gets the email address from SMF for the wiki account.
	 *
	 * @param User $user
	 * @access public
	 */
	function initUser(&$user)
	{
		///echo "<br> initUser Start";
		$username = htmlentities(strtolower($user->mName), ENT_QUOTES, 'UTF-8');
		$username = str_replace('&#039;', '\\\'', $username); // Allow apostrophes (Escape them though)

//				echo "<script>alert('username(".$username.")init started!');</script>";

		//echo "<BR>Start init---";

		if($data = uc_get_user(iconv("UTF-8", "UTF-8", $username))) { //Get information from UC
			list($uid, $username1, $email) = $data;
			//$username=iconv("GBK", "UTF-8", $username);
			//echo "init uname1:".$username1;
			//echo $email.$uid;
			$user->mEmail=$email; //Get email from UC
			$user->mid=$uid; //Get address from UC
		}

		$this->updateUser($user);
		$user->setToken();

		$user->setOption( 'enotifwatchlistpages', 1 );
		$user->setOption( 'enotifusertalkpages', 1 );
		$user->setOption( 'enotifminoredits', 1 );
		$user->setOption( 'enotifrevealaddr', 1 );

		$user->saveSettings(); 
	}

	/**
	 * Checks if the user is a member of the SMF group called wiki.
	 *
	 * @param string $username
	 * @access public
	 * @return bool
	 * @todo Remove 2nd connection to database. For function isMemberOfWikiGroup()
	 *
	 */
	function isMemberOfWikiGroup($username)
	{
		// In LocalSettings.php you can control if being
		//echo "<br>---IsMember Start---";
		// a member of a wiki is required or not.
		//return false;
		if (isset($GLOBALS['wgSMF_UseWikiGroup']) && $GLOBALS['wgSMF_UseWikiGroup'] === false)
		{
			return true;
		}
		//	//echo isset($GLOBALS['wgSMF_UseWikiGroup']) && $GLOBALS['wgSMF_UseWikiGroup'] === false;
		// Connect to the database.
		$fresMySQLConnection = $this->connect();

		// Check MySQL Version
		if ($GLOBALS['gstrMySQLVersion'] >= 4.1)
		{	////echo  $GLOBALS['wgSMF_UserextTB'];exit;
			// Get all the groups the user is a member of.
			$fstrMySQLQuery = 'SELECT   `gid`
				FROM `' . $GLOBALS['wgSMF_UserextTB'] . '`
				WHERE `username` = CONVERT( _utf8 \'' . $username . '\' USING utf8 )

				LIMIT 1';
		}
		else
		{
			// Get all the groups the user is a member of.
			$fstrMySQLQuery = 'SELECT `gid`
				FROM `' . $GLOBALS['wgSMF_UserextTB'] . '`
				WHERE `username` = \'' . $username . '\'
				LIMIT 1';
		}


		// Query Database.
		$fresMySQLResult = mysql_query($fstrMySQLQuery, $fresMySQLConnection)
			or die($this->mySQLError('Unable to view external table_2'));

		//   //echo $fstrMySQLQuery;exit;
		while($faryMySQLResult = mysql_fetch_array($fresMySQLResult))
		{                
			$GroupID = $faryMySQLResult['gid'];
			//echo  "groupID is: ".$GroupID;
			//	$AdminID = $faryMySQLResult['adminid'];
		}      
		$wgSMF_WikiGroupIDArray=explode(',',$GLOBALS['wgSMF_WikiGroupID']);
		if (in_array($GroupID,$wgSMF_WikiGroupIDArray )) {
			return true;
		}
		else
		{
			return false;	
		}
	}

	/**
	 * Modify options in the login template.
	 *
	 * NOTE: Turned off some Template stuff here. Anyone who knows where
	 * to find all the template options please let me know. I was only able
	 * to find a few.
	 *
	 * @param UserLoginTemplate $template
	 * @access public
	 */
	function modifyUITemplate( &$template )
	{

		$template->set('usedomain',   false); // We do not want a domain name.
		$template->set('create',      false); // Remove option to create new accounts from the wiki.
		$template->set('useemail',    false); // Disable the mail new password box.

	}

	/**
	 * This prints an error when a MySQL error is found.
	 *
	 * @param string $message
	 * @access public
	 */
	function mySQLError( $message )
	{
		//echo $message . '<br />';
		//echo 'MySQL Error Number: ' . mysql_errno() . '<br />';
		//echo 'MySQL Error Message: ' . mysql_error() . '<br /><br />';
		exit;
	}

	/**
	 * Set the domain this plugin is supposed to use when authenticating.
	 *
	 * NOTE: We do not use this.
	 *
	 * @param string $domain
	 * @access public
	 */
	function setDomain( $domain )
	{
		$this->domain = $domain;
	}

	/**
	 * Set the given password in the authentication database.
	 * Return true if successful.
	 *
	 * NOTE: We only allow the user to change their password via phpBB.
	 *
	 * @param string $password
	 * @return bool
	 * @access public
	 */
	function setPassword( $password )
	{
		return true;
	}

	/**
	 * Return true to prevent logins that don't authenticate here from being
	 * checked against the local database's password fields.
	 *
	 * This is just a question, and shouldn't perform any actions.
	 *
	 * Note: This forces a user to pass Authentication with the above
	 *       function authenticate(). So if a user changes their SMF
	 *       password, their old one will not work to log into the wiki.
	 *       Wiki does not have a way to update it's password when SMF
	 *       does. This however does not matter.
	 *
	 * @return bool
	 * @access public
	 */
	function strict()
	{
		return true;
	}

	/**
	 * Update user information in the external authentication database.
	 * Return true if successful.
	 *
	 * @param $user User object.
	 * @return bool
	 * @public
	 */
	function updateExternalDB( $user )
	{
		return true;
	}

	/**
	 * When a user logs in, optionally fill in preferences and such.
	 * For instance, you might pull the email address or real name from the
	 * external user database.
	 *
	 * The User object is passed by reference so it can be modified; don't
	 * forget the & on your function declaration.
	 *
	 * NOTE: Not useing right now.
	 *
	 * @param User $user
	 * @access public
	 */
	function updateUser( &$user )
	{
		return true;
	}

	/**
	 * Check whether there exists a user account with the given name.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * NOTE: MediaWiki checks its database for the username. If it has
	 *       no record of the username it then asks. "Is this really a
	 *       valid username?" If not then MediaWiki fails Authentication.
	 *
	 * @param string $username
	 * @return bool
	 * @access public
	 * @todo write this function.
	 */
	function userExists($username)
	{
		// Clean $username and force lowercase username.
		//echo "<br> userExists Start";
		$username = htmlentities(strtolower($username), ENT_QUOTES, 'UTF-8');
		$username = str_replace('&#039;', '\\\'', $username); // Allow apostrophes (Escape them though)
		//$username=iconv("UTF-8", "GBK", $username);
		//echo "<BR>Start Exist---";

		//---------------------------------------------------------------------------------------------------
		if($data = uc_get_user(iconv("UTF-8", "UTF-8", $username))) {
			list($uid, $username1, $email) = $data;	
			//$username=iconv("GBK", "UTF-8", $username);
			//echo "Exist username :".$username;
			//echo "---Exist username1 :".$username1;
			return true;
		} else {
			//echo 'No such user:'.$username;
			//echo iconv("UTF-8", "GBK", $username);
			return false;
		}

	}

	/**
	 * Check to see if the specific domain is a valid domain.
	 *
	 * @param string $domain
	 * @return bool
	 * @access public
	 */
	function validDomain( $domain )
	{
		return true;
	}

}

?>
