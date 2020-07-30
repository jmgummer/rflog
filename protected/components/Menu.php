<?php

class Menu{
	public static function GetMenu($type){
		if($type==1){
			return Menu::PostCampaignMenu();
		}else{
			return false;
		}
	}
	// post campaign menu
	public static function PostCampaignMenu(){
		$avatar_image = Yii::app()->request->baseUrl . '/images/avatars/male.png';
		$client_name = Yii::app()->user->client_name;
		$FullName = Yii::app()->user->FullName;
		$menu ='<div class="side-content-wrap">
            <div class="sidebar-left open" data-perfect-scrollbar data-suppress-scroll-x="true">
                <ul class="navigation-left">
                    <li class="nav-item">
                        <a class="nav-item-hold" href="'.Yii::app()->createUrl("home").'">
                            <i class="nav-icon i-Bar-Chart"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-item-hold" href="'.Yii::app()->createUrl("home/manual").'">
                            <i class="nav-icon i-Film-Video"></i>
                            <span class="nav-text">Manual Logs</span>
                        </a>
                        <div class="triangle"></div>
                    </li>';
        $menu .= '<li class="nav-item">
                        <a class="nav-item-hold" href="'.Yii::app()->createUrl("home/auto").'">
                            <i class="nav-icon i-Video-2"></i>
                            <span class="nav-text">Auto Logs</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-item-hold" href="'.Yii::app()->createUrl("compliance").'">
                            <i class="nav-icon i-Clock-Back"></i>
                            <span class="nav-text">Compliance</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-item-hold" href="'.Yii::app()->createUrl("account").'">
                            <i class="nav-icon i-Add-User"></i>
                            <span class="nav-text">Account</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                </ul>
            </div>
            <div class="sidebar-overlay"></div>
        </div>';
		return $menu;
	}

	
}