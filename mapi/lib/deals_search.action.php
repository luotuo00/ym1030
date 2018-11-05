<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class deals_search
{
	function index(){
		$root = get_baseroot();
		$root['response_code'] = 1;
		$root['program_title'] = "投资搜索";
		
		$level_list = load_auto_cache("level");
		$root["level_list"] = $level_list['list'];
		output($root);		
	}
}
?>
