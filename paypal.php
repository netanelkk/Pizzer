<?php
header('Content-Type: text/html; charset=UTF-8');
require_once('config.php');
$orderpro = '';
$payment = $_GET['payment']; $status = $_GET['status'];
if($payment == 'credit') { $key = 'כרטיס אשראי'; }else{ if($payment == 'call') { require_once('js/paycall.php'); $key = 'הטלפון'; }else{ $key = 'PayPal'; } }
if($status == 'done') {
$index = $_GET['index']; $cc = $_GET['ConfirmationCode']; $amount = $_GET['amount']; $custom = $_GET['custom'];
$needpay = orderprice($mysqli,$custom);
$client = new SoapClient("https://www.pelepay.co.il/pay/TrancAuthentication.asmx?wsdl");
$param = array('username'=>$peleu,'password'=>$pelep,'dealindex'=>$index,'confirmationcode'=>$cc,'amount'=>$amount);
$result = $client->AuthenticateTransaction($param)->AuthenticateTransactionResult;
if($result != 'True') {
echo 'העסקה נכשלה!';
die;
}else{
$q21 = $mysqli->query("SELECT * FROM `zz_purchases` WHERE `index`='$index'");
if($q21->num_rows > 0) {
echo 'עסקה זו כבר הוחשבה.';
die;
}else{
$mysqli->query("INSERT INTO `zz_purchases`(`index`,`uid`) VALUES('$index','$myid')");
if($amount > $needpay) { 
$mysqli->query("UPDATE `zz_order` SET `paid`='$needpay' WHERE `id`='$custom'");
}else{
$mysqli->query("UPDATE `zz_order` SET `paid`=`paid`+'$amount' WHERE `id`='$custom'");
}
} } }
$q0 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`='0' ORDER BY `id` DESC LIMIT 1");
$qq = $q0->fetch_assoc(); $orderid = $qq['id']; $orderpro = $qq['products']; $opaid = $qq['paid'];
echo <<<Print
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="rtl">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<link rel="stylesheet" href="$url/style.css" type="text/css" media="all"/>
<title>תשלום באמצעות $key</title>
<style>
.crt {width:auto !important;}
</style>
<div class="content" style="width:550px; margin:auto;">
<div class="title" style="margin: 10px 0 10px 0px; width:630px;">תשלום באמצעות $key</div><BR>
Print;

if($status == 'done') { echo '<div class="notice">התשלום בוצע בהצלחה!<BR>בעוד מספר שניות הסטטוס יתעדכן בדף התשלום.'; die; }else{
if($status == 'cancel') { echo '<div class="notice">בחרת לבטל את התשלום.<BR> לא חוייבת.</div>'; die; } }

if($canlogin != 1) {
echo '<div class="notice">אינך מחובר.</div>';
}else{
if($orderpro == Null) {
echo '<div class="notice">אין לך פריטים בהזמנה. <BR><a href="'.$url.'/order" style="font-size:15pt; color:#BCD7E0;">חזור להזמנה</a></div>';
}else{
if($paypal == Null && $payment != 'credit' && $payment != 'call') {
echo '<div class="notice">בעל האתר חסם את אפשרות התשלום דרך PayPal. <BR><a href="javascript: window.close()" style="font-size:15pt; color:#BCD7E0;">סגור חלון</a></div>';
}else{
if($pelem == Null || $pelep == Null || $peleu == Null && $payment == 'credit') {
echo '<div class="notice">בעל האתר חסם את אפשרות התשלום דרך כרטיס אשראי. <BR><a href="javascript: window.close()" style="font-size:15pt; color:#BCD7E0;">סגור חלון</a></div>';
}else{
if($paycall == Null && $payment == 'call') {
echo '<div class="notice">בעל האתר חסם את אפשרות התשלום דרך שיחת טלפון. <BR><a href="javascript: window.close()" style="font-size:15pt; color:#BCD7E0;">סגור חלון</a></div>';
}else{
$amount = round(orderprice($mysqli,$orderid)-$opaid,2);
if($amount < $minbill) {
echo 'יש להזמין סכום מינימלי של '.$minbill.' ₪ כדי לבצע משלוח.';
}else{
if(($amount < 2 || $amount > 100) && $payment == 'call') {
echo '<div class="notice">תשלום דרך הטלפון מותר רק בסכומים בין 1 ל 100 ש"ח.<BR><a href="javascript: window.close()" style="font-size:15pt; color:#BCD7E0;">סגור חלון</a></div>';
}else{
$q = "SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `products`!='' AND `status`='0' ORDER BY `id` DESC LIMIT 1";
orderdet($mysqli,$q,$minbill,"pp");
if($payment == 'call') { $amount = floor($amount);
$call = micropayment::getPremiumPhone($amount);
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$("#done").click(function(eventObject) {
eventObject.preventDefault();
var price = "$amount",premium = "$call",phone = $("#phone").val(),orderid = "$orderid";
$("#done").attr({'disabled':true,'value':'טוען..'});

$.ajax({type:"POST",url:"$url/action.php?do=paycall",data:({price:price,premium:premium,phone:phone,orderid:orderid}),success:function(data) {
$("#done").attr({'disabled':false,'value':'סיים רכישה'});
if(data == 'e1') {
$(".notice").html("התשלום נכשל. אנא בדוק כי המספר שהוקש תקין.").slideDown(400);
}else{
if(data == 'e2') {
$(".notice").html("רכישה זו כבר נחשבה.").slideDown(400);
}else{
$(".notice").html("התשלום בוצע בהצלחה!<BR>בעוד מספר שניות הסטטוס יתעדכן בדף התשלום.").slideDown(400);
} } }
});
});
});
</script>
אנא התקשר למספר הבא: <b>$call</b><BR>
<form action='' method="POST">
לאחר מכן, הקש את המספר שממנו חייגת: <input type="text" id="phone"><BR><BR>
<input type="submit" value="סיים רכישה" id="done"><div class="notice" style="display:none; margin-top:5px;"></div><BR><BR>
הסכום הסופי לתשלום הוא $amount ש"ח, ללא חיובים נוספים.<BR>
החיוב הינו חד פעמי.<BR>
מספר טלפון לבירורים ותמיכה: 03-7173333
</form>
Print;
}else{
if($payment == 'credit') {
echo <<<Print
<form name="pelepayform" action="https://www.pelepay.co.il/pay/paypage.aspx" method="post"><INPUT TYPE="hidden" value="$pelem" NAME="business"><INPUT TYPE="hidden" value="$amount" NAME="amount"><INPUT TYPE="hidden" value="הזמנה מספר $orderid ב $sitename" NAME="description"><INPUT TYPE="hidden" value="$orderid" NAME="custom"><input type="hidden" value="$url/confirm" name="success_return"><input type="hidden" value="$url/paypal.php?payment=credit&status=cancel" name="cancel_return"><input type="image" src="http://www.pelepay.co.il/btn_images/pay_button_6.gif" name="submit" alt="Make payments with pelepay"></form>
<BR><BR>
<b>תשלום מאובטח מאת:</b><BR>
<a href="https://www.pelepay.co.il" title="סליקת כרטיסי אשראי" target="_blank"><img src="https://www.pelepay.co.il/images/banners/respect_pp_13b.gif" width="134" height="38" alt="סליקת כרטיסי אשראי" /></a>
<BR>
פרטי הכרטיס אשראי לא יחשפו לבעל האתר.<BR>
מספר טלפון לבירורים ותמיכה: 077-3270050
Print;
}else{
if(isset($_POST['up'])) {
$time = $_POST['time'];
        $p->add_field('business', $paypal);
        $p->add_field('return', $url.'/pay');
        $p->add_field('cancel_return', $url.'/pay');
        $p->add_field('notify_url', $url.'/paypal/ipn.php?action=ipn&amount='.$amount.'&id='.$orderid);
        $p->add_field('item_name', 'order #'.$orderid);
        $p->add_field('amount', $amount);
        $p->add_field('currency_code','ILS');
        $p->submit_paypal_post();
}
echo '<BR><div style="width:200px;margin:auto;"><form action="" method="POST"><input type="submit" value="שלם" name="up"></form></div>';
} } } } } } } } }
echo '</div>';
?>