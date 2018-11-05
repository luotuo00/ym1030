<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------

class region_list
{
    /**
     * 获取所有的配送地区的数据集
     * 输入：
     * 无
     * 
     * 
     * 输出：
     * region_list:array 地区列表
     * Array(
     *  Array(
     *      "id"=> int 地区ID,
     *      "pid" => int父级地区ID
     *      "name" => string 地区名称
     *      "region_level" => int 地区等级
     *  )
     * )
     */
    
	public function index(){
		
		$root = array ();
        $sql = "select id,pid,name,region_level from " . DB_PREFIX . "region_conf order by pid";
        $region_list = $GLOBALS ['db']->getAll ( $sql );
		$root ['db_version'] = app_conf("DB_VERSION");
        $root ['region_list'] = $region_list?$region_list:array();
        output ( $root );

	}
}
?>
