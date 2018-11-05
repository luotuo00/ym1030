
<?php

require '../../system/common.php';

$payment_notice_id = intval($_REQUEST['payment_notice_id']);
require_once APP_ROOT_PATH."system/payment/Fuiouwap_payment.php";
$payment_object = new Fuiouwap_payment();
$post_url = $payment_object->get_payment($payment_notice_id);
					
//app_redirect($post_url);

$html = '<!doctype html><html><head><meta charset="utf-8"></head><body>
		<form name="form1" id="form1" method="post" action="'.$post_url['url'].'" target="_self">	
				<input name="ENCTP"  type="hidden" value="'.$post_url['ENCTP'].'">
				<input name="VERSION"  type="hidden" value="'.$post_url['VERSION'].'">		
				<input name="MCHNTCD"  type="hidden" value="'.$post_url['MCHNTCD'].'">
				<input name="FM"  type="hidden" value="'.$post_url['FM'].'">
				<input type="submit" value="提交"></input>
		</form>
		</body></html>
		 <script language="javascript">document.form1.submit();</script>
		';

		
echo $html;			
?>