\<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/project_func.php';
class project_add_update
{
	public function index(){
		
		$root = get_baseroot();
		$user =  $GLOBALS['user_info'];
		$root['session_id'] = es_session::id();
		$user_id  = intval($user['id']);
		$root['user_id'] = $user_id;
		
		/*new start*/
		if(!$user)
		{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		else
		{
			$id = intval($GLOBALS["request"]['id']);
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."project where id = ".$id." and is_delete = 0 and is_effect = 1 and user_id = ".$user_id);
			if(!$deal_info)
			{
				$root['response_code'] = 0;
				$root['show_err'] ="不能更新该项目的动态";
			}
			else
			{
				$root['deal_info'] = $deal_info;
				$root['program_title'] = $deal_info["name"];
			}
		}
		
		$root['user_income'] = $user_income;

		output($root);
		
	}
}
?>
