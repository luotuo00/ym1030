<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class uc_del_address
{
	public function index(){
		
		$root = get_baseroot();
		
		$address_id = intval($GLOBALS['request']['id']);
		$user =  $GLOBALS['user_info'];
		$root['session_id'] = es_session::id();
		$user_id  = intval($user['id']);
		if ($user_id >0){
			
			if($address_id){
				$GLOBALS['db']->query("delete from ".DB_PREFIX."user_address  where id = ".$address_id);
				$root['response_code'] = 1;
				$root["show_err"] = "删除成功";
			}else{
				$root["show_err"] = "删除失败";
			}
			
			output($root);
			
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		$root['program_title'] = "收货地址删除";
		output($root);		
	}
}
?>
