<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------
//require APP_ROOT_PATH.'app/Lib/uc.php';
class peizi_bid
{
	public function index(){
		
		$root = get_baseroot();

		$id = intval($GLOBALS['request']['id']);
		$paypassword =  FW_DESPWD($GLOBALS['request']['paypassword']);
		$user_id = intval($GLOBALS['user_info']['id']);
//		$user =  $GLOBALS['user_info'];
//		$root['session_id'] = es_session::id();
//		$user_id  = intval($user['id']);
		
		require_once APP_ROOT_PATH.'system/libs/peizi.php';
		$result = dopeizi_bid($id,$user_id,$paypassword);
		//ajax_return($result);
		$root['result'] = $result;
			
		$root['program_title'] = "配资投资";
		output($root);		
	}
}
?>
