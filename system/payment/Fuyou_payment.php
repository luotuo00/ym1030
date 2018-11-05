<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

$payment_lang = array(
	'name'	=>	'富友支付',
	'Fuyou_account'	=>	'商户号',
	'Fuyou_key'		=>	'密钥',
	
	'GO_TO_PAY'	=>	'前往富友在线支付',
	'VALID_ERROR'	=>	'支付验证失败',
	'PAY_FAILED'	=>	'支付失败',

	'baofoo_gateway'	=>	'支持的银行',
	
	//借记卡
	'baofoo_gateway_0803080000'	=>	'招商银行',
	'baofoo_gateway_0801020000'	=>	'工商银行',
	'baofoo_gateway_0801050000'	=>	'建设银行',
	'baofoo_gateway_0803100000'	=>	'浦发银行',
	'baofoo_gateway_0801030000'	=>	'农业银行',
	'baofoo_gateway_0803050000'	=>	'民生银行',
	
	'baofoo_gateway_0803090000'	=>	'兴业银行',
	'baofoo_gateway_0803010000'	=>	'交通银行',
	'baofoo_gateway_0803030000'	=>	'光大银行',
	'baofoo_gateway_0801040000'	=>	'中国银行',
	'baofoo_gateway_0804031000'	=>	'北京银行',
	
	
	'baofoo_gateway_0804105840'	=>	'平安银行',
	'baofoo_gateway_0803060000'	=>	'广发银行',
	
	'baofoo_gateway_0801000000'	=>	'中国邮政储蓄银行',
	'baofoo_gateway_0803020000'	=>	'中信银行',

	//'baofoo_gateway_3080'	=>	'银联在线',
	
	
	
);
$config = array(
	'Fuyou_account'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //支付宝帐号: 
	'Fuyou_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //校验码
	
	'baofoo_gateway'	=>	array(
		'INPUT_TYPE'	=>	'3',
		'VALUES'	=>	array(
				
				
				//借记卡
				'0803080000',//'招商银行',
				'0801020000',//'工商银行',
				'0801050000',//'建设银行',
				'0803100000',//'浦发银行',
				'0801030000',//'农业银行',
				'0803050000',//'民生银行',
				
				'0803090000',//'兴业银行',
				'0803010000',//'交通银行',
				'0803030000',//'光大银行',
				'0801040000',//'中国银行',
				'0804031000',//北京银行
				
				
				'0804105840',//'平安银行',
				'0803060000',//广发银行
				
				'0801000000',//'中国邮政储蓄银行',
				'0803020000',//'中信银行',
				
				
			
			)
	), //可选的银行网关
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Fuyou';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


    /* 支付方式：1：在线支付；0：线下支付 */
    $module['online_pay'] = '1';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = 'https://open.fuiou.com';
    return $module;
}

// 支付宝直连支付模型
require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Fuyou_payment implements payment {
	private $payment_lang = array(
		'name'	=>	'富友支付',
		'Fuyou_account'	=>	'商户号',
		'Fuyou_key'		=>	'密钥',
		
	
		'VALID_ERROR'	=>	'支付验证失败',
		'PAY_FAILED'	=>	'支付失败',
	
		'baofoo_gateway'	=>	'支持的银行',
	
		//借记卡
		'baofoo_gateway_0803080000'	=>	'招商银行',
		'baofoo_gateway_0801020000'	=>	'工商银行',
		'baofoo_gateway_0801050000'	=>	'建设银行',
		'baofoo_gateway_0803100000'	=>	'浦发银行',
		'baofoo_gateway_0801030000'	=>	'农业银行',
		'baofoo_gateway_0803050000'	=>	'民生银行',
		
		'baofoo_gateway_0803090000'	=>	'兴业银行',
		'baofoo_gateway_0803010000'	=>	'交通银行',
		'baofoo_gateway_0803030000'	=>	'光大银行',
		'baofoo_gateway_0801040000'	=>	'中国银行',
		'baofoo_gateway_0804031000'	=>	'北京银行',
		
		
		'baofoo_gateway_0804105840'	=>	'平安银行',
		'baofoo_gateway_0803060000'	=>	'广发银行',
		
		'baofoo_gateway_0801000000'	=>	'中国邮政储蓄银行',
		'baofoo_gateway_0803020000'	=>	'中信银行',
	
		
		
	
	
	);
	public function get_payment_code($payment_notice_id) {
        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		//$order = $GLOBALS['db']->getRow("select order_sn,bank_id from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);

		$_OrderMoney = round($payment_notice['money'],2);

		/*获取支付信息*/
        $payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Fuyou'");  
    	$payment['config'] = unserialize($payment['config']);
    	
		$_Md5Key= $payment['config']['Fuyou_key'];


		//支付校验
		$_mchnt_cd = $payment['config']['Fuyou_account'];
		$_order_id = $payment_notice['notice_sn']; //商户订单号

		$_order_amt = round($payment_notice['money'],2)*100; //交易金额

		$_order_pay_type = 'B2C'; //支付类型
		$_iss_ins_cd = "0801050000"; //银行

		$_page_notify_url = SITE_DOMAIN.APP_ROOT.'/callback/pay/fuyou_callback.php?act=response'; //页面跳转URL
		$_back_notify_url = SITE_DOMAIN.APP_ROOT.'/callback/pay/fuyou_callback.php?act=notify';; //后台通知URL

		$_order_valid_time = ""; //超时时间

		//$_mchnt_cd = "0004510F0341577"; //商户代码
		$_mchnt_key = $payment['config']['Fuyou_key'];
		$_goods_name = ""; //商品名称
		$_goods_display_url = ""; //商品展示网址
		$_rem = ""; //备注
		$_ver = "1.0.1"; //版本号

		//拼接数据
		$_data = "";
		$_data .= $_mchnt_cd."|";
		$_data .= $_order_id."|";
		$_data .= $_order_amt."|";
		$_data .= $_order_pay_type."|";
		$_data .= $_page_notify_url."|";
		$_data .= $_back_notify_url."|";
		$_data .= $_order_valid_time."|";
		$_data .= (string)$payment_notice['bank_id']."|";
		$_data .= $_goods_name."|";
		$_data .= $_goods_display_url."|";
		$_data .= $_rem."|";
		$_data .= $_ver."|";
		$_data .= $_mchnt_key;

		file_put_contents("08213.txt",$_data);

		$_md5 = MD5($_data); //签名数据

		$def_url = 
		'<form action="https://pay.fuiou.com/smpGate.do" method="post">
			<input type="hidden" name="md5" value="'.$_md5.'" />
            <input type="hidden" name="mchnt_cd" value="'.$_mchnt_cd.'" />
            <input type="hidden" name="order_id" value="'.$_order_id.'" />
            <input type="hidden" name="order_amt" value="'.$_order_amt.'" />
            <input type="hidden" name="order_pay_type" value="'.$_order_pay_type.'" />
            <input type="hidden" name="page_notify_url" value="'.$_page_notify_url.'" />
            <input type="hidden" name="back_notify_url" value="'.$_back_notify_url.'" />
            <input type="hidden" name="order_valid_time" value="'.$_order_valid_time.'" />
            <input type="hidden" name="iss_ins_cd" value="'.$payment_notice['bank_id'].'" />
            <input type="hidden" name="goods_name" value="'.$_goods_name.'" />
            <input type="hidden" name="goods_display_url" value="'.$_goods_display_url.'" />
            <input type="hidden" name="rem" value="'.$_rem.'" />
            <input type="hidden" name="ver" value="'.$_ver.'" />';

		$def_url .= "<input type='submit' class='paybutton' value='去支付' />";
        $def_url .= "</form>";
        $def_url.="<br /><div style='text-align:center' class='red'>".$GLOBALS['lang']['PAY_TOTAL_PRICE'].":".format_price($_OrderMoney)."</div>";





        return $def_url;
    }

     public function response($request) {
		$return_res = array(
            'info' => '',
            'status' => false,
        );
         /*获取支付信息*/
        $payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Fuyou'");  
    	$payment['config'] = unserialize($payment['config']);
    	
		$_Md5Key= $payment['config']['Fuyou_key'];//$payment['config']['Fuyou_key'];



		$_mchnt_cd = $request['mchnt_cd'];

		$_order_id = $request['order_id'];

		$_order_date = $request['order_date'];

		$_order_amt = $request['order_amt'];

		$_order_st = $request['order_st'];

		$_order_pay_code = $request['order_pay_code'];


		$_order_pay_error = $request['order_pay_error'];

		$_resv1 = $request['resv1'];

		$_fy_ssn = $request['fy_ssn'];

		

		$md5 = $request['md5'];



		//拼接数据
		$_data = "";
		$_data .= $_mchnt_cd."|";
		$_data .= $_order_id."|";
		$_data .= $_order_date."|";
		$_data .= $_order_amt."|";

		$_data .= $_order_st."|";
		

		$_data .= $_order_pay_code."|";


		$_data .= $_order_pay_error."|";

		$_data .= $_resv1."|";


		$_data .= $_fy_ssn."|";

		$_data .= $_Md5Key;

		//exit(print_r($_data));

		file_put_contents("08211.txt",print_r($request,true));

		file_put_contents("08212.txt",print_r($payment['config'],true));

		

		$_md5 = MD5($_data); //签名数据

        if ($md5 != $_md5 ) {
        	echo "Md5CheckFail";
        } else {
			if($_order_st==11)
			{
				$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$_order_id."'");
				require_once APP_ROOT_PATH."system/libs/cart.php";
				$rs = payment_paid($payment_notice['id'],$_fy_ssn);						
				
				$is_paid = intval($GLOBALS['db']->getOne("select is_paid from ".DB_PREFIX."payment_notice where id = '".intval($payment_notice['id'])."'"));
				if ($is_paid == 1){
					if($payment_notice["debit_type"] > 0)
						app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['id'],"from"=>"debit"))); //支付成功
					else
						app_redirect(url("index","payment#incharge_done",array("id"=>$payment_notice['id']))); //支付成功
				}else{
					if($payment_notice["debit_type"] > 0)
						app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'],"from"=>"debit")));
					else
						app_redirect(url("index","payment#pay",array("id"=>$payment_notice['id'])));
				}
			}
			else
			{
				echo "OrderFail";
			}
        }
    }
    
     public function notify($request) {
		$return_res = array(
            'info' => '',
            'status' => false,
        );

		 /*获取支付信息*/
        $payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Fuyou'");  
    	$payment['config'] = unserialize($payment['config']);
    	
		//$_Md5Key= $payment['config']['Fuyou_key'];
		$_Md5Key= $payment['config']['Fuyou_key'];//$payment['config']['Fuyou_key'];



		$_mchnt_cd = $request['mchnt_cd'];

		$_order_id = $request['order_id'];

		$_order_date = $request['order_date'];

		$_order_amt = $request['order_amt'];

		$_order_st = $request['order_st'];

		$_order_pay_code = $request['order_pay_code'];


		$_order_pay_error = $request['order_pay_error'];

		$_resv1 = $request['resv1'];

		$_fy_ssn = $request['fy_ssn'];

		$md5 = $request['md5'];



		//拼接数据
		$_data = "";
		$_data .= $_mchnt_cd."|";
		$_data .= $_order_id."|";
		$_data .= $_order_date."|";
		$_data .= $_order_amt."|";

		$_data .= $_order_st."|";
		

		$_data .= $_order_pay_code."|";


		$_data .= $_order_pay_error."|";

		$_data .= $_resv1."|";


		$_data .= $_fy_ssn."|";

		$_data .= $_Md5Key;
		

		$_md5 = MD5($_data); //签名数据


		
       

        if ($md5 != $_md5) {
        	echo "Md5CheckFail";
        } else {
			if($_order_st==11)
			{
				$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$_order_id."'");
				$order_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
				require_once APP_ROOT_PATH."system/libs/cart.php";
				$rs = payment_paid($payment_notice['id'],$_fy_ssn);						
				if($rs)
				{
					
						$rs = order_paid($payment_notice['order_id']);				
						if($rs)
						{
							//开始更新相应的outer_notice_sn					
							//$GLOBALS['db']->query("update ".DB_PREFIX."payment_notice set outer_notice_sn = '".$gopayOutOrderId."' where id = ".$payment_notice['id']);
							echo "ok";
						}
						else 
						{
							echo "ok";
						}
					
				}
				else
				{
					echo "OrderFail";
				}
			}
			else
			{
				echo "OrderFail";
			}
        }
    }

    public function get_display_code() {
        $payment_item = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment where class_name='Fuyou'");
		if($payment_item)
		{
			$payment_cfg = unserialize($payment_item['config']);
			$html = "<style type='text/css'>.baofoo_types{ background:url(".SITE_DOMAIN.APP_ROOT."/system/payment/Baofoo/banklogo.gif) no-repeat 0 0; float:left; display:block;width:150px; height:14px;font-size:0px;  text-align:left; padding:15px 0px;}";
	        $html .=".baofoo_types input{margin:0 }";
	        $html .=".bk_type_0{background:url(".SITE_DOMAIN.APP_ROOT."/system/payment/Baofoo/baofoo.jpg) no-repeat 0px -2px; font-size:0px; width:150px; height:10px;}"; 
	        $html .=".bk_type_0{ width:150px; height:10px;}"; 
	        $html .=".bk_type_0803080000,.bk_type_4001{ background-position:10px -447px}"; //招商银行
	        $html .=".bk_type_0801020000,.bk_type_4002{ background-position:10px -406px}"; //工商银行
	        $html .=".bk_type_0801050000,.bk_type_4003{ background-position:10px -85px}"; //建设银行
	        $html .=".bk_type_0803100000,.bk_type_4004{ background-position:10px -366px}"; //浦发
	        $html .=".bk_type_0801030000,.bk_type_4005{ background-position:10px -47px}"; //农业
	        $html .=".bk_type_0803050000,.bk_type_4006{ background-position:10px -167px}"; //民生银行
	        
	        $html .=".bk_type_0803090000,.bk_type_4009{ background-position:10px -486px}"; //兴业银行
	        $html .=".bk_type_0803010000,.bk_type_4020{ background-position:10px -205px}"; //交通银行
	        $html .=".bk_type_0803030000,.bk_type_4022{ background-position:10px -126px}"; //中国光大银行
	        $html .=".bk_type_0801040000,.bk_type_4026{ background-position:10px -829px}"; //中国银行
	        $html .=".bk_type_0804031000,.bk_type_4032{ background-position:10px -614px}"; //北京银行
	        $html .=".bk_type_3033,.bk_type_4033{ background-position:10px -1051px}"; //BEA东亚银行	
	        $html .=".bk_type_3034,.bk_type_4034{ background-position:10px -1090px}"; //渤海银行
	        $html .=".bk_type_0804105840,.bk_type_4035{ background-position:10px -901px}"; //平安银行
	        $html .=".bk_type_0803060000,.bk_type_4036{ background-position:10px -246px}"; //广发银行
	        $html .=".bk_type_3037,.bk_type_4037{ background-position:10px -1130px}"; //上海农商银行
	        $html .=".bk_type_0801000000,.bk_type_4038{ background-position:5px -526px}"; //中国邮政储蓄银行
	        $html .=".bk_type_0803020000,.bk_type_4039{ background-position:10px -287px}"; //中信银行
	        $html .=".bk_type_3046,.bk_type_4046{ background-position:10px -1172px}"; //宁波银行
	        $html .=".bk_type_3047,.bk_type_4047{ background-position:10px -1220px}"; //日照银行
	        $html .=".bk_type_3048,.bk_type_4048{ background-position:10px -1263px}"; //河北银行
	        $html .=".bk_type_3049,.bk_type_4049{ background-position:10px -1307px}"; //湖南省农信联合社
	        $html .=".bk_type_3050,.bk_type_4050{ background-position:10px -1349px}"; //华夏银行
	        $html .=".bk_type_3051,.bk_type_4051{ background-position:10px -1390px}"; //威海市商业银行
	        $html .=".bk_type_3054,.bk_type_4054{ background-position:10px -1432px}"; //重庆农村商业银行
	        $html .=".bk_type_3055,.bk_type_4055{ background-position:10px -1472px}"; //大连银行
	        $html .=".bk_type_3056,.bk_type_4056{ background-position:10px -1514px}"; //东莞银行
	        $html .=".bk_type_3057,.bk_type_4057{ background-position:10px -1557px}"; //富滇银行
	        $html .=".bk_type_3059,.bk_type_4059{ background-position:10px -1599px}"; //上海银行
	        $html .=".bk_type_3080,.bk_type_4080{ background-position:10px -1645px}"; //银联在线
	        $html .="</style>";
	        $html .="<script type='text/javascript'>function set_bank(bank_id)";
			$html .="{";
			$html .="$(\"input[name='bank_id']\").val(bank_id);";
			$html .="}</script>";
			$is_show_jieji = false;
			$is_show_xyk = false;
			$html.= "<h3 class='clearfix tl'><b>富友支付</b></h3><div class='blank1'></div><hr />";
	        		$is_show_jieji = true;
	       foreach ($payment_cfg['baofoo_gateway'] AS $key=>$val)
	        {
			  
	            $html  .= "<label class='baofoo_types bk_type_".$key." ui-radiobox' rel='common_payment' title='".$this->payment_lang["baofoo_gateway_".$key]."' ><input type='radio' name='payment' value='".$payment_item['id']."' rel='".$key."' onclick='set_bank(\"".$key."\")' /></label>";
	        }
	        $html .= "<input type='hidden' name='bank_id' /><div class='blank'></div>";
			return $html;
		}
		else{
			return '';
		}
    }
    /**
     * 字符转义
     * @return string
     */
    function fStripslashes($string)
    {
            if(is_array($string))
            {
                    foreach($string as $key => $val)
                    {
                            unset($string[$key]);
                            $string[stripslashes($key)] = fStripslashes($val);
                    }
            }
            else
            {
                    $string = stripslashes($string);
            }

            return $string;
    }
    
    public function orderquery($payment_notice_id){
    	/*$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
		$order = $GLOBALS['db']->getRow("select order_sn,bank_id from ".DB_PREFIX."deal_order where id = ".$payment_notice['order_id']);
		
		$_TransID = $order['order_sn'];
		
		$payment_info = $GLOBALS['db']->getRow("select config from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
       
		$_MerchantID = $payment_info['config']['baofoo_account'];
        $_Md5Key = $payment_info['config']['baofoo_key'];
        
		//此处加入判断，如果前面出错了跳转到其他地方而不要进行提交
		$_Md5Sign = md5($_MerchantID.$_TransID.$_Md5Key);
		
		$info = @file_get_contents("http://paygate.baofoo.com/Check/OrderQuery.aspx?MerchantID=".$_MerchantID."&TransID=".$_TransID."&Md5Sign=".$_Md5Sign);
		
		if($info){
			$data = array();
			$data['status'] = 1;
			list($data['MerchantID'],$data['TransID'],$data['CheckResult'],$data["factMoney"],$data['SuccTime'],$data['Md5Sign']) = explode("|",$info);
			
			$CheckResult = $data['CheckResult'];
			
			if($CheckResult == "Y")
			{
				$data['CheckResult'] = "成功";
			}
			elseif($CheckResult == "F")
			{
				$data['CheckResult'] = "失败";
			}
			elseif($CheckResult == "P")
			{
				$data['CheckResult'] = "处理中";
				
			}
			elseif($CheckResult == "N"){
				$data['CheckResult'] = "没有订单";
			}
			
			$time_span = to_timespan($data['SuccTime'],"YmdHis");
			$data['SuccTime'] =  to_date($time_span,"Y-m-d H:i:s");
			
			$data['factMoney'] = format_price($data['factMoney']/100);
			
			echo json_encode($data);
		}
		else{
			$data['status'] = 0;
			echo json_encode($data);
		}*/
		
		$data['status'] = 1;
		$data['CheckResult'] = "4.0接口不支持查询";
		echo json_encode($data);
    }
}
?>
