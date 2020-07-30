<?php

/**
 * this class handles notifications
 */
class NotificationsPanel
{
	private $user_id;
	private $messages;

	function __construct(){
        $this->user_id = Yii::app()->user->user_id;
        $this->messages = $this->UserMessages();
    }
	
	public function MessageHeader($messagecount){
		$content = '<div class="badge-top-container" id="dropdownNotification" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		$content .= '<span class="badge badge-primary">'.$messagecount.'</span>';
		$content .= '<i class="i-Bell text-muted header-icon"></i>';
		$content.= '</div>';
		return $content;
	}

	public function MessagesBox(){
		$content = '<div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">';
		if($this->messages && count($this->messages)>0){
			$messagearray = $this->messages;
			foreach ($messagearray as $keyvalue) {
				$dateadded = $keyvalue['dateadded'];
				$message = $keyvalue['message'];
				$xtime = $this->xTimeAgo ($dateadded);
				$content .='<div class="dropdown-item d-flex">
                            <div class="notification-icon">
                                <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                            </div>
                            <div class="notification-details flex-grow-1">
                                <p class="m-0 d-flex align-items-center">
                                    <span>New message</span>
                                    <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                                    <span class="flex-grow-1"></span>
                                    <span class="text-small text-muted ml-auto">'.$xtime.'</span>
                                </p>
                                <p class="text-small text-muted m-0">'.$message.'</p>
                            </div>
                        </div>';
			}
		}
		$content.= '</div>';
		return $content;
	}

	public function MessageContent(){
		$messagecount = count($this->messages);
		$messagecontent = $this->MessageHeader($messagecount);
		$messagecontent.= $this->MessagesBox();
		return $messagecontent;
	}

	public function xTimeAgo ($oldTime) {
		$newTime = date("Y-m-d H:i:s");
		$timeCalc = strtotime($newTime) - strtotime($oldTime);
		if ($timeCalc > (60*60*24)) {$timeCalc = round($timeCalc/60/60/24) . " days ago";}
		else if ($timeCalc > (60*60)) {$timeCalc = round($timeCalc/60/60) . " hours ago";}
		else if ($timeCalc > 60) {$timeCalc = round($timeCalc/60) . " minutes ago";}
		else if ($timeCalc > 0) {$timeCalc .= " seconds ago";}
		return $timeCalc;
	}

	public function UserMessages(){
		$messagearray = array();
		$messages = Notifications::model()->findAll('user_id=:a AND note_status=0', array(':a'=>$this->user_id));
		if($messages){
			foreach ($messages as $key) {
				$messagearray[] = array('message_type'=>$key->message_type,'message'=>$key->message, 'dateadded'=>$key->dateadded);
			}
		}
		return $messagearray;
	}
}