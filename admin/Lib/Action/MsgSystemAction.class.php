<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class MsgSystemAction extends CommonAction{
	public function add()
	{	
		$this->assign("vip_list",M("VipType")->where('is_effect = 1 and is_delete = 0 order by sort ')->findAll());
		$this->assign("user_level_list",M("UserLevel")->findAll());
		$this->assign("default_end_time",to_date(TIME_UTC+3600*24*7));
		$this->display();
	}
	
	public function edit() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M(MODULE_NAME)->where($condition)->find();
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$this->assign ( 'vo', $vo );
		$this->assign("vip_list",M("VipType")->where('is_effect = 1 and is_delete = 0 order by sort ')->findAll());
		$this->assign("user_level_list",M("UserLevel")->findAll());
		$this->display ();
	}	
	
	public function insert() {
		B('FilterString');
		
		$ajax = intval($_REQUEST['ajax']);
		$data = M(MODULE_NAME)->create ();
		
		if($data['send_type'] == 3){
			$data['send_type_id'] = intval($_REQUEST['level_id']);
		}elseif($data['send_type'] == 4){
			$data['send_type_id'] = intval($_REQUEST['vip_id']);
		}elseif($data['send_type'] == 2){
			$data['send_define_data'] = $_REQUEST['send_define_data'];
		}
		
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);		
		$data['create_time'] = TIME_UTC;
		$user_names = preg_split("/[ ,]/i",$data['user_names']);
		$user_ids = "";
		foreach($user_names as $k=>$v)
		{
			$uid = M("User")->where("user_name = '".$v."'")->getField("id");
			if($uid)
				$user_ids.="|".$uid."-".$v."|";
		}
		$data['user_ids'] = $user_ids;		
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/add"));
		if(!check_empty($data['title']))
		{
			$this->error(L("MSY_TITLE_EMPTY_TIP"));
		}	
		if(!check_empty($data['content']))
		{
			$this->error(L("MSY_CONTENT_EMPTY_TIP"));
		}	
		// 更新数据
		$log_info = $data['title'];
		$data['content'] = str_replace(array("copy(",'fopen','fclose','fread','file_get_contents(','file_put_contents('),"strim(", $data['content']);			
	
		$list=M(MODULE_NAME)->add($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
	
	public function update() {
		B('FilterString');
		$data = M(MODULE_NAME)->create ();
		
		if($data['send_type'] == 3){
			$data['send_type_id'] = intval($_REQUEST['level_id']);
		}elseif($data['send_type'] == 4){
			$data['send_type_id'] = intval($_REQUEST['vip_id']);
		}elseif($data['send_type'] == 2){
			$data['send_define_data'] = $_REQUEST['send_define_data'];
		}
		
		$user_names = preg_split("/[ ,]/i",$data['user_names']);
		$user_ids = "";
		foreach($user_names as $k=>$v)
		{
			$uid = M("User")->where("user_name = '".$v."'")->getField("id");
			if($uid)
			$user_ids .= "|".$uid."-".$v."|";
		}
		$data['user_ids'] = $user_ids;	
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
		
		$log_info = M(MODULE_NAME)->where("id=".intval($data['id']))->getField("title");
		//开始验证有效性
		$this->assign("jumpUrl",u(MODULE_NAME."/edit",array("id"=>$data['id'])));
		if(!check_empty($data['title']))
		{
			$this->error(L("MSY_TITLE_EMPTY_TIP"));
		}	
		if(!check_empty($data['content']))
		{
			$this->error(L("MSY_CONTENT_EMPTY_TIP"));
		}	
		$data['content'] = str_replace(array("copy(",'fopen','fclose','fread','file_get_contents(','file_put_contents('),"strim(", $data['content']);			
	
		// 更新数据
		$list=M(MODULE_NAME)->save ($data);
		if (false !== $list) {
			//成功提示
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );		

				$rel_data = M(MODULE_NAME)->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M(MODULE_NAME)->where ( $condition )->delete();			
				if ($list!==false) {
					M("MsgBox")->where(array ('system_msg_id' => array ('in', explode ( ',', $id ) )))->delete();
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}	
}
?>