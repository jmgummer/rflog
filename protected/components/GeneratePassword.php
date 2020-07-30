<?php

/**
* GeneratePassword Component Class
* This Class Is Used To Generate a new Password for Users
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Reelmedia
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class GeneratePassword{
	/* 
	** Function used to generate a new Password 
	** start with a blank password
	** define possible characters
	** set up a counter
	** add random characters to $password until $length is reached
	** pick a random character from the possible ones
	** we don't want this character if it's already in the password
	*/
	public static function Generate($length = 8)
	{
		$password = "";
		$possible = "0123456789bcdfghjkmnpqrstvwxyz";
		$i = 0;
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}

	/* 
	** Function to Update the Database with the New Password 
	*/

	public static function ResetPassword($id)
	{
		$new_password = GeneratePassword::Generate();
		if($update = AgencyUsers::model()->find('agency_users_id=:a', array(':a'=>$id))){
			$update->password = md5($new_password);
			if($update->save()){
				$name = ucfirst($update->firstname)." " . ucfirst($update->surname);
				$username = $update->username;
				$email = $update->email;
				$send_email = GeneratePassword::SendResetMail($username,$name,$email,$new_password);
				return 'The users password has been reset, please check email';
			}else{
				return 'There was a problem with this query, Please try later';
			}
		}else{
			return 'There seems to be a problem, check if the user exists and try again';
		}
	}

	/**
	*
	* @return  Send an Email with your new Password
	* @throws  InvalidArgumentException
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public static function SendResetMail($username,$name,$email,$password)
	{
		$subject    =   "ReelMedia Account : Password reset";
		$body       =   "Dear ".$name.", <br>
						Your ReelMedia Agency password has been reset.<br><br>  Your new credentials are as follows:<br>
						Username: $username<br>Password: $password<br>
						You can log into your account using http://www.reelforge.com/<br><br>IMPORTANT :Please enter your username and password then select 'Reelmedia' from the dropdown list. 
						<br><br> We advise that you keep this email for your records. We also advise that you <u><b>change your password</b></u> as soon as you log in.<br>
						Thank You,<br>
						Administrator<br>";

		$message = Mailer::Build($body);
		Yii::app()->mailer->Host =  '192.168.0.45';
		Yii::app()->mailer->IsSMTP();
		Yii::app()->mailer->From = "info@reelforge.com";
		Yii::app()->mailer->AddReplyTo = 'info@reelforge.com';
		Yii::app()->mailer->FromName = "ReelMedia Administrator";
		Yii::app()->mailer->AddAddress($email);
		// Yii::app()->mailer->AddBCC("sammy.lusiola@reelforge.com");
		// Yii::app()->mailer->AddBCC("steve.oyugi@reelforge.com");
		Yii::app()->mailer->Subject = $subject;
		Yii::app()->mailer->Body = $message;
		Yii::app()->mailer->IsHTML(true);
		Yii::app()->mailer->Send();
	}



}