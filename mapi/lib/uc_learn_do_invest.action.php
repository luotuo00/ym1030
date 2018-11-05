<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_learn_do_invest
{
	public function index(){
		
		$root = array();
		
		$user =  $GLOBALS['user_info'];
		$root['session_id'] = es_session::id();
		$user_id  = intval($user['id']);
		if ($user_id >0){
			require_once APP_ROOT_PATH.'system/libs/learn.php';
			$root['user_login_status'] = 1;
			$root['response_code'] = 1;
			
			$learn_id = intval($GLOBALS['request']['learn_id']);
			$money = floatval($GLOBALS['request']['money']);
			
			$result = learn_invest($learn_id,$money);
            $root['show_err'] =$result['info'];
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		$root['program_title'] = "立即投资";
		output($root);		
	}
}
?>
