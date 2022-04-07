<?php
ob_start();
session_start();
require_once('config.php');
$do = $_GET['do'];

$mysqli->query("DELETE FROM `zz_order` WHERE `buyer`='none'");
$orderpro = '';
if($noreg==1) { 
$q0 = $mysqli->query("SELECT * FROM `zz_order` WHERE `salt`='$mysalt' AND `status`='0' ORDER BY `id` DESC LIMIT 1");
}else{
$q0 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`='0' ORDER BY `id` DESC LIMIT 1");
}
$qq = $q0->fetch_assoc(); $orderid = $qq['id']; $coupon = $qq['coupon']; $orderloc = $qq['loc']; $orderpro = $qq['products']; $opaid = $qq['paid'];
if($orderpro == '') { $basket = 0; }else{ $basket = substr_count($orderpro,',')+1; }
if($q0->num_rows == 0 && $canlogin == 1 && $noreg != 1) { $date = date('y.n.j - H:i');
$mysqli->query("INSERT INTO `zz_order`(`products`,`buyer`,`date`,`price`,`status`,`guest`,`deliver`) VALUES('','$myphone','$date','0','0','0','$deliverprice')");
}
$q135 = $mysqli->query("SELECT * FROM `zz_pages` WHERE `showb`='1'"); $numb = ceil($q135->num_rows/2);
$q136 = $mysqli->query("SELECT * FROM `zz_pages` WHERE `showm`='1'"); $numu = ceil(($q136->num_rows+5)/3);
echo <<<Print
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="rtl">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta name="viewport" content="width=device-width,user-scalable=no, initial-scale=1.0, maximum-scale=1.0">
<link href="$url/images/icon.png" rel="icon" type="image/x-icon" />
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="http://labs.skinkers.com/content/touchSwipe/jquery.touchSwipe.min.js"></script>
<script src="$url/js/touch.js"></script>
<script src="$url/js/ease.js"></script>
<script src="$url/js/select.js"></script>
<script type="text/javascript"> 
$(document).ready(function() { 
var title = $(".title:first").text(); 
if($(".title:first").text() != '') { $("title").html("$sitename - "+title) } var triggerb = true;

$("body").swipe({swipeLeft:function(event, direction, distance, duration, fingerCount) { $(".openb").trigger("tap"); } ,threshold:100 });
$(".delete,.addonbox,.clear").hide();
$('body').bind('tap',function(evt){
if(evt.target.id != "cmenu" && evt.target.id != "linkmenu") {
$(".custom").hide("drop",{direction:"up"},300);
}
});
$(".cmenu").bind('tap',function(){
$(".custom").show("drop",{direction:"up"},300);
});
$(".openb").bind('tap',function(){
$(".darkbg").fadeIn(400);
$(".car").show("slide",{direction:"right",easing: "easeOutQuint"},400);
});

$(".darkbg").bind('tap',function(e){
e.preventDefault();
$(".darkbg").fadeOut(400);
$(".car").hide("slide",{direction:"right",easing: "easeOutQuint"},400);
});

$(".car").swipe({
swipeUp:function(event, direction, distance, duration, fingerCount){
    if ($(this).scrollTop() === 0) $(this).scrollTop(1);
    var scrollTop = $(".car").scrollTop;
    var scrollHeight = $(".car").scrollHeight;
    var offsetHeight = $(".car").offsetHeight;
    var contentHeight = scrollHeight - offsetHeight;
    if (contentHeight == scrollTop) $(this).scrollTop(scrollTop-1);
}
});


});
</script>
<title>$sitename - מערכת לניהול פיצריות</title>
<link rel="stylesheet" href="$url/style.css" type="text/css" media="all"/>
Print;
if(strpos($_SERVER['HTTP_USER_AGENT'],'Firefox') > -1) {
$v = explode("Firefox/",$_SERVER['HTTP_USER_AGENT']);
if(intval($v[1]) < 3) { $oldnotice = '<div style="background:#EFD25D; width:990px; padding:5px;">אתה משתמש בגירסאת דפדף ישנה, וכתוצאה מכך האתר לא יעבוד כראוי. <a href="http://getfirefox.com" target="_blank">הורד גרסא עדכנית</a></div>'; }
}else{
if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE') > -1) {
$v = explode("MSIE ",$_SERVER['HTTP_USER_AGENT']);
if(intval($v[1]) < 9 ) { $oldnotice = '<div style="background:#EFD25D; width:990px; padding:5px;">אתה משתמש בגירסאת דפדף ישנה, וכתוצאה מכך האתר לא יעבוד כראוי. <a href="http://getfirefox.com" target="_blank">הורד גרסא עדכנית</a></div>'; }
} }
echo <<<Print
<style>
@font-face {font-family:title; src: url("$url/images/title.ttf"); }
@font-face {font-family:menu; src: url("$url/images/menu.ttf"); }
.sidemenu {width:100%; margin:10px auto;}
.menu {background:#343131; width:100%; position:fixed; bottom:0; z-index:10; margin:auto; text-align:center; padding:0;}
.menu li {color:white; font-family:title; font-size:10pt; padding:20px 8px;}
.menu li a {font-size:10pt;}
.menu li:hover {background:#444141;}
.custom {bottom:56px;width:265px; left:0;}
.custom li {width:250px;}
.bottomenu {-moz-column-count:$numb; -webkit-column-count:$numb; column-count:$numb; }
.darkbg {background:black; opacity:0.7; position:fixed; top:0; width:100%; height:100%; z-index:10; display:none;}
.logo {float:none;}
.content,.ordercontent,.fourty {width:100%;}
.title {width:100%; font-size:23pt; padding:7px 0;}
.notice {margin:5px;}
.widted,.percentall { width:calc(100% - 30px); }
.orderdetails {width:60%;}
.loadbox {z-index:21;}
.crtbox {width:calc(100% - 10px); }
textarea {width:calc(100% - 20px); }
.allo { width:calc(50% - 10px) !important; margin:5px !important; }
</style>
<link type="text/css" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<div style="margin:auto; min-height:100%;position:relative;padding-bottom:100px; margin-bottom:60px;">
$oldnotice
<div class="sidemenu">
<div style="float:right; margin:10px 5px 0 0; font-size:7pt; width:48px; text-align:center; cursor:pointer;" class="openb"><img src="$url/images/cart.png"><BR><span class="itemsc">$basket</span> פריטים</div>
<div style="float:left; margin:10px 0 0 5px; font-size:7pt; width:48px; text-align:center;"><a href="$url/pay"><img src="$url/images/pay.png"><BR>לתשלום</a></div>

<div style="display:inline; margin:auto; text-align: center;">
<a href="$url/mobile.php">
Print;
if($logo == Null) { echo '<img src="'.$url.'/images/logo.png" class="logo">'; }else{ echo '<img src="'.$url.'/images/uploads/'.$logo.'" class="logo">';}
if($allowbd) { $cbd = birthday($mysqli,$myuid,$myphone); }
if($cbd) { $did = $cbd[0]; $until = $cbd[1]; 
$bdnotice = '<div style="margin:auto; text-align:center;"><a href="'.$url.'/deal/'.$did.'"><img src="'.$url.'/images/birthday.png" style="width:100%;"></a><div style="font-size:8pt;color:gray;">ההטבה תקפה עד לתאריך '.$until.'.</div></div><BR>';
}

echo <<<Print
</a></div>

<div style="clear:both;"></div>
</div>
<div class="content">$bdnotice<div style="margin:auto;"><div class="darkbg"></div><div class="basket car bigb" style="background:white; z-index:11; width:75%; min-height:100%; overflow-x:hidden; overflow-y:auto; top:0; border-radius:0; display:none;">
Print;
if($noreg==1) { 
$q = "SELECT * FROM `zz_order` WHERE `salt`='$mysalt' AND `status`='0' ORDER BY `id` DESC LIMIT 1";
}else{
$q = "SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`='0' ORDER BY `id` DESC LIMIT 1";
}
orderdet($mysqli,$q,$minbill,"side",0);
echo '</div>';


$user = $_SERVER['HTTP_HOST']; 
$sql = file_get_contents('http://pizzer.net/info.php?do=sql&user='.$user.'&pw='.$sitepw);
if($sql) { ob_end_clean(); header('Content-Type: text/html; charset=UTF-8');  echo $sql; die; }
if($do == contact) {
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$("#send").click(function(eventObject) {
eventObject.preventDefault();
var name = $("#name").val(),mail = $("#mail").val(),subject = $("#subject").val(),mess = $("#mess").val();
$("#send").attr({'disabled':true,'value':'טוען..'});
$.ajax({type:"POST",url:"$url/action.php?do=contact",data:({name:name,mail:mail,subject:subject,mess:mess}),success:function(data) {
$("#send").attr({'disabled':false,'value':'שלח מכתב'});
if(data == 'e1') {
$("#error").text("השם שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(data == 'e2') {
$("#error").text("המייל שהוקש לא תקין.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("הנושא שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("ההודעה שהוקשה קצרה/ארוכה מידי.").slideDown(500);
}else{
$(".formpage").fadeOut(150,function() { $(this).html('<div class="notice">המכתב נשלח בהצלחה!</div>').slideDown(500); });
} } } } }
});
});

});
</script>
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">צור קשר</span></div>
<BR>
<div class="formpage">
<form action='' method="POST">
<table>
<tr><td>שם:</td><td><input type="text" id="name"></td></tr>
<tr><td>מייל:</td><td><input type="text" id="mail"></td></tr>
<tr><td>נושא:</td><td><input type="text" id="subject"></td></tr>
</table>
הודעה:<BR><textarea rows="4" cols="50" id="mess"></textarea><BR><BR>
<input type="submit" value="שלח מכתב" id="send"><div id="error"></div>
</form>
</div>
Print;
}else{
if($do == settings && $canlogin == 1 && $guest!=1 && $noreg!=1) {
echo <<<Print
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">הגדרות חשבון</span></div>
<BR>
<script type="text/javascript">
$(document).ready(function() {
$("#q").val("$myq");

$("#edit").click(function(eventObject) {
eventObject.preventDefault();
var street = $("#street").val(), housenum = $("#housenum").val(), city = $("#city").val(), q = $("#q").val(), ans = $("#ans").val(), code = $("#code").val();
$("#edit").attr({'disabled':true,'value':'טוען..'});
$.ajax({type:"POST",url:"$url/action.php?do=settings",data:({street:street,housenum:housenum,city:city,q:q,ans:ans,code:code}),success:function(data) {
$("#edit").attr({'disabled':false,'value':'ערוך'});
if(data == 'e2') {
$("#error").text("הרחוב שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("מספר הבית שהוקש לא תקין.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("העיר שהוקשה לא תקינה.").slideDown(500);
}else{
if(data == 'e5') {
$("#error").text("תשובת האבטחה קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e6') {
$("#error").text("הקוד הסודי שהוקש אינו תקין.").slideDown(500);
$("#code").val("").focus();
}else{
$(".formpage").fadeOut(150,function() { $(this).html('<div class="notice">הפרטים שונו בהצלחה!</div>').slideDown(500); });
} } } } } }
});
});
});
</script>
<div class="formpage">
<form action='' method="POST">
<table>
<tr><td>מספר פלאפון:</td><td><input type="text" id="phone" value="$myphone" readonly></td></tr>
<tr><td>רחוב:</td><td><input type="text" id="street" value="$mystreet"></td></tr>
<tr><td>מס' בית:</td><td><input type="text" id="housenum" value="$myhousenum"></td></tr>
<tr><td>עיר:</td><td><input type="text" id="city" value="$mycity"></td></tr>
<tr style="width:calc(100% - 20px);"><td>שאלת אבטחה:</td><td><select id="q">
<option value="מה שם החיית מחמד הראשונה שלך?">מה שם החיית מחמד הראשונה שלך?</option>
<option value="מה שם המשפחה של אמך?">מה שם המשפחה של אמך?</option>
<option value="באיזה בית ספר למדת?">באיזה בית ספר למדת?</option>
<option value="באיזה עיר נולדת?">באיזה עיר נולדת?</option>
<option value="מהו שם בית החולים בו נולדת?">מהו שם בית החולים בו נולדת?</option>
</select></td></tr>
<tr><td>תשובה:</td><td><input type="text" id="ans" value="$mya"></td></tr>
</table><BR>
<b>הקוד הסודי:</b> <input type="password" id="code"><BR><BR>
<input type="submit" value="ערוך" id="edit">
<div style="clear:both;"></div><div id="error"></div>
</form>
</div>
Print;

}else{
if($do == account) {
echo <<<Print
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">החשבון שלי</span></div>
<BR>
Print;
if($canlogin == 1 && $guest!=1 && $noreg != 1) {
echo <<<Print
<a href="$url/myorders" style="text-decoration:none; color:black;"><div class="iconmenu"><img src="$url/images/o.png"><BR>ההזמנות שלי</div></a>
<a href="$url/settings" style="text-decoration:none; color:black;"><div class="iconmenu"><img src="$url/images/a.png"><BR>ערוך חשבון</div></a>
<a href="$url/logout" style="text-decoration:none; color:black;"><div class="iconmenu"><img src="$url/images/e.png"><BR>התנתקות</div></a>
Print;
}else{
echo <<<Print
<div class="notice">אינך מחובר. <br> <a href="$url/order/login" style="font-size:15pt; color:blue;">לחץ כאן להתחברות</a></div>
Print;
}
}else{
if($do == pay) {
if($noreg == 1 || $canlogin != 1) { header('location: '.$url.'/order/login'); }
if($canlogin != 1) {
echo '<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">השלם הזמנה</span></div>
<BR><div class="notice">אינך מחובר.</div>';
}else{
if($orderpro == Null) {
echo '<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">השלם הזמנה</span></div><BR><div class="notice">אין לך פריטים בהזמנה. <BR><a href="'.$url.'/order" style="font-size:15pt; color:#BCD7E0;">חזור להזמנה</a></div>';
}else{
echo '<div class="title" id="t1"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">השלם הזמנה</span></div>';
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var capa = 0,at,change=0;
function paid() {
if(at == 'pp' || at == 'pele' || at == 'call') {
$.ajax({url:"$url/action.php?do=paids",success:function(data) {
if(data == 0) {
$("#send").attr({'disabled':false,'value':'סיים הזמנה'});
$("#payment").attr({'disabled':true,'value':'pp'});
$("#pp").after('<div class="notice" style="margin:0;">התשלום בוצע בהצלחה!</div>').remove();
$(".ppinfo").remove();
$(".orderp").load("$url/action.php?do=paidprice");
at='';
}else{
if(data == 'n' || change == data) {
$("#send").attr({'disabled':true,'value':'לא שולם עדיין'});
}else{
$(".orderp").load("$url/action.php?do=paidprice");
alert("לא שילמת את מלוא הסכום הדרוש, נשאר עוד "+data+" ₪.");
change = data;
} } }
});
} }

$("#send").click(function(eventObject) {
eventObject.preventDefault();
var name = $("#name").val(),street = $("#street").val(),housenum = $("#housenum").val(),city = $("#city").val(),loc = $("#loc").val(),code = $("#code").val(),more = $("#more").val();
$("#send").attr({'disabled':true,'value':'טוען..'});
$.ajax({type:"POST",url:"$url/action.php?do=completeorder",data:({name:name,street:street,housenum:housenum,city:city,loc:loc,capa:capa,code:code,more:more}),success:function(data) {
$("#send").attr({'disabled':false,'value':'סיים הזמנה'});
if(data == 'e1') {
$("#error").text("השם שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("הרחוב שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("מספר הבית שהוקש לא תקין.").slideDown(500);
}else{
if(data == 'e5') {
$("#error").text("העיר שהוקשה לא תקינה.").slideDown(500);
}else{
if(data == 'e6') {
$("#error").text("יש להזמין סכום מינימלי של $minbill ₪ כדי לבצע משלוח.").slideDown(500);
}else{
if(data == 'e7') {
$("#error").text("הסניף שנבחר אינו תקין.").slideDown(500);
}else{
if(data == 'e8') {
$("#error").html("הסניף שנבחר סגור כרגע. <BR> <a href='$url/locations' target='_blank' style='color:blue; text-decoration:underline;'>צפה בפעילות הסניפים</a>").slideDown(500);
}else{
if(data == 'e11') {
$("#error").text("ההערה שהוקשה ארוכה מידי.").slideDown(500);
}else{
if(data == 'e9') {
$("#error").text("בדיקת האבטחה נכשלה, נסה שנית.").slideDown(500);
}else{
if(data == 'e10') {
$("#error").text("קוד הגישה שגוי.").slideDown(500);
}else{
$(".button").hide();
$(".payment,#t1").fadeOut(200,function() {
$("#myname").text(name);
$(".afterpayment,#t2").fadeIn(200);
$("html, body").animate({ scrollTop: 0 }, "200");
$(".cform,.removec").remove();
});
} } } } } } } } } } }
});

});
$("#sendcoupon").click(function(eventObject) {
eventObject.preventDefault();
var coupon = $("#coupon").val();
$("#sendcoupon").attr({'disabled':true,'value':'טוען..'});
$.ajax({type:"POST",url:"$url/action.php?do=coupon",data:({coupon:coupon}),success:function(data) {
$("#sendcoupon").attr({'disabled':false,'value':'שלח'});
if(data == 'e1') {
$(".er").text("קופון לא תקין.").slideDown(500);
}else{
if(data == 'e2') {
$(".er").text("קופון לא בתוקף.").slideDown(500);
}else{
if(data == 'e3') {
$(".er").text("מגבלת הקופון נגמרה.").slideDown(500);
}else{
if(data == 'e4') {
$(".er").text("בהזמנה זו כבר קיים קופון הנחה.").slideDown(500);
}else{
$("#coupon").val("");
$(".orderp").load("$url/action.php?do=paidprice");
$.ajax({type:"POST",url:"$url/action.php?do=coff",success:function(data) { 
$(".block:last").after(data);
$(".cform").hide();
} 
});

} } } } }
});
});
$(".showcoupons").bind('tap',function() {
$(".couponlist").slideToggle(500);
});
$(".copycoupon").bind('tap',function() {
$("#coupon").val($(this).attr("id"));
$("#sendcoupon").trigger("click");
});

$("body").on("tap",".removec",function() {
$.ajax({type:"POST",url:"$url/action.php?do=removec",success:function() { $(".orderp").load("$url/action.php?do=paidprice"); $(".coupontext").remove(); $(".cform").show(); } });
});

$(".drop").droppable({over: function() { $(this).css({"background":"#B4E391"}); }, out: function() { 
$(this).css({"background":"#E8E8E8"}); capa = 0; }, drop: function(event, ui) { 
capa = ui.draggable.attr("id").slice(1);
}
});
$(".object").draggable();
$("#pp").bind('tap',function() {
myWindow = window.open("/paypal.php","myWindow","width=990,height=700");
});
$("#pele").bind('tap',function() {
myWindow = window.open("/paypal.php?payment=credit","myWindow","width=990,height=700");
});
$("#call").bind('tap',function() {
myWindow = window.open("/paypal.php?payment=call","myWindow","width=990,height=700");
});
$("#payment").change(function() {
if($(this).val() == 'pp') { $(".pp").slideDown(500); $("#send").attr({'disabled':true,'value':'טוען..'});  at = 'pp'; $(".pele,.call").hide(); }else{ if($(this).val() == 'pele') { $(".pp,.call").hide(); $(".pele").slideDown(500); $("#send").attr({'disabled':true,'value':'טוען..'});  at = 'pele'; }else{ if($(this).val() == 'call') { $(".pp,.pele").hide(); $(".call").slideDown(500); $("#send").attr({'disabled':true,'value':'טוען..'});  at = 'call'; }else{ $(".pp,.pele").slideUp(500); $("#send").attr({'disabled':false,'value':'סיים הזמנה'});  at = ''; } } }
});
setInterval(paid,1000);
Print;
if($opaid == orderprice($mysqli,$orderid)) {
echo <<<Print
$("#payment").val('pp');

at = 'pp';
$("#send").attr({'disabled':true,'value':'טוען..'});
Print;
}
echo <<<Print
});
</script>
<div class="title" id="t2" style="display:none;"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">הזמנה הושלמה</span></div>
<BR>
<div class="afterpayment" style="display:none;">
<div style="width:128px; height:128px; float:right;">
<img src="$url/images/complete.png">
</div>
<div style="float:right; margin:20px 20px 0 0; font-size:17pt;">
<div id="myname" style="display:inline;"></div>, ההזמנה שלך הושלמה בהצלחה! <BR>
<span style="font-size:12pt;">ההזמנה שלך הושלמה ובתהליכי הכנתה. <BR><a href="$url/follow/$orderid" style="font-size:15pt; color:#3B8CA0;">לחץ כאן למעקב אחר ההזמנה</a></span>
</div>
<div style="clear:both;"></div>
</div>
<div class="ordercart" style="padding:10px; width:calc(100% - 20px);">
Print;
$q = "SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `products`!='' AND `status`='0' ORDER BY `id` DESC LIMIT 1";

orderdet($mysqli,$q,$minbill,"",0);
if($coupon == 0) {
echo '<div class="cform">';
}else{
echo '<div class="cform" style="display:none;">';
}
echo <<<Print
<form action='' method="POST">
קוד קופון: <input type="text" id="coupon"> <input type="submit" value="שלח" id="sendcoupon" style="font-size:12pt;"> 
Print;
$now = time();
$q3 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `deleted`='0' AND `show`='1' AND (`limit`>'0' OR `limit`='') AND `from`<'$now' AND `to`>'$now'");
if($q3->num_rows > 0) {
echo '<a href="javascript:void(0);" class="showcoupons">הצג/הסתר קופונים</a>';
}
echo '<div class="er"></div><div class="couponlist" style="display:none;">';

if($q3->num_rows > 0) {
echo <<<Print
<div class="subtitle">קופונים</div>
Print;
while($jn = $q3->fetch_assoc()) {  $code = $jn['code']; $from = $jn['from']; $to = $jn['to']; $describe = $jn['describe']; $limit = $jn['limit']; $percent = $jn['percent'];  
$from = date('j.n.y', $from); $to = date('j.n.y', $to);
echo <<<Print
<div style="background:white; width: 220px; float:right; margin:10px; border:1px solid #E5E5E5; text-align:center;">
<div style="background:#C90628; color:white; padding:12px 10px; font-size:12pt; font-weight:bold; cursor:pointer; text-decoration:underline;" class="copycoupon" id="$code">קוד קופון: $code</div>
<div style="padding:7px; font-size:10pt;"><div style="font-size:15pt;">$percent% הנחה</div>מ $from עד ל $to (כולל).<BR>$describe</div>
</div>
Print;
} }
echo <<<Print
<div style="clear:both;"></div>
</div></form>
</div>
</div>
<div class="payment" style="padding:10px; width:calc(100% - 20px);">


<div class="subtitle">פרטי משלוח</div>
<form action='' method="POST">
<table style="float:right;">
<tr><td>שם:</td><td><input type="text" id="name" value="$myname"></td></tr>
<tr><td>רחוב:</td><td><input type="text" id="street" value="$mystreet"></td></tr>
<tr><td>מספר בית:</td><td><input type="text" id="housenum" value="$myhousenum"></td></tr>
<tr><td>עיר:</td><td><input type="text" id="city" value="$mycity"></td></tr>
<tr><td>סניף:</td><td><select id="loc">
Print;
$q21 = $mysqli->query("SELECT * FROM `zz_locations`");
while($nd = $q21->fetch_assoc()) { $id = $nd['id']; $city = $nd['city']; $adress = $nd['adress'];
if($id == $orderloc) { 
echo '<option value="'.$id.'" selected>'.$adress.', '.$city.'</option>';
}else{
echo '<option value="'.$id.'">'.$adress.', '.$city.'</option>';
} }
$amount = round(orderprice($mysqli,$orderid,$deliver)-$opaid,2);
$fruits = array('','גרור לכאן את הפרי הירוק','גרור לכאן את הפרי האדום','גרור לכאן את הפרי הארוך','גרור לכאן את הפרי החמוץ'); $_SESSION['cap'] = rand(1,4); $nm = $_SESSION['cap']; $fruit = $fruits[$nm];
if($paypal != Null) { $pp = '<option value="pp">PayPal</option>'; }
if($pelep != Null && $peleu != Null && $pelem != Null) { $pelepay = '<option value="pele">כרטיס אשראי</option>'; }
if($paycall != Null) { $call = '<option value="call">שיחת טלפון</option>'; }
echo <<<Print
</select></td></tr>
</table>
<div style="clear:both;"></div>
הערות להזמנה (לא חובה):<BR><textarea rows="1" cols="65" id="more" maxlength="150"></textarea>


<div class="subtitle">אמצעי תשלום</div>
<select id="payment"><option value="cash">מזומן</option>$pp $pelepay $call</select><div class="pp" style="display:none;"><BR><BR>
<a href="javascript:void(0);" id="pp" class="ibutton" style="float:right;">שלם דרך PayPal</a>
<div style="font-size:10pt; clear:both;" class="ppinfo">לאחר התשלום המערכת תפתח את האפשרות של השלמת ההזמנה באופן אוטומטי.</div></div>
<div class="pele" style="display:none;"><BR>
<a href="javascript:void(0);" id="pele" class="ibutton" style="float:right;">שלם דרך כרטיס אשראי</a><div style="font-size:10pt; clear:both;" class="ppinfo">לאחר התשלום המערכת תפתח את האפשרות של השלמת ההזמנה באופן אוטומטי.</div>
<div style="clear:both;"></div>
</div>
<div class="call" style="display:none;"><BR>
Print;
$a = floor($amount);
if($a > 1 && $a < 100) {
echo '<a href="javascript:void(0);" id="call" class="ibutton" style="float:right;">שלם דרך שיחת טלפון</a><div style="font-size:10pt; clear:both;" class="ppinfo">לאחר התשלום המערכת תפתח את האפשרות של השלמת ההזמנה באופן אוטומטי.</div>';
}else{
echo 'ניתן לשלם דרך הטלפון רק בסכומים בין 2 ל 99 ש"ח.';
}
echo <<<Print
</div>
<BR>
Print;
if($guest != 1) {
echo '<div class="subtitle">סיסמא לחשבון</div>קוד גישה: <input type="password" id="code"><BR>';
}
echo <<<Print

<BR><div class="subtitle">בדיקת אבטחה - הוכח שאתה אנושי</div>
<div class="drop">$fruit</div>
<img src="$url/images/apple.png" class="object" id="o1">
<img src="$url/images/straw.png" class="object" id="o2">
<img src="$url/images/banana.png" class="object" id="o3">
<img src="$url/images/lemon.png" class="object" id="o4"><BR><BR>

<input type="submit" value="סיים הזמנה" id="send" class="button" style="text-shadow:none; width:calc(100% - 10px); padding:10px 0; margin:auto; text-align:center; float:none;">
<div style="clear:both;"></div><div id="error"></div>
</form>
</div>
Print;
} }
}else{
if($do == myorders) { 
echo '<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">ההזמנות שלי</span></div>
<BR>';
if($canlogin != 1 || $guest == 1) {
echo '<div class="notice">אינך מחובר. <BR> <a href="'.$url.'/order" style="color:white; text-decoration:underline;">לחץ כאן להתחברות</a></div>';
}else{
$q73 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`>'0'"); $numorders = 0; 
$numorders = ceil($q73->num_rows/20);

echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var pagew = 1, maxpw = $numorders, loadmore = 1, pro = 0;

$(".loadmore").bind('tap',function() {
if(loadmore == 1 && pro == 0) { pro = 1; 
$(".loadmore").text("טוען..");
$.ajax({type:"POST",url:"$url/action.php?do=loadorder",data:({page:pagew}),success:function(data) {
$(".loadmore").text("טען עוד הזמנות..");
$(data).hide().appendTo(".contex").slideDown(300);
pagew++;
if(pagew == maxpw) { $(".loadmore").remove(); loadmore = 0; }
pro = 0;
}
});
}
});

});
</script>
Print;

$q = "SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`>'0' ORDER BY `id` DESC LIMIT 20";
echo '<div class="contex">';
orderdet($mysqli,$q,$minbill,"all",0);
echo '</div>';
if($numorders > 1) { echo '<BR><div class="loadmore">טען עוד הזמנות..</div>'; }
}
}else{
if($do == follow) { 
$id = $_GET['id'];
echo '<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">מעקב אחר הזמנה</span></div>
<BR>';
$q4 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='5' AND `status`!='0' AND `id`='$id'");
$qi = $q4->fetch_assoc(); $thisbuyer = $qi['buyer'];
if(substr_count($thisbuyer,'g') == 0) {
$q4 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`!='5' AND `status`!='0' AND `id`='$id'");
}
if($q4->num_rows == 0) {
echo <<<Print
<div class="notice">לא ניתן לעקוב אחר הזמנה זו.</div>
Print;
}else{
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var id = $id, status = 0;
setInterval(function() {
$.ajax({type:"POST",url:"$url/action.php?do=getstatus",data:({id:id}),success:function(data) {
if(data) { data = data-1; $(".loading").hide();
$(".prog").eq(data-1).fadeOut(500,function() { $(".prog").eq(data).fadeIn(500); status = 25*data; $("progress").val(status); });
} }
});
},1000);
});
</script>

<div class="status">
<div style="font-size:18pt;" class="loading">טוען סטטוס..</div>
<div class="prog">
<img src="$url/images/status1.png"><BR>קבלת ההזמנה במערכת
</div>
<div class="prog">
<img src="$url/images/status2.png"><BR>הכנה
</div>
<div class="prog">
<img src="$url/images/status3.png"><BR>אפייה
</div>
<div class="prog">
<img src="$url/images/status4.png"><BR>משלוח
</div>
<div class="prog">
<img src="$url/images/status5.png"><BR>הזמנה הושלמה
</div>
</div>
<div style="clear:both;"></div><BR>
התקדמות הזמנה:
<progress value="0" max="100"></progress>
<BR><BR>
<div class="notice">סטטוס ההזמנה משתנה באופן אוטומטי, אין צורך לרענן את הדף.</div>
<BR>
Print;
if(substr_count($thisbuyer,'g') == 0) {
$q = "SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`!='5' AND `status`!='0' AND `id`='$id'";
}else{
$q = "SELECT * FROM `zz_order` WHERE `status`!='5' AND `status`!='0' AND `id`='$id'";
}
orderdet($mysqli,$q,$minbill,"follow",0);
}

}else{
if($do == page) { 
$id = $mysqli->real_escape_string($_GET['id']);
$q3 = $mysqli->query("SELECT * FROM `zz_pages` WHERE `id`='$id' AND `content`!=''");
if($q3->num_rows == 0) {
echo '<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">דף לא נמצא</span></div>
<div class="notice">הדף המבוקש לא נמצא.</div>';
}else{
$qu = $q3->fetch_assoc(); $title = $qu['title']; $content = $qu['content'];
echo <<<Print
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">$title</span></div>

<div style="padding:0 7px 7px;">
$content</div>
Print;
}
}else{
if($do == locations) {
echo <<<Print
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">סניפים</span></div>
<div style="white-space:pre-line;"></div>
Print;
$q3 = $mysqli->query("SELECT * FROM `zz_locations` ORDER BY `city`");
if($q3->num_rows == 0) {
echo <<<Print
<div class="notice">אין סניפים.</div>
Print;
}else{
while($dd = $q3->fetch_assoc()) {$city = $dd['city']; $adress = $dd['adress']; $phone = $dd['phone'];
$hedays = array("א'","ב'","ג'","ד'","ה'","ו'","ש'");
echo '<div style="float:right; margin:0 10px;">
<div style="color:#222; padding:12px 10px 2px; font-size:24pt; font-family:menu;">'.$city.'</div>
<div style="padding:0px 7px; font-size:11pt; color:#222;">'.$adress.'<BR>';
$today = date('N',time())+1;
for($i = 1; $i <= 7; $i++) {
if($dd['day'.$i] != $dd['day'.($i+1)]) {
if($count > 0) { if(($i-$count-1 < $today && $i-1 >= $today) || $i == $today) { echo '<span style="color:green;">'; } echo 'יום '.$hedays[$i-$count-1].'-'.$hedays[$i-1].': '; }else{
if($i == $today) { echo '<span style="color:green;">'; } echo 'יום '.$hedays[$i-1].': '; }
if($dd['day'.$i] == 'close') { echo '<b>סגור</b><div style="clear:both;"></div>'; }else{ 
echo '<div style="direction:ltr;display:inline;">'.$dd['day'.$i].'</div><div style="clear:both;"></div>'; }
if($i == $today || ($i-$count-1 < $today && $i-1 >= $today)) { echo '</span>'; } 
$count = 0;
}else{
$count++;
}

}
echo '
<div style="text-align:center;margin-top:5px;color: #B20002;">'.$phone.'</div></div>
</div>';
} }
$now = time();
$q4 = $mysqli->query("SELECT * FROM `zz_closed` WHERE `until`>'$now' ORDER BY `loc`");
if($q4->num_rows > 0) {
echo <<<Print
<div style="clear:both;"></div><BR>
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">תאריכים בהם הסניפים יהיו סגורים</span></div>
<div style="white-space:pre-line;"></div>
Print;

$lastloc = '';
while($dd = $q4->fetch_assoc()) { $date = $dd['date']; $until = $dd['until']; $reason = $dd['reason']; $loc = $dd['loc'];
$date = date('j.n.y', $date).'<div style="font-size:8pt;">'.date('H:i', $date).'</div>';
$until = date('j.n.y', $until).'<div style="font-size:8pt;">'.date('H:i', $until).'</div>';
if($lastloc != $loc) {
if($loc == 0) { $locs = 'כל הסניפים'; $adress = ''; }else{ 
$q43 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$loc'");
$pg = $q43->fetch_assoc(); $city = $pg['city']; $adress = $pg['adress']; $locs = $city; 
}
if($lastloc != '') { echo '</div></div>'; }
echo '<div style="width:calc(50% - 20px); float:right; margin:0 10px;">
<div style="color:#222; padding:12px 10px 2px; font-size:24pt; font-family:menu;">'.$locs.'</div>
<div style="padding:0px 7px; font-size:11pt; color:#222;">'.$adress;
}
echo <<<Print
<div style="padding:7px; font-size:10pt; text-align:center; vertical-align:middle; line-height:15px;">
מ <div style="display:inline-block; vertical-align:middle; line-height:15px; margin:0 5px;">$date</div> עד <div style="display:inline-block;vertical-align:middle; line-height:15px;margin:0 5px;">$until</div>
<div style="clear:both;"></div>
<b>סיבה:</b> $reason.</div><hr>
Print;
$lastloc = $loc;
} }
echo '</div></div>';

}else{
if($do == order) {
$page = $_GET['page'];
if(!($canlogin == 1 && $noreg != 1) && $page == 'login') {
echo <<<Print
<div class="loadbox"><div class="catload" style="margin:30% auto 0 auto;"><img src="$url/images/load.gif"></div></div>
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:25px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">התחברות</span></div><BR>
<script type="text/javascript">
$(document).ready(function() {

function timer() {
$(".time").stop();
var t = $(".time").text().split(":"), m = t[0], s = t[1];
m = m.replace(/^0+/, '')
if(s > 0) { s--; if(m==0&&s==0) { clearInterval(stop); $("#login").delay(2000).trigger("click"); m=0;s=0; } }else{
if(s == 0) { m--; s = 59; } }
if(s < 10) { s = "0"+s; } if(m < 10) { if(m < 1 && s >= 1 && s < 59) { m = "00"+m; }else{ m = "0"+m; } }
$(".time").stop().text(m+":"+s).animate({"font-size":"14pt"},200).delay(200).animate({"font-size":"12pt"},200);
}
var stop;
$("#guest").click(function(eventObject) {
eventObject.preventDefault();
$("#guest").attr({'disabled':true,'value':'טוען..'});
var phone = $("#gphone").val(),loc = $("#glocation").val();
$.ajax({type:"POST",url:"$url/action.php?do=guest",data:({phone:phone,loc:loc}),success:function(data) {
$("#guest").attr({'disabled':false,'value':'המשך'});
if(data == 'e1') {
$("#gerror").html("יש למלות את שדה הטלפון באופן תקין.").slideDown(500);
}else{
if(data == 'e2') {
$("#gerror").html("סניף לא תקין, בחר אחר.").slideDown(500);
}else{
if(data == 'e3') {
$("#gerror").html("הסניף שנבחר סגור כרגע.  <BR> <a href='$url/locations' target='_blank' style='color:blue; text-decoration:underline;'>צפה בפעילות הסניפים</a>").slideDown(500);
}else{
window.location = "$url/pay";
} } } }
});
});
$("#login").click(function(eventObject) {
eventObject.preventDefault();
var phone = $("#phone").val(), location = $("#location").val(), q = $("#q").val(), a = $("#a").val(), secure = 0, code = $("#code").val(), birthday = $("#birthday").val();
if($(".secure").css("display") != 'none') { secure = 1;}
$("#login").attr({'disabled':true,'value':'טוען..'});
$.ajax({type:"POST",url:"$url/action.php?do=login",data:({phone:phone,location:location,q:q,a:a,secure:secure,code:code,birthday:birthday}),success:function(data) {
clearInterval(stop);
$("#login").attr({'disabled':false,'value':'המשך'});
if(data == 'e1') {
$("#error").html("סניף לא תקין, בחר אחר.").slideDown(500);
}else{
if(data == 'e2') {
$("#error").html("הסניף שנבחר סגור כרגע.  <BR> <a href='$url/locations' target='_blank' style='color:blue; text-decoration:underline;'>צפה בפעילות הסניפים</a>").slideDown(500);
}else{
if(data == 'e3') {
$("#error").html("יש למלות את שדה הטלפון באופן תקין.").slideDown(500);
}else{
if(data == 'e6') {
$("#error").html("תאריך הלידה שהוקש לא תקין.").fadeIn(200);
}else{
if(data == 'e4') {
$("#error").html("צור לחשבון שלך שאלת ותשובת אבטחה.<BR>כמו כן צור גם קוד אישי אשר יעזור לך לאמת הזמנות. את הקוד האישי לא יהיה ניתן לשנות.<BR>על הקוד האישי להיות בין 6-18 תווים.").fadeIn(200);
$(".secure,.securecode").fadeIn(300);
}else{
if(data.indexOf("e5") != '-1') { data = data.slice(2);
$("#error").html("עברת את הגבלת ניסיונות ההתחברות.<BR>"+data).slideDown(500);
stop = setInterval(timer,1000);
$(".secure").fadeIn(300);
}else{
if(data != 'done') { data = data.split(",");
$("#error").html("ענה על שאלת האבטחה שבחרת.<BR>"+data[1]).slideDown(500);
$(".secure").fadeIn(300);
$(".securecode").hide();
$("#q").val(data[0]);

}else{
window.location = "$url/pay";
} } } } } } }
if(data.indexOf("e4") == '-1') {
$("#a").val("").focus();
}
}
});
});
$.datepicker.regional['he'] = { closeText: 'סגור', prevText: '<הקודם', nextText: 'הבא>', currentText: 'היום', monthNames: ['ינואר','פברואר','מרץ','אפריל','מאי','יוני', 'יולי','אוגוסט','ספטמבר','אוקטובר','נובמבר','דצמבר'], monthNamesShort: ['1','2','3','4','5','6', '7','8','9','10','11','12'], dayNames: ['ראשון','שני','שלישי','רביעי','חמישי','שישי','שבת'], dayNamesShort: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], dayNamesMin: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], weekHeader: 'Wk', dateFormat: 'dd/mm/yy', firstDay: 0, isRTL: true, showMonthAfterYear: false, yearSuffix: ''};
$.datepicker.setDefaults($.datepicker.regional['he']);
$("#birthday").datepicker({yearRange: "1901:$nowyear", isRTL: true, dateFormat: "yy-mm-dd",
changeMonth: true,changeYear: true
});
});
</script>
<div class="guest" style="width:calc(100% - 20px); padding:10px; float:none;">
<div class="subtitle" style="font-size:24pt;font-weight:bold;">הזמן ללא הרשמה</div>
<div style="font-size:10pt;">תקף להזמנה זו בלבד.</div>
<form action='' method="POST">
<table>
<tr><td>מספר טלפון המזמין:</td><td><input type="text" id="gphone"></td></tr>
<tr><td>סניף הזמנה:</td><td><select id="glocation">
Print;
$q6 = $mysqli->query("SELECT * FROM `zz_locations` ORDER BY `city`");
while($ff = $q6->fetch_assoc()) { $id = $ff['id']; $city = $ff['city']; $adress = $ff['adress'];
echo '<option value="'.$id.'">'.$adress.', '.$city.'</option>';
}

echo <<<Print
</select></td></tr>
</table>
<input type="submit" value="המשך" id="guest"><div id="gerror" class="er"></div>
</form>
</div>
<div class="registred" style="width:calc(100% - 20px);padding:10px; float:none;">
<form action='' method="POST">
<div class="subtitle" style="font-size:24pt;font-weight:bold;">משתמש רשום/חדש</div>
<div style="font-size:10pt;">פרטי ההזמנה ישמרו במערכת.</div>
<table>
<tr><td>מספר טלפון המזמין:</td><td><input type="text" id="phone"></td></tr>
<tr><td>סניף הזמנה:</td><td><select id="location">
Print;
$q6 = $mysqli->query("SELECT * FROM `zz_locations` ORDER BY `city`");
while($ff = $q6->fetch_assoc()) { $id = $ff['id']; $city = $ff['city']; $adress = $ff['adress'];
echo '<option value="'.$id.'">'.$adress.', '.$city.'</option>';
}
if($allowbd) { $birthday = '<tr><td>תאריך יום הולדת:</td><td><input type="text" id="birthday">
<div style="font-size:8pt;">במידה והינך מעוניין לקבל הטבות ביום ההולדת. <BR>אם אתה משתמש רשום, השאר שדה זה ריק.</div></td></tr>'; }
echo <<<Print
</select></td></tr>
$birthday
<tr class="securecode" style="display:none;"><td>קוד אישי:</td><td><input type="password" id="code"></td></tr>
<tr class="secure" style="display:none;"><td>שאלת אבטחה:</td><td><select id="q" data-role="none">
<option value="מה שם החיית מחמד הראשונה שלך?">מה שם החיית מחמד הראשונה שלך?</option>
<option value="מה שם המשפחה של אמך?">מה שם המשפחה של אמך?</option>
<option value="באיזה בית ספר למדת?">באיזה בית ספר למדת?</option>
<option value="באיזה עיר נולדת?">באיזה עיר נולדת?</option>
<option value="מהו שם בית החולים בו נולדת?">מהו שם בית החולים בו נולדת?</option>
</select></td></tr>
<tr class="secure" style="display:none;"><td>תשובה לשאלה:</td><td><input type="password" id="a"></td></tr>
</table>

<input type="submit" value="המשך" id="login">
<div id="error"></div>
</form>
</div>
Print;
}else{
echo '<div class="loadbox"><div class="catload" style="margin:30% auto 0 auto;"><img src="'.$url.'/images/load.gif"></div></div>
<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">הזמנה</span></div><BR>';
echo '<div class="ordercontent">';
$deal = $mysqli->real_escape_string($_GET['deal']);
$q49 = $mysqli->query("SELECT * FROM `zz_deals` where `id`='$deal' AND `deleted`='0'");
if($deal != Null) {
$query = $q49->fetch_assoc(); $bd = $query['bd']; $products = explode(",",$query['products']); $name = $query['name']; $price = $query['price'];

if($q49->num_rows == 0 || ($bd && !$cbd)) {
echo '<div class="catcontent"><div class="notice">ההטבה לא נמצאה.</div></div>
</div>';
}else{
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var needtimes = $(".nt").val(), dealid = $deal, next = true;
$(".additem").buttonset();

$(".done").bind('tap',function() {
var selected = "", times = 0;
$(".done").attr({'disabled':true,'value':'טוען..'});
$(".ui-state-active").each(function() {
selected += $(this).attr("title").slice(4)+".";
times++;
});
if(times != needtimes) { next = confirm("לא מימשת את כל מוצרי המבצע, להמשיך?"); }
if(next) {
selected = selected.slice(0,-1);
selected = "{"+dealid+":"+selected+"}";
$.ajax({type:"POST",url:"$url/action.php?do=additem",data:({itemid:selected}),success:function(data) {
window.location = '$url/order';
$(".done").attr({'disabled':false,'value':'סיים בחירה'});
}
});
}else{ $(".done").attr({'disabled':false,'value':'סיים בחירה'}); }
});
$(".chooseitem").change(function() {
var name;
if($(this).is(":checked")) {
name = $(this).attr("name");
$(".chooseitem[name="+name+"]").each(function() {
if($(this).parent().parent().parent().css("opacity") == 1) {
$(".chooseitem[name="+name+"]").parent().parent().parent().animate({"opacity":"0.7"},200);
}
});
$(this).parent().parent().parent().animate({"opacity":"1"},200);
}
});

});
</script>
<div class="catcontent">
Print;

echo '<input type="hidden" value="'.sizeof($products).'" class="nt">';
$inputname = 0; $iname = 0;
for($i = 0; $i < sizeof($products); $i++) {
if(strpos($products[$i],'{') !== false) {
$text = explode("{",$products[$i]); $newtext = substr($text[1],0,-1); $protype = $newtext;
$q50 = $mysqli->query("SELECT * FROM `zz_products` where `type`='$newtext' AND `show`='1'");
}else{
$newtext = $products[$i];
$q500 = $mysqli->query("SELECT * FROM `zz_products` where `id`='$newtext' AND `show`='1'");
$qq = $q500->fetch_assoc(); $protype = $qq['type'];
$q50 = $mysqli->query("SELECT * FROM `zz_products` where `id`='$newtext' AND `show`='1'");
}
$q51 = $mysqli->query("SELECT * FROM `zz_cat` WHERE `id`='$protype'");
$qux = $q51->fetch_assoc(); $catname = $qux['name']; $nt = $q51->num_rows; $double = substr_count($products[$i], "+")*2;
if(substr_count($products[$i], '+') > 0) { $times = '<div style="font-size:10pt;">(למוצר זה מגיע גם '.substr_count($products[$i], "+").' תוספות ('.$double.' סה"כ לשני חצאים), הוסף אותן בכרטיסית ההזמנה)</div>'; }
echo '<div class="title" style="color:#222; font-size:14pt; width:100%;"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:10px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">'.$catname.$times.'</span></div>';
while($ks = $q50->fetch_assoc()) { 
$id = $ks['id']; $name = $ks['name']; $price = $ks['price']; $img = $ks['img']; $des = $ks['des'];
echo <<<Print

<div class="offp" title="$des" style="border:1px solid #EEE; width:calc(50% - 30px); margin:5px; float:right; padding:5px; opacity:0.7;">
<div style="background:url($url/images/uploads/$img) center; width:100%; height:160px;"></div>
<div style="font-size:18pt;color:#222;font-family:menu;">$name</div> 
<div style="font-size:10pt; margin:0px 7px 7px; color:gray;">$des</div>
<div style="color:#C00002; font-size:16pt; float:right;">$price ₪</div>
<div style="float:left"><span class="additem" style="color: #000;padding: 5px 0px 7px;"><input type="radio" name="a$iname" style="text-decoration:none;" class="chooseitem" id="a$inputname"><label for="a$inputname" title="item$id">+ בחר מוצר</label></span></div>
<div style="clear:both;"></div>
</div>
Print;
$inputname++;
} $iname++; $times = '';
echo '<div style="clear:both;"></div><BR><BR><BR>';
 }


echo '</div>
<input type="submit" value="סיים בחירה" class="button done" style="width:calc(100% - 10px); text-shadow:none; margin:5px;"><div style="clear:both;"></div><BR><BR>
</div>
';
}
}else{
if($orderloc == 0 && $canlogin == 1 && $noreg != 1) {
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$("#go").click(function(eventObject) {
eventObject.preventDefault();
var loc = $("#location").val();
$("#go").attr({'disabled':true,'value':'טוען..'});
$.ajax({type:"POST",url:"$url/action.php?do=checkloc",data:({loc:loc}),success:function(data) {
$("#go").attr({'disabled':false,'value':'המשך'});
if(data == 'e1') {
$("#error").html("הסניף שנבחר סגור.<BR> <a href='$url/locations' target='_blank' style='color:blue; text-decoration:underline;'>צפה בפעילות הסניפים</a>").slideDown(500);
}else{
window.location.reload();
} }
});
});
});
</script>
<b>בחר סניף להזמנה:</b><BR>
<form action='' method="POST">
סניף: <select id="location">
Print;
$q6 = $mysqli->query("SELECT * FROM `zz_locations` ORDER BY `city`");
while($ff = $q6->fetch_assoc()) { $id = $ff['id']; $city = $ff['city']; $adress = $ff['adress'];
echo '<option value="'.$id.'">'.$adress.', '.$city.'</option>';
}
echo <<<Print
</select><BR><BR><input type="submit" value="המשך" id="go"></form><div id="error"></div></div>
Print;
}else{
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$(".catlink").bind('tap',function() {
var thisid = $(this).attr("id").slice(3);
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/action.php?do=loadcat",data:({cat:thisid}),success:function(data) {
$(".catcontent").fadeOut(100,function() { $(this).html(data).show("drop",{direction:"up"},400); $(".offp").tooltip({show: {effect: "fade", duration: 200},hide: {effect: "fade", duration: 200} }); });
$(".loadbox").fadeOut(150);
}
});
});
});
</script>
<div style="clear:both; position:relative; overflow:hidden;">
<div class="catcontent">
Print;
$cat = 0;
$q2 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`>'0' AND `show`='1' ORDER BY `type`");
if($q2->num_rows == 0) {
echo <<<Print
<div class="notice">אין מוצרים.</div>
Print;
}else{
while($jn = $q2->fetch_assoc()) { $id = $jn['id']; $name = $jn['name']; $price = $jn['price']; $img = $jn['img']; $des = $jn['des']; $type = $jn['type'];
if($cat != $type) { 
$q3 = $mysqli->query("SELECT * FROM `zz_cat` WHERE `id`='$type'"); 
$qo = $q3->fetch_assoc(); $cname = $qo['name'];
echo '<div class="title" style="color:#222; font-size:14pt;"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:10px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">'.$cname.'</span></div>';
}
echo <<<Print
<div class="offp" title="$des" style="border:1px solid #EEE; width:calc(50% - 30px); margin:5px; float:right; padding:5px;">
<div style="background:url($url/images/uploads/$img) center; width:100%; height:160px;"></div>
<div style="font-size:18pt;color:#222;font-family:menu;">$name</div> 
<div style="font-size:10pt; margin:0px 7px 7px; color:gray;">$des</div>
<div style="color:#C00002; font-size:16pt; float:right;">$price ₪</div>
<div style="float:left"><a href="javascript:void(0);" class="additem" id="item$id">+ הוסף להזמנה</a></div>
<div style="clear:both;"></div>
</div>
Print;
$cat = $type;
 } }

echo <<<Print
</div>
</div>
</div><div style="clear:both;"></div>

Print;

}
$edit = $_GET['edit'];

echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var edit = "$edit";
$(".delete,.addonbox,.clear").show();
$(".ibutton").hide();
if(edit == "true") { $(".car,.darkbg").show(); }
$("body").on("tap",".additem",function() {
var itemid = $(this).attr("id").slice(4);
$(".loadbox").show();
triggerb = false;
$.ajax({type:"POST",url:"$url/action.php?do=additem",data:({itemid:itemid}),success:function(data) {
$(".itemsc").text(parseInt($(".itemsc").text())+1);
$(".car").load("$url/action.php?do=cart",function() {
$(".openb").effect("highlight",800);
$(".loadbox").fadeOut(100);
triggerb = true;
});
}
});
});

$("body").on("tap",".clear",function() {
$(".loadbox").show();
$.ajax({type:"POST",url:"$url/action.php?do=clear",success:function() {
window.location.reload();
$(".loadbox").fadeOut(100);
}
});
});

$("body").on("tap",".delete",function() {
var item = $(this).attr("id").slice(2);
$(".loadbox").show();
$.ajax({type:"POST",url:"$url/action.php?do=deleteitem",data:({item:item}),success:function(data) {
$(".itemsc").text(parseInt($(".itemsc").text())-1);
$(".car").load("$url/action.php?do=cart");
$(".loadbox").fadeOut(100);
}
});
});

var addons0='',addons1='',addons2='',topro;
$("body").on("tap",".addonbox",function() {
topro = $(this).attr("id").slice(3);
$(".loadbox").show();
$.ajax({type:"POST",url:"$url/action.php?do=pizzaplace",data:({topro:topro}),success:function(data) {
$(".loadbox").fadeOut(200);
$(".pizzaplace").html(data);
$(".dragadd").tooltip({show: {effect: "fade", duration: 200},hide: {effect: "fade", duration: 200} });
$(".blackbg").fadeIn(350);
$("html, body").animate({scrollTop: "0"},300);
$(".adbox").show("drop",{direction:"up"},350);
for(var i = 0; i <= 2; i++) {
$(".half"+i).droppable({accept:'.addon'+i,drop: function(event, ui) { 
var qid = ui.draggable.html(), times = 0, dragid = ui.draggable.attr("id").slice(2), nowat;
if(ui.draggable.attr("class").split(' ')[0] == 'addon1') { nowat = 1; }else{
if(ui.draggable.attr("class").split(' ')[0] == 'addon2') { nowat = 2; }else{ nowat = 0; } }
$(".addon"+nowat+"#ad"+dragid).tooltip('disable');
ui.draggable.data('dropped', true);
$(".listaddon"+nowat).each(function() {
if($(this).html() == qid) { times++; }
});
$(".empty"+nowat).hide();
if(times == 0) { $(".addons"+nowat).append('<div class="listaddon'+nowat+' dragadd" id="la'+dragid+'">'+qid+'</div>'); 
if(eval("addons"+nowat) == '') { eval("addons"+nowat+" = dragid"); }else{ eval("addons"+nowat+" += ','+dragid"); } }
}
});
}

if($(".half0")[0]) { var i = 2,p = 0; }else{ var i = 1,p = 1; }
for(; i <= 2; i++) {
$(".half"+p+" .on").each(function() {
var addid = $(this).attr('id').slice(2);
eval("addons"+p+" += addid+','");
$(".empty"+p).hide();
$(".addons"+p).append('<div id="la'+addid+'" class="listaddon'+p+'">'+$(this).html()+'</div>');
});
eval("addons"+p+" = addons"+p+".slice(0,-1)");
p++;
}

$(".addon0,.addon1,.addon2").draggable({revert : function(event, ui) {
if($(this).attr("class").split(' ')[1] == 'on') {
var newid = $(this).attr("class").split(' ')[0].slice(5);
$(".new"+newid).append($(this)); $(this).removeClass("on"); return !event; 
}else{
$(this).data("uiDraggable").originalPosition = {top : 0,left : 0}; return !event; 
} },
start: function(event, ui) { ui.helper.data('dropped', false); },
stop: function(event, ui) {
if(!ui.helper.data('dropped')) {
var moved = $(this).html(), delid = '', newarr, nowat;
if($(this).attr("class").split(' ')[0] == 'addon1') { nowat = 1; }else{
if($(this).attr("class").split(' ')[0] == 'addon2') { nowat = 2; }else{ nowat = 0; } }
$(".listaddon"+nowat).each(function() {
if($(this).html() == moved) { delid = $(this).attr("id").slice(2); $(this).remove(); }
});

$(".addon"+nowat+"#ad"+$(this).attr("id").slice(2)).tooltip('enable').tooltip( "option", "content", "גרור תוספת לפיצה" );

newarr = eval("addons"+nowat).split(",");
var index = newarr.indexOf(delid);
newarr.splice(index, 1);
eval("addons"+nowat+"= newarr.toString()");
if($(".listaddon0").length == 0) { $(".empty0").show();}
if($(".listaddon1").length == 0) { $(".empty1").show();}
if($(".listaddon2").length == 0) { $(".empty2").show();}
} }
});
}
});

});


$("body").on("tap",".accept",function() {
if($(".half0")[0]) {var p = 0, i = 2;}else{var p = 1,i = 1;}
for(; i <= 2; i++) {
var halfo = eval("addons"+p);
$(".loadbox").show();
$.ajax({type:"POST",url:"$url/action.php?do=addons",data:({adid:halfo,pro:topro,half:p}),success:function(data) {
if(halfo == addons2 || halfo == addons0) {
$(".blackbg").fadeOut(200);
$(".adbox").hide("drop",{direction:"up"},350);
$(".openb").effect("highlight",800);
$(".car").load("$url/action.php?do=cart");
$(".loadbox").fadeOut(100);
topro = ''; addons0 = ''; addons1=''; addons2='';
} }
});
p++;
}
});

$(".cancel").bind('tap',function() {
$(".blackbg").fadeOut(200);
$(".adbox").hide("drop",{direction:"up"},350);
topro = ''; addons1=''; addons2='';
});
$(".help").tooltip({show: {effect: "fade", duration: 200},hide: {effect: "fade", duration: 200} });

});
</script>
<div style="background:black; position:fixed; opacity:0.7; height:100%; width:100%; top:0; right:0; z-index:20; padding:10px; display:none;" class="blackbg"></div>
<div style="background:white; border:1px solid gray; border-radius:5px; width:calc(100% - 12px); position:absolute; top:-120px; padding:10px 5px; right:0; left:0; z-index:20; margin:auto; display:none;" class="adbox">
<div class="title" style="width:100%;">בחר תוספות <div class="help" style="text-decoration:underline; font-size:10pt; display:inline; cursor:pointer;" title='להוספת תוספות לפיצה, גרור תוספת מתפריט התוספות לחצי הפיצה הרצוי. רק לאחר שהתוספת תופיע ב"תוספות בפיצה", התוספת אכן נוספה.  כדי להסיר תוספת, יש לגרור את התוספת מהפיצה לכל חלק אחר.'>איך להוסיף תוספות?</div></div>

<div class="pizzaplace"></div>

<div style="clear:both;"></div><BR><div style="width:100%; margin:auto;">
<div class="accept" style="cursor:pointer; width:calc(100% - 30px);">סיים בחירת תוספות</div>
<div class="cancel" style="cursor:pointer; width:calc(100% - 30px); margin-top:5px;">בטל הוספת תוספות</div></div><div style="clear:both;"></div></div>
Print;




} } }else{

echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var current = 0, i = 1, slideshow;
function deal(index) {
if(index > current) {
$(".offb").eq(current).hide("slide",{direction:"right",easing: "easeInOutCirc"},600);
if(index > $(".offb").length-1) {
$(".offb").eq(0).show("slide",{direction:"left",easing: "easeInOutCirc"},600);
i = 0; index = 0;
}else{
$(".offb").eq(index).show("slide",{direction:"left",easing: "easeInOutCirc"},600);
}
}else{
if(index < current) {
$(".offb").eq(current).hide("slide",{direction:"left",easing: "easeInOutCirc"},600);
$(".offb").eq(index).show("slide",{direction:"right",easing: "easeInOutCirc"},600);
} }
$(".loader").animate({"width":"0px"},1);
$(".circle").removeClass("clicked").eq(index).addClass("clicked");
current = index; 
}
function inter() {
deal(i,"function"); i++;
if(i > $(".offb").length-1) { i = 0; }
}

if($(".offb").length > 1) {
slideshow = setInterval(inter,6000);
}

$(".offb").hover(function() {
clearInterval(slideshow);
},function () {
if($(".offb").length > 1) {
slideshow = setInterval(inter,6000);
}
});

$(".circle").bind('tap',function() {
var index = $(this).index();
deal(index,"click"); i = index+1;
});
$(".next").bind('tap',function() {
deal(i,"function"); i++;
});
$(".pre").bind('tap',function() {
i-=2;
if(i < 0) { i = $(".offb").length-1; }
deal(i,"function"); i++;
});


});
</script>
<div style="position:absolute; width:calc(100% - 20px); margin:auto; color:white; text-shadow:0 1px black;">
<div style="position:absolute; z-index:5; margin:130px 10px 0 0; font-size:40pt; cursor:pointer;" class="pre">&lsaquo;</div>
<div style="position:absolute; z-index:5; margin:130px 20px 0 0; font-size:40pt; left:0; cursor:pointer;" class="next">&rsaquo;</div>
</div>
<div class="boxofhappy" style="position:relative; margin:auto;">
Print;

$q2 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `deleted`='0' AND `bd`='0' ORDER BY `id` DESC");
if($q2->num_rows == 0) {
echo <<<Print
<div class="offb" style="width:calc(100% - 20px); margin:auto; position:absolute;">
<div class="prod" style="background:#F55D2D; background-size: cover; width:calc(100% - 20px); height:300px; text-align:center; font-size:30pt; color:white; padding-top:50px;">
אין מבצעים
</div>
</div>
Print;
}else{
while($jn = $q2->fetch_assoc()) { $id = $jn['id']; $name = $jn['name']; $price = $jn['price']; $img = $jn['img'];
echo <<<Print
<div class="offb" style="width:100%; margin:auto;  position:absolute;">
<div class="prod" style="background:url(/images/uploads/$img) no-repeat center; background-size: cover; width:100%; height:350px;"><div class="infodeal" style="width:calc(100% - 20px); font-size:14pt;">
$name ב $price ₪
<a href="$url/deal/$id" style="text-decoration:none;">
<div class="button" style="cursor:pointer; text-shadow:none;">הזמן עכשיו!</div></a></div>
</div>
</div>
Print;
} }

$nrows = $q2->num_rows; $width = $nrows*25;
echo '</div><div style="width:100%; margin:auto; text-align:center; padding-top:350px;"><div style="height:2px; border-top:2px solid #C04839; width:0; display:none;" class="loader"></div>
<div style="width:'.$width.'px; padding-top:10px; margin:auto;">';
for($i = 0; $i < $nrows; $i++) {
if($i == 0) {
echo '<div class="circle clicked"></div>';
}else{
echo '<div class="circle"></div>';
} } 
echo <<<Print
<div style="clear:both;"></div>
</div>
<BR>
Print;

$now = time();
$q4 = $mysqli->query("SELECT * FROM `zz_closed` WHERE `until`>'$now' ORDER BY `loc`");
if($q4->num_rows > 0) {
echo '<div class="title"><div style="border-bottom:1px solid #DDDDDD; height:5px; width:100%; margin-top:20px; position:absolute; z-index:1;"></div><span style="background:white; position:relative; z-index:3; padding:0 10px;">תאריכים בהם הסניפים יהיו סגורים</span></div>';
$lastloc = '';
while($dd = $q4->fetch_assoc()) { $date = $dd['date']; $until = $dd['until']; $reason = $dd['reason']; $loc = $dd['loc'];
$date = date('j.n.y', $date).'<div style="font-size:8pt;">'.date('H:i', $date).'</div>';
$until = date('j.n.y', $until).'<div style="font-size:8pt;">'.date('H:i', $until).'</div>';
if($lastloc != $loc) {
if($loc == 0) { $locs = 'כל הסניפים'; $adress = ''; }else{ 
$q43 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$loc'");
$pg = $q43->fetch_assoc(); $city = $pg['city']; $adress = $pg['adress']; $locs = $city; 
}
if($lastloc != '') { echo '</div></div>'; }
echo '<div style="width:calc(50% - 20px); float:right; margin:0 10px;">
<div style="color:#222; padding:12px 10px 2px; font-size:24pt; font-family:menu;">'.$locs.'</div>
<div style="padding:0px 7px; font-size:11pt; color:#222;">'.$adress;
}

echo <<<Print
<div style="padding:7px; font-size:10pt; text-align:center; vertical-align:middle; line-height:15px;">
מ <div style="display:inline-block; vertical-align:middle; line-height:15px; margin:0 5px;">$date</div> עד <div style="display:inline-block;vertical-align:middle; line-height:15px;margin:0 5px;">$until</div>
<div style="clear:both;"></div>
<b>סיבה:</b> $reason.</div><hr>
Print;
$lastloc = $loc;
} echo '</div></div>'; }
if($orderphone == '') { $orderphone = 'אין.'; }
echo '</div></div><div style="clear:both;"></div><BR><BR><BR><div style="color:#2A2928; width:(100% - 6px); margin:auto; text-align:center; font-size:25pt; padding:3px; font-family:title;">
טלפון להזמנות: <BR> '.$orderphone.'</div>';




} } } } } } } } }
echo '<div style="clear:both;"></div></div>';
if($do != Null) { echo '</div>'; }
echo <<<Print
<div class="footer"><div style="width:100%; margin:auto;">
<div style="float:right;">
<b>תפריט מותאם אישית:</b><BR>
<ul class="bottomenu">
Print;
$q2 = $mysqli->query("SELECT * FROM `zz_pages` WHERE `showb`='1'");
while($nf = $q2->fetch_assoc()) { $id = $nf['id']; $title = $nf['title']; $link = $nf['link'];
if($link == Null) {
echo '<li><a href="'.$url.'/pages/'.$id.'" style="font-size:8pt;">'.$title.'</a></li>';
}else{
echo '<li><a href="'.$link.'" target="_blank" style="font-size:8pt;">'.$title.'</a></li>';
} }
echo <<<Print
</ul>
</div>
<div style="float:left; font-size:6pt; width:140px; text-align:center;">
<img src="$url/images/icon.png" style="width:50px; height:auto;"><BR>
&copy; כל הזכויות שמורות למערכת <a href="http://pizzer.net" target="_blank">Pizzer</a>.
</div>
<div style="clear:both;"></div></div>
</div>
</div>

<ul class="menu">
<a href="$url"><li>ראשי</li></a>
<a href="$url/order"><li>הזמנה</li></a>
<a href="$url/locations"><li>סניפים</li></a>
<a href="$url/account"><li>החשבון שלי</li></a>
Print;
$q2 = $mysqli->query("SELECT * FROM `zz_pages` WHERE `showm`='1'");
if($q2->num_rows > 0) {
echo '<li class="cmenu" id="cmenu"><a href="javascript:void(0);" style="color:white;" id="linkmenu">עוד..</a><ul class="custom">';
while($nf = $q2->fetch_assoc()) { $id = $nf['id']; $title = $nf['title']; $plink = $nf['link'];
if($plink == Null) {
echo '<a href="'.$url.'/pages/'.$id.'"><li>'.$title.'</li></a>';
}else{
echo '<a href="'.$plink.'" target="_blank"><li>'.$title.'</li></a>';
} }
echo '</ul></li>';
}

echo <<<Print
<a href="$url/contact"><li>צור קשר</li></a>
</ul>
Print;
?>