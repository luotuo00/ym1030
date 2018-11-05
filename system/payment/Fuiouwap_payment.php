<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------


/* 测试商户号：0002900F0096235
秘钥：5old71wihg2tqjug9kkpxnhx9hiujoqj */


$payment_lang = array(
	'name'	=>	'富友快捷支付',
	'trade_process'	=>	'商户代码',
	'mer_key'		=>	'签名密钥',
);

$config = array(
	'trade_process'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //商户号: 
	'mer_key'	=>	array(
		'INPUT_TYPE'	=>	'0'
	), //密钥
);
/* 模块的基本信息 */
if (isset($read_modules) && $read_modules == true)
{
    $module['class_name']    = 'Fuiouwap';

    /* 名称 */
    $module['name']    = $payment_lang['name'];


   	/* 支付方式：1：在线支付；0：线下支付; 2:手机支付 */
    $module['online_pay'] = '2';

    /* 配送 */
    $module['config'] = $config;
    
    $module['lang'] = $payment_lang;
    $module['reg_url'] = '342042888';
    return $module;
}

require_once(APP_ROOT_PATH.'system/libs/payment.php');
class Fuiouwap_payment implements payment {	
	
	public function get_payment_code($payment_notice_id) {
		
        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
    
        $url = SITE_DOMAIN.APP_ROOT.'/callback/pay/Fuiouwap_pay.php?payment_notice_id='.$payment_notice_id;
        $url = str_replace("/mapi", "", $url);
        $pay = array();
        $pay['subject'] = $payment_notice['notice_sn'];
        $pay['body'] = '会员充值';
        $pay['total_fee'] = round($payment_notice['money'],2);
        $pay['total_fee_format'] = format_price($pay['total_fee']);
        $pay['out_trade_no'] = $payment_notice['notice_sn'];
        $pay['notify_url'] = $url;
        
        $pay['partner'] = '';//$payment_info['config']['alipay_partner'];//合作商户ID
        $pay['seller'] = '';//$payment_info['config']['alipay_account'];//账户ID
        
        $pay['key'] = '';//$payment_info['config']['alipay_key'];//支付宝(RSA)公钥
        $pay['is_wap'] = 1;//
        $pay['pay_code'] = 'Fuiouwap';
        
        return $pay;
    }
    
	public function get_payment($payment_notice_id) {
		
        $payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where id = ".$payment_notice_id);
        $user = $GLOBALS['db']->getRow("select *,AES_DECRYPT(mobile_encrypt,'".AES_DECRYPT_KEY."') as mobile_encrypt,AES_DECRYPT(idno_encrypt,'".AES_DECRYPT_KEY."') as idno from ".DB_PREFIX."user where id = ".$payment_notice['user_id']);

		$bank_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user_bank where user_id = ".$user['id']);
        
        $acct_name=$bank_info['real_name'];
        $id_no=$user['idno'];
        $card_no=str_replace(" ","",$bank_info['bankcard']);
        
        
        
		$payment_info = $GLOBALS['db']->getRow("select id,config,logo from ".DB_PREFIX."payment where id=".intval($payment_notice['payment_id']));
		$payment_info['config'] = unserialize($payment_info['config']);
       


        $_Merchant_url = SITE_DOMAIN.APP_ROOT.'/Fuiouwap_response.php';
		$_Merchant_url = str_replace("/mapi", "", $_Merchant_url);

        $_noticeUrl = SITE_DOMAIN.APP_ROOT.'/Fuiouwap_notify.php';
        $_noticeUrl = str_replace("/mapi", "", $_noticeUrl);
        
        
        $tradeProcess = trim($payment_info['config']['trade_process']);//商户代码，由支付系统为外部系统生成的唯一标示符
        $mer_key = trim($payment_info['config']['mer_key']);//KEY
        


	 	$merOrderNum = $payment_notice['notice_sn'];
	 	$tranAmt = round($payment_notice['money'],2)*100;
	 	
	 	
	 	$ENCTP=0;
	 	
	 	
	 	$MCHNTCD =$tradeProcess;
	 	$TYPE=10;
	 	$VERSION='2.0';
	 	$LOGOTP=0;
	 	$MCHNTORDERID=$merOrderNum;
	 	$USERID=$payment_notice['user_id'];
	 	$AMT=$tranAmt;
	 	$BANKCARD=$card_no;
	 	$BACKURL=$_noticeUrl;//后台通知
	 	$REURL=$_Merchant_url;//支付失败URL
	 	$HOMEURL=$_Merchant_url;//页面通知URL
	 	$NAME=$acct_name;
	 	//$NAME=iconv('GB2312', 'UTF-8', $NAME);
	 	
	 	$IDTYPE=0;
	 	$IDNO=$user['idno'];
	 	 
	 	
	 	
	 	
	 	
	 	//拼接数据
	 	$_data = "";
	 	$_data .= $TYPE."|";
	 	$_data .= $VERSION."|";
	 	$_data .= $MCHNTCD."|";
	 	$_data .= $MCHNTORDERID."|";
	 	$_data .= $USERID."|";
	 	$_data .= $AMT."|";
	 	$_data .= $BANKCARD."|";
	 	$_data .= $BACKURL."|";
	 	$_data .= $NAME."|";
	 	$_data .= $IDNO."|";
	 	$_data .= $IDTYPE."|";
	 	$_data .= $LOGOTP."|";
	 	$_data .= $HOMEURL."|";
	 	$_data .= $REURL."|";
	 	$_data .= $mer_key;
	 	
	 	
	 	//print_r($_data);exit();
	 	
	 	$_md5 = MD5($_data); //签名数据
	 	
	 	
	 	
	 	$FM="<ORDER><VERSION>2.0</VERSION><LOGOTP>0</LOGOTP><MCHNTCD>".$tradeProcess."</MCHNTCD><TYPE>10</TYPE><MCHNTORDERID>".$merOrderNum."</MCHNTORDERID><USERID>".$USERID."</USERID><AMT>".$tranAmt."</AMT><BANKCARD>".$BANKCARD."</BANKCARD><NAME>".$NAME."</NAME><IDTYPE>".$IDTYPE."</IDTYPE><IDNO>".$IDNO."</IDNO><BACKURL>".$BACKURL."</BACKURL><HOMEURL>".$HOMEURL."</HOMEURL><REURL>".$REURL."</REURL><REM1></REM1><REM2></REM2><REM3></REM3><SIGNTP>md5</SIGNTP><SIGN>".$_md5."</SIGN></ORDER>";
	 	
	 	
      	/*交易参数*/
      
	if ($tradeProcess=='0002900F0096235'){
		
		$parameter = array(
				'ENCTP' => $ENCTP,
				'VERSION'=> $VERSION,
				'MCHNTCD'=> $MCHNTCD,
				'FM'=> $FM,
				'url'=> 'http://www-1.fuiou.com:18670/mobile_pay/h5pay/payAction.pay',
				'_data'=>$_data
		);
		
	}
	else {
		
		$parameter = array(
				'ENCTP' => $ENCTP,
				'VERSION'=> $VERSION,
				'MCHNTCD'=> $MCHNTCD,
				'FM'=> $FM,
				'url'=> 'https://mpay.fuiou.com:16128/h5pay/payAction.pay',
				
				
		);
		
	} 
	
	

	
        return $parameter;
        
    }
	
	
	
	
	
	
	//异步
	  public function notify($request) {
		$return_res = array(
            'info' => '',
            'status' => false,
        );
		
		
		
		/* 取返回参数 */
		$VERSION = $request["VERSION"];
		$TYPE = $request["TYPE"];
		$RESPONSECODE = $request["RESPONSECODE"];
		$MCHNTCD = $request["MCHNTCD"];
		$MCHNTORDERID = $request["MCHNTORDERID"];//订单号
		$ORDERID = $request["ORDERID"];
		$BANKCARD = $request["BANKCARD"];
		$AMT = $request["AMT"];
		$SIGN = $request["SIGN"];
		
		
		
		
		
		//查询密要
		$payment = $GLOBALS['db']->getRow("select id,config from ".DB_PREFIX."payment where class_name='Fuiouwap'");  
    	$payment['config'] = unserialize($payment['config']);
        $mer_key = trim($payment['config']['mer_key']);
        
        
        //拼接数据
        $_data = "";
        $_data .= $TYPE."|";
        $_data .= $VERSION."|";
        $_data .= $RESPONSECODE."|";
        $_data .= $MCHNTCD."|";
        $_data .= $MCHNTORDERID."|";
        $_data .= $ORDERID."|";
        $_data .= $AMT."|";
        $_data .= $BANKCARD."|";
        $_data .= $mer_key;
         
        $_md5 = MD5($_data); //签名数据
        

        //file_put_contents("data100.txt", $RESPONSECODE);
		
	  if($SIGN == $_md5 and $RESPONSECODE == "0000"){
			$payment_notice = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."payment_notice where notice_sn = '".$MCHNTORDERID."'");
				
			require_once APP_ROOT_PATH."system/libs/cart.php";
			
			$rs = payment_paid($payment_notice['id'],$merOrderNum);						
            	
			$is_paid = intval($GLOBALS['db']->getOne("select is_paid from ".DB_PREFIX."payment_notice where id = '".intval($payment_notice['id'])."'"));
			if ($is_paid == 1){
				echo "200";//支付成功
				
			}else{
				echo "0";
			}
		}else{
			echo "0";
		}
	}	
	

	
//同步
     public function response($request) {
		$return_res = array(
            'info' => '',
            'status' => false,
        );

 
			
		
	$respCode = $request["RESPONSECODE"];
	
	
	//file_put_contents("data4.txt", $respCode);
	
		
		if($respCode == "0000"){

            showIpsInfo("支付成功",wap_url("member","uc_incharge_log#index"));
		   
		   }else{
		
		   	showIpsInfo("支付失败了",wap_url("member","uc_incharge#index"));
		    
		    }			
	}
    
   
    public function get_display_code() {
        return '';
    }

    
    public function orderquery($payment_notice_id){
    	
    }
	
	

	
}
?>
