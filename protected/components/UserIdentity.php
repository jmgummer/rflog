<?php

/**
* UserIdentity represents the data needed to identity a user.
* It contains the authentication method that checks if the provided
* data can identity the user.
*
* @package     Reelmedia
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/

class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		/* Regular Clients Login */
		$client = Users::model()->find('username=:a AND password=:b AND status=1', array(':a'=>$this->username,':b'=>sha1($this->password)));
		if($client==FALSE){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}else{
			$this->username = 'admin';
			$this->setState('user_id', $client->id);
			$this->setState('client_name',$client->username);
			$this->setState('client_type',$client->client_type);
			$this->setState('usertype',$client->usertype);
			$this->setState('FullName',$client->FullName);
			$client->lastlogin = date('Y-m-d H:i:s');
			$client->save();
			$this->errorCode=self::ERROR_NONE;
		}

		return !$this->errorCode;
	}
}