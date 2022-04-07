<?php
ob_start();
require_once('config.php');
if(isset($_COOKIE['admin'])) {
$ch = $mysqli->query("SELECT * FROM `zz_settings`");
$pd = $ch->fetch_assoc(); $cc = md5($pd['user'].','.$pd['pass'].$pd['salt']);
$cuser = $_COOKIE['admin'];
if($cc == $cuser) {
$canlogin = 1; $adminlog = 1;
} }
if($adminlog != 1) { $canlogin = 0; setcookie("admin","",time()-86400); }
echo <<<Print
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="rtl">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<link href="$url/images/icon.png" rel="icon" type="image/x-icon" />
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link type="text/css" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="$url/js/ease.js"></script>
<script src="$url/js/select.js"></script>
<script src="$url/js/script.js"></script>
<script src="$url/js/jquery.wysibb.js"></script>

<link rel="stylesheet" href="$url/js/wbbtheme.css" /> 
<script type="text/javascript"> 
$(document).ready(function() { 
$("textarea").wysibb(); 
});
</script>
<link type="text/css" href="$url/admin.css" rel="stylesheet" />
<title>pizzer - פאנל ניהול</title>
<style>
body {background:url($url/images/bg.png) fixed no-repeat; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; margin:auto;
font-family:arial;}
</style>

<script type="text/javascript">
var inter = 0;
$(document).ready(function() {
function pageload(url) {
$(".loadbox").fadeIn(300); 
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:url+"?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$("body").off("click",".noload");
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", url);
}
});
});


}
$("body").on("click","a:not('.noload,.selectboxit-option-anchor,.ui-spinner-button,.ui-corner-all')",function(eventObject) {
eventObject.preventDefault();
var url = $(this).attr("href");
pageload(url);
});

});
</script>
<div class="loadbox"><div class="catload" style="margin:30% auto 0 auto;"><img src="$url/images/load.gif"></div></div>
Print;

if($canlogin == 1) {
echo <<<Print
<div class="content">
<ul class="menu">
<li class="mtitle">סחורה</li>
<a href="$url/admin.php/pro"><li>מוצרים</li></a>
<a href="$url/admin.php/cat"><li>קטגוריות</li></a><hr>
<li class="mtitle">ניהול עסקאות</li>
<a href="$url/admin.php/customers"><li>לקוחות</li></a>
<a href="$url/admin.php/orders"><li>הזמנות</li></a><hr>
<li class="mtitle">מיקום</li>
<a href="$url/admin.php/loc"><li>סניפים</li></a>
<a href="$url/admin.php/closed"><li>שעות פעילות</li></a><hr>
<li class="mtitle">הטבות</li>
<a href="$url/admin.php/coupons"><li>קופונים</li></a>
<a href="$url/admin.php/deals"><li>מבצעים</li></a>
<a href="$url/admin.php/birthday"><li>ימי הולדת</li></a><hr>
<li class="mtitle">מערכת</li>
<a href="$url/admin.php/pages"><li>עמודים</li></a>
<a href="$url/admin.php/logs"><li>התחברויות</li></a>
<a href="$url/admin.php/settings"><li>הגדרות</li></a>
</ul>
<div class="welcome">
<div style="float:right; padding:10px;">ברוך הבא $suser!</div>
<div style="float:left; padding:9px;">
<a href="$url/admin.php">דף ניהול ראשי</a>
<a href="$url/paction.php?do=logout" class="noload">התנתקות</a>
</div>
</div>
<div class="mainblock"><div class="contents">
Print;
$do = $_GET['do'];
if($do == birthday) {
if($_GET['from'] == 'js') { ob_end_clean(); }
if($allowbd) { $checked = 'checked'; }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
if("$checked" == "checked") { $(".bdpage").show(); }
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
$(".bd").change(function() {
var bd = $(".bd:checked").val();
$(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=changebd",data:({bd:bd}),success:function(data) { 
$(".loadbox").fadeOut(300);
if(data) { $(".bdpage").slideDown(400); }else{ $(".bdpage").slideUp(400); }
}
});
});

$(".dealbd").change(function() {
var did = $("option:selected",this).val(), uid = $(this).parent().parent().attr("id"), dname = $("option:selected",this).text();
$(".loadbox").fadeIn(300);
if(confirm("האם אתה בטוח שברצונך לשלוח הטבה זו?")) {
$.ajax({type:"POST",url:"$url/paction.php?do=dealbd",data:({did:did,uid:uid}),success:function(data) { 
$(".loadbox").fadeOut(300);
$("#"+uid+" td:eq(3)").html('<span style="color:green;">נשלחה הטבה - '+dname+'</span>');
}
});
}
});

});
</script>
<div class="title" style="background:#7d7d7d;">ימי הולדת</div><div style="padding:10px;">
<input type="checkbox" class="bd" value="1" id="bd" name="bd" $checked><label for="bd">אפשר הטבות לימי הולדת</label>
<div class="bdpage" style="display:none;">
<div class="subtitle">הוסף הטבת יום הולדת</div>
<a href="$url/admin.php/deals/bd">לחץ כאן להוספת הטבת יום הולדת</a><BR>
הטבת יום הולדת ניתנת למימוש כמו מבצע, רק שבהטבת יום הולדת רק מי שיש לו יום הולדת, וההטבה שלו בתוקף, יוכל להזמין. <BR><BR>
<div class="subtitle">משתמשים עם יום הולדת קרוב</div>
בעת שליחת הטבה ללקוח, ההטבה תופיע לו בחלק העליון של האתר. תמונה להמחשה: <BR><img src="$url/images/birthday.png" style="width:700px;">
<table class="cus">
<tr class="cushead"><td>שם מלא</td><td>תאריך יום הולדת</td><td>מספר פלאפון</td><td>אפשרויות</td></tr>
Print;
$selectdeal = '<select class="dealbd"><option value="" disabled selected>בחר הטבה לשליחה..</option>';
$q141 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `bd`='1' AND `deleted`='0'");
while($ax = $q141->fetch_assoc()) { $id = $ax['id']; $name = $ax['name'];
$selectdeal .= '<option value="'.$id.'">'.$name.'</option>'; } $selectdeal .= '</select>';
$plus = explode("-",date('m-d',strtotime("+2 week"))); $minus = explode("-",date('m-d',strtotime("-2 week"))); $today = date('Y-m-d');
$q140 = $mysqli->query("SELECT * FROM `zz_buyers` WHERE (DAY(`birthday`)>='{$minus[1]}' AND MONTH(`birthday`)='{$minus[0]}') OR (DAY(`birthday`)<='{$plus[1]}' AND MONTH(`birthday`)='{$plus[0]}')");
if($q140->num_rows == 0) { 
echo '<tr><td colspan="4">אין ימי הולדת בקרוב.</td></tr>';
}
while($km = $q140->fetch_assoc()) { $id = $km['id']; $name = $km['name']; $phone = $km['phone']; $bday = $km['birthday'];
if($name == Null) { $name = '<span style="color:gray;">לא צויין</span>'; }
$q142 = $mysqli->query("SELECT * FROM `zz_birthday` WHERE `uid`='$id' AND `until`>'$today'");
if($q142->num_rows) { $sq = $q142->fetch_assoc(); $did = $sq['did']; $q143 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `id`='$did'");
$qz = $q143->fetch_assoc(); $dname = $qz['name'];
$x = '<span style="color:green;">נשלחה הטבה - '.$dname.'</span>'; }
$final = $x; if($x == Null) { $final = $selectdeal; }
echo '<tr id="u'.$id.'"><td>'.$name.'</td><td>'.$bday.'</td><td>'.$phone.'</td><td>'.$final.'</td></tr>';
$x = '';
}
echo <<<Print
</div></div>
Print;
}else{
if($do == update) {
if($_GET['from'] == 'js') { ob_end_clean(); }
$lastversion = file_get_contents('http://pizzer.net/info.php?do=lastversion');
if($version == $lastversion) {
echo '<div class="title" style="background:#7d7d7d;">עדכון המערכת</div><div style="padding:0 10px 10px;"><div class="notice">אתה משתמש בגירסא האחרונה של המערכת.</div>';
}else{
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {

$('.update').ajaxForm({beforeSend:function() { 
$(".loading").html('<div style="background:#787878;color:white;padding:3px;border-radius:3px;width:60px;font-size:10pt;"><div style="float:right;margin-top:2px;"><img src="$url/images/load.gif" style="width:15px;height:15px;"></div><div style="float:right;margin-right:3px;">מעדכן..</div><div style="clear:both;"></div></div>');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150);  $(".percent").remove(); $('.catload').css("height","32px");
if(xhr.responseText == 'error') {
$(".loading").fadeOut(200,function() { $(this).html('<span style="color:#AD6969;">העדכון נכשל.</span>').fadeIn(200); });
}else{
$(".loading").fadeOut(200,function() { $(this).html('<span style="color:#7AB24C;">העדכון בוצע בהצלחה.</span>').fadeIn(200); });
} } });

});
</script>
<div class="title" style="background:#7d7d7d;">עדכון המערכת</div><div style="padding:10px;">
<div class="subtitle">עדכן את המערכת</div>
נמצאה גרסא חדשה למערכת (Pizzer v$lastversion).<BR>
לתשומת לבך, עדכון המערכת יחליף את הקבצים הנוכחים, וכל שינוי בקבצים שביצעת באופן ידני יוסר. <BR><BR>
<form action='$url/paction.php?do=update' method="POST" class="update" enctype="multipart/form-data">
<div class="loading"><input type="submit" value="עדכן עכשיו"></div>

</form>
Print;
}
}else{
if($do == settings) {
if($_GET['from'] == 'js') { ob_end_clean(); }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$("#logo").change(function() {
var finfo = this.files[0];
$(".choose").fadeOut(200,function() { $(this).text(finfo.name).fadeIn(200); });
});

$('.setup').ajaxForm({beforeSend:function() { $(".loadbox").fadeIn(300); $('.catload').append('<div class="percent">0%</div>').css("height","42px"); },uploadProgress: function(event, position, total, percentComplete) { 
$(".percent").text(percentComplete + '%');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150);  $(".percent").remove(); $('.catload').css("height","32px");
if(xhr.responseText == 'e1') {
$("#error").text("ניתן להעלות רק תמונות מסוג jpg,bmp,png,gif.").slideDown(500);
}else{
if(xhr.responseText == 'e2') {
$("#error").text("כתובת האתר שהוקשה לא תקינה.").slideDown(500);
}else{
if(xhr.responseText == 'e3') {
$("#error").text("כתובת המייל שהוקשה לא תקינה.").slideDown(500);
}else{
if(xhr.responseText == 'e4') {
$("#error").text("כתובת המייל של ה PayPal אינה תקינה.").slideDown(500);
}else{
if(xhr.responseText == 'e13') {
$("#error").text("חובה למלות את שלושת סעיפי ה PelePay באופן תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e15') {
$("#error").text("שם משתמש או סיסמא של PelePay אינם תקינים.").slideDown(500);
}else{
if(xhr.responseText == 'e5') {
$("#error").text("מספר הטלפון שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e6') {
$("#error").text("שם הפיצריה שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e7') {
$("#error").text("יש להקיש סכום מינימלי לקניה באופן תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e12') {
$("#error").text("יש להקיש את דמי המשלוח באופן תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e8') {
$("#error").text("יש להקיש כמות התחברויות לפני חסימה באופן תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e9') {
$("#error").text("יש להקיש את זמן החסימה באופן תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e10') {
$("#error").text("שם המשתמש שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e11') {
$("#error").text("על הסיסמא להיות בין 6-18 תווים ולהכיל גם אותיות קטנות וגדולות, וגם מספרים.").slideDown(500);
}else{
if(xhr.responseText == 'e14') {
$("#error").text("יש להקיש את מספר הרישיון של המערכת.").slideDown(500);
}else{
$("#error").slideUp(500);
$("input[type=text]").val("");
$("#img").val('');
$(".choose").text("בחר תמונה להעלאה..");
$(".loadbox").fadeIn(300); $(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/settings?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
if(data.indexOf('split') == -1) {
window.location.reload();
}else{
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
}
window.history.pushState("string", "title", "$url/admin.php/settings");
}
});
});
} } } } } } } } } } } } } } } } }); 

});
</script>
<div class="title" style="background:#7d7d7d;">הגדרות</div><div style="padding:10px;">

<form action='$url/paction.php?do=settings' method="POST" enctype="multipart/form-data" class="setup">
<table>
<tr><td><b>פרטי האתר</b></td><td></td></tr>
<tr><td>לוגו האתר:<div style="font-size:8pt;">רצוי בגודל 220x50.</div></td><td><div class="file" style="width:160px;overflow:hidden;"><div class="choose">$logo</div><input type="file" name="logo" id="logo" style="opacity:0;width:160px;"></div></td></tr>
<tr><td>כתובת האתר:</td><td><input type="text" name="url" value="$url" style="direction:ltr;"></td></tr>
<tr><td>כתובת המייל:<div style="font-size:8pt;">מכתבי הצור קשר יגיעו לשם.</div></td><td><input type="text" name="adminmail" value="$adminmail" style="direction:ltr;"></td></tr>
<tr><td>מספר טלפון להזמנות:<div style="font-size:8pt;">יופיע בראש האתר.</div></td><td><input type="text" name="phone" value="$orderphone" style="direction:ltr;"></td></tr>
<tr><td>שם העסק:</td><td><input type="text" name="name" value="$sitename"></td></tr>
<tr><td>מספר רישיון:<div style="font-size:8pt;">רשום ב<a href="http://pizzer.net/account" class="noload" target="_blank">חשבון משתמש</a></div></td><td><input type="text" value="$sitepw" name="li"></td></tr>
<tr><td><b>הגבלות</b></td><td></td></tr>
<tr><td>סכום מינימלי לקנייה:</td><td><input type="text" name="minbill" value="$minbill"></td></tr>
<tr><td>דמי משלוח בהזמנה:</td><td><input type="text" name="deliver" value="$deliverprice"></td></tr>
<tr><td>כמות התחברויות לפני חסימה:<div style="font-size:8pt;">כמה פעמים מותר למשתמש לנסות להתחבר<BR> לפני חסימה של כמה דקות?</div></td><td><input type="text" name="logtry" value="$logtry"></td></tr>
<tr><td>זמן המתנה בחסימה:<div style="font-size:8pt;">כמה זמן משתמש צריך לחכות<BR> לפני שהוא רשאי שוב להתחבר? (בדקות)</div></td><td><input type="text" name="logtime" value="$logtime"></td></tr>
<tr><td><b>תשלומים וסליקות</b></td><td></td></tr>
<tr><td>מייל של PayPal:<div style="font-size:8pt;">השאר ריק כדי לא לאפשר רכישות<BR> דרך PayPal.<BR><a href="http://pizzer.net/account" class="noload" target="_blank">פרטים נוספים</a></div></td><td><input type="text" name="paypal" value="$paypal" style="direction:ltr;"></td></tr>
<tr><td>מייל של PelePay:<div style="font-size:8pt;">השאר ריק כדי לא לאפשר רכישות<BR> דרך כרטיס אשראי.<BR><a href="http://pizzer.net/account" class="noload" target="_blank">פרטים נוספים</a></div></td><td><input type="text" name="pelem" value="$pelem" style="direction:ltr;"></td></tr>
<tr><td>שם משתמש ב PelePay:<div style="font-size:8pt;">השאר ריק כדי לא לאפשר רכישות<BR> דרך כרטיס אשראי.<BR> חובה למלות כדי למנוע תשלומים מזוייפים.</div></td><td><input type="text" name="peleu" value="$peleu" style="direction:ltr;"></td></tr>
<tr><td>סיסמא ב PelePay:<div style="font-size:8pt;">השאר ריק כדי לא לאפשר רכישות<BR> דרך כרטיס אשראי.<BR> חובה למלות כדי למנוע תשלומים מזוייפים.<BR>אם כבר מילאת בעבר, השאר ריק כדי לא לשנות.</div></td><td><input type="text" name="pelep" style="direction:ltr;"></td></tr>
<tr><td>מספר משתמש ב PayCall:<div style="font-size:8pt;">השאר ריק כדי לא לאפשר רכישות<BR> דרך שיחת טלפון.<BR> בעת פתיחת החשבון באתר,<BR> ישלח אליך מייל עם מספר המשתמש.<BR><a href="http://pizzer.net/account" class="noload" target="_blank">פרטים נוספים</a></div></td><td><input type="text" name="paycall" style="direction:ltr;" value="$paycall"></td></tr>

<tr><td><b>פרטי אדמין</b></td><td></td></tr>
<tr><td>שם משתמש לאדמין:</td><td><input type="text" name="user" value="$suser"></td></tr>
<tr><td>סיסמא לאדמין:<div style="font-size:8pt;">השאר ריק כדי לא לשנות.</div></td><td><input type="password" name="pass"></td></tr>
</table>
<input type="submit" value="שנה הגדרות" name="set"><div id="error"></div>
</form>
</div>
Print;
}else{
if($do == logs) {
if($_GET['from'] == 'js') { ob_end_clean(); } 
$q104 = $mysqli->query("SELECT * FROM `zz_adminlogs` ORDER BY `id` DESC LIMIT 100"); $nrows = $q104->num_rows;
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$(".clear").click(function() {
$(".loadbox").fadeIn(150);
$.ajax({url:"$url/paction.php?do=clearlog",success:function(data) {
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/logs?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/logs");
}
});
});
}
});
});
});
</script>
<div class="title" style="background:#7d7d7d;">התחברויות</div><div style="padding:10px;">
100 נסיונות התחברות אחרונות לפאנל הניהול. <BR> <a href="javascript:void(0);" style="color:red;text-decoration:none;" class="clear noload">נקה את הרשימה ($nrows)</a>
<table class="cus">
<tr class="cushead"><td>שם משתמש</td><td>סיסמא</td><td>תאריך</td><td>אייפי</td></tr>
Print;

if($nrows == 0) {
echo '<tr><td colspan="4">לא נמצאו התחברויות</td></tr>';
}else{
while($so = $q104->fetch_assoc()) { $user = $so['user']; $pass = $so['pass']; $date = $so['date']; $ip = $so['ip'];
echo '<tr><td>'.$user.'</td><td>'.$pass.'</td><td>'.$date.'</td><td>'.$ip.'</td></tr>';
} }
echo '</table></div>';
}else{
if($do == editpage) {
$id = $_GET['id'];
if($_GET['from'] == 'js') { ob_end_clean(); } 
$q102 = $mysqli->query("SELECT * FROM `zz_pages` WHERE `id`='$id'");
if($q102->num_rows == 0) {
echo '<div class="title" style="background:#7d7d7d;">עמודים</div><div style="padding:0 10px 10px;"><div class="notice">לא קיים דף כזה.</div></div>';
}else{
$oe = $q102->fetch_assoc(); $title = $oe['title']; $link = $oe['link']; $content = $mysqli->real_escape_string($oe['content']); $showm = $oe['showm']; $showb = $oe['showb'];
echo <<<Print
<script type="text/javascript"> 
$(document).ready(function() { 
$("textarea").wysibb();
$("#add").click(function(eventObject) {
eventObject.preventDefault();
var id = $id,title = $("#title").val(),link = $("#link").val(),content = $("#content").htmlcode(),showm = $("#showm:checked").val(),showb = $("#showb:checked").val();
$(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=editpage",data:({id:id,title:title,link:link,content:content,showm:showm,showb:showb}),success:function(data) {
$(".loadbox").fadeOut(300);
if(data == 'e1') {
$("#error").text("כותרת הדף קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e2') {
$("#error").text("התוכן שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("הכתובת שהוקשה לא תקינה.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("יש לבחור בין כתובת לבין תוכן ולמלא את הנבחר.").slideDown(500);
}else{
$(".loadbox").fadeIn(300); $(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/pages?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/pages");
}
});
});
} } } } }
});
});
$(".del").click(function() {
var id = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/paction.php?do=delpage",data:({id:id}),success:function(data) {
$(".loadbox").fadeOut(150);
if(!data) {
$("#d"+id).parent().parent().fadeTo(200,0,function() { $(this).animate({"width":"0px","height":"0px"},200,function() { $(this).remove(); }); });
} }
});
});
$("textarea").htmlcode('$content');
if($showm == 1) { $("#showm").prop("checked",true); }
if($showb == 1) { $("#showb").prop("checked",true); }

$("#showm,#showb").button();

}); 
</script>

<div class="title" style="background:#7d7d7d;">עמודים</div><div style="padding:10px;">
<div class="subtitle">ערוך עמוד</div><div style="font-size:10pt;">יש לבחור האם העמוד יהיה עם תוכן או שיהיה קישור שיעביר לדף אחר (דף פייסבוק למשל).<BR> יש למלא את השדה שנבחר ואת האחר להשאיר ריק.</div>
<form action='' method="POST">
<table>
<tr><td style="width:100px;">כותרת העמוד:</td><td><input type="text" id="title" value="$title"></td></tr>
<tr><td style="width:100px;">הצגת קישור:</td><td><input type="checkbox" value="1" id="showm" name="index"><label for="showm">הצג בתפריט</label>
<input type="checkbox" value="1" id="showb" name="index"><label for="showb">הצג בתחתית הדף</label></td></tr>
<tr><td>כתובת העמוד:</td><td><input type="text" id="link" style="direction:ltr;"  value="$link"></td></tr>
<tr><td><b>או</b></td></tr>
<tr><td>תוכן העמוד:</td></tr>
<tr><td colspan="2"><textarea id="content" style="height:100px;"></textarea></td></tr>
</table>
<input type="submit" value="ערוך" id="add"><div id="error"></div>
</form>
</div>
Print;
}
}else{
if($do == pages) {
if($_GET['from'] == 'js') { ob_end_clean(); } 
echo <<<Print
<script type="text/javascript"> 
$(document).ready(function() { 
$("textarea").wysibb();
$("#add").click(function(eventObject) {
eventObject.preventDefault();
var title = $("#title").val(),link = $("#link").val(),content = $("#content").htmlcode(),showm = $("#showm:checked").val(),showb = $("#showb:checked").val();

$(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=addpage",data:({title:title,link:link,content:content,showm:showm,showb:showb}),success:function(data) {
$(".loadbox").fadeOut(300);
if(data == 'e1') {
$("#error").text("כותרת הדף קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e2') {
$("#error").text("התוכן שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("הכתובת שהוקשה לא תקינה.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("יש לבחור בין כתובת לבין תוכן ולמלא את הנבחר.").slideDown(500);
}else{
$(".loadbox").fadeIn(300); $(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/pages?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/pages");
}
});
});
} } } } }
});
});
$(".del").click(function() {
var id = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/paction.php?do=delpage",data:({id:id}),success:function(data) {
$(".loadbox").fadeOut(150);
if(!data) {
$("#d"+id).parent().parent().fadeTo(200,0,function() { $(this).animate({"width":"0px","height":"0px"},200,function() { $(this).remove(); }); });
} }
});
});

$("#showm,#showb").button();
}); 
</script>

<div class="title" style="background:#7d7d7d;">עמודים</div><div style="padding:10px;">
<div class="subtitle">הוסף עמוד</div><div style="font-size:10pt;">יש לבחור האם העמוד יהיה עם תוכן או שיהיה קישור שיעביר לדף אחר (דף פייסבוק למשל).<BR> יש למלא את השדה שנבחר ואת האחר להשאיר ריק.</div>
<form action='' method="POST">
<table>
<tr><td style="width:100px;">כותרת העמוד:</td><td><input type="text" id="title"></td></tr>
<tr><td style="width:100px;">הצגת קישור:</td><td><input type="checkbox" value="1" id="showm" name="index"><label for="showm">הצג בתפריט</label>
<input type="checkbox" value="1" id="showb" name="index"><label for="showb">הצג בתחתית הדף</label></td></tr>
<tr><td>כתובת העמוד:</td><td><input type="text" id="link" style="direction:ltr;"></td></tr>
<tr><td><b>או</b></td></tr>
<tr><td>תוכן העמוד:</td></tr>
<tr><td colspan="2"><textarea id="content" style="height:100px;"></textarea></td></tr>
</table>
<input type="submit" value="הוסף" id="add"><div id="error"></div>
</form>
<BR>
<div class="subtitle">כל העמודים</div>
Print;
$q101 = $mysqli->query("SELECT * FROM `zz_pages` ORDER BY `id` DESC");
while($sd = $q101->fetch_assoc()) { $id = $sd['id']; $title = $sd['title']; $content = $sd['content']; $showm = $sd['showm']; $showb = $sd['showb'];
$link = $sd['link'];
if($link == Null) { $mklink = $url.'/pages/'.$id; $ulink = $mklink; }else{ $len = strlen($link); $ulink = $link;
$mklink = $link;
if($len > 40) { $mklink = substr($link,0,15).'...'.substr($link,$len-15,$len); }
}
if($showm==1) { $showm = 'מוצג בתפריט'; }else{ $showm = ''; }
if($showb==1) { if($showm=='מוצג בתפריט') { $showm .= ' | '; } $showb = 'מוצג בתחתית'; }else{ $showb = ''; }

echo <<<Print
<div style="background:white; width:250px; float:right; margin:20px 10px; border:1px solid #E5E5E5; text-align:center;">
<div style="background:#C90628; color:white; padding:12px 10px; font-size:12pt; font-weight:bold;">$title</div>
<div style="padding:7px; font-size:10pt;"><a href="$ulink" target="_blank" class="noload">$mklink</a><BR>
<a href="$url/admin.php/editpage/$id" style="text-decoration:none;">ערוך</a> | <a href="javascript:void(0);" style="text-decoration:none;color:red;" class="del noload" id="d$id">מחק</a><div style="font-size:8pt;">$showm $showb</div></div>
</div>
Print;
}

echo '</div>';
}else{
if($do == editdeal) {
if($_GET['from'] == 'js') { ob_end_clean(); } 
$prolist = "";
$q95 = $mysqli->query("SELECT * FROM `zz_cat`");
while($of = $q95->fetch_assoc()) { $cid = $of['id']; $name = $of['name']; 
$prolist .= '<option value="{'.$cid.'}">בחירה מהקטגוריה '.$name.'</option>';
$q96 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='$cid' AND `show`='1'");
while($fo = $q96->fetch_assoc()) { $pid = $fo['id']; $pname = $fo['name'];
$prolist .= '<option value="'.$pid.'">'.$pname.'</option>'; } }
$q97 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `show`='1'");
$numadd = $q97->num_rows; $index = 0;
$id = $_GET['id'];
$q100 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `id`='$id' AND `deleted`='0'");
if($q100->num_rows == 0) {
echo '<div class="title" style="background:#7d7d7d;">ערוך מבצע</div><div style="padding:0 10px 10px;"><div class="notice">מבצע לא נמצא.</div></div>';
}else{
while($tr = $q100->fetch_assoc()) { $id = $tr['id']; $name = $tr['name']; $index = $tr['index']; $prod = $tr['products']; $bd = $tr['bd'];
if($bd) { $checked = 'checked'; }
$price = $tr['price']; $img = $tr['img']; $prods = substr_count($prod,',')+1; $parray = explode(",",$prod); $jsarray = '['; $adarray = '[';
for($i = 0; $i < sizeof($parray); $i++) { $adarray .= substr_count($parray[$i],"+").','; $cleanpro = str_replace("+","",$parray[$i]);
$jsarray .= '"'.$cleanpro.'",'; } $jsarray = substr($jsarray,0,-1); $adarray = substr($adarray,0,-1); $jsarray .= ']'; $adarray .= ']';
if($allowbd) { $birthday = '<tr><td colspan="2"><input type="checkbox" class="bd" value="1" id="bd" name="bd" '.$checked.'><label for="bd">סמן אם ברצונך שזוהי תהיה הטבה לימי הולדת.</label></td></tr>'; }

echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var index = 0, link, prodarray = $jsarray, addons = $adarray;
$("#s$index").prop("checked",true);
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
String.prototype.repeat = function( num ) { return new Array( num + 1 ).join( this ); }
function addpro(i,prolist) { link = '';
if(i > 0) { link = '<a href="javascript:void(0);" style="color:red; text-decoration:none; font-size:10pt;" class="delpro noload">הסר מוצר</a>'; }
return '<div><div style="vertical-align:middle; line-height:30px; margin-bottom:5px;display:none;" class="probox" id="co'+i+'"><div style="font-weight:bold;display:inline;vertical-align:middle;">מוצר:</div> <select class="pro" id="p'+i+'"><option selected disabled>בחר מוצר או קטגוריה</option>$prolist</select> '+link+'</div><div class="addons" id="a'+i+'" style="display:none;margin-bottom:5px;"><b>תוספות בחינם למוצר:</b> <input class="spinner" id="s'+i+'" name="value" value="0" style="width:20px;font-size:10pt;padding:1px;"></div></div>';
}
$("body").off("click",".delpro").on("click",".delpro",function() {
$(this).parent().parent().slideUp(400,function() { $(this).remove(); });
});
$(".indexes").buttonset();
$(".spinner").spinner({spin:function(event, ui) {
if(ui.value <= 0) {
$(this).spinner("value", 0);
return false;
}else{
if(ui.value >= $numadd) {
$(this).spinner("value", $numadd);
return false;
} } }
});
$("body").off("change",".pro").on("change",".pro",function() {
var selectedpro = $(this).val(), tid = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(200);
$.ajax({type:"POST",url:"$url/paction.php?do=checkaddons",data:({selectedpro:selectedpro}),success:function(data) {
$(".loadbox").fadeOut(200);
if(data == 1) { 
$("#a"+tid).slideDown(400);
}else{
$("#a"+tid).slideUp(400);
}
}
});
});
function loadlist(index) {
var content = addpro(index,'$prolist');
$('.loadmore').before(content);
if(index < $prods) { $("#co"+index).show(); }else{ $("#co"+index).slideDown(400);
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300}); }
$(".spinner").spinner({spin:function(event, ui) {
if(ui.value <= 0) {
$(this).spinner("value", 0);
return false;
}else{
if(ui.value >= $numadd) {
$(this).spinner("value", $numadd);
return false;
} } }
});
}
$(".loadmore").click(function() {
index++;
loadlist(index);
});
for(var i = 0; i < $prods; i++) {
loadlist(index);
$("#p"+i).val(prodarray[i]);
if(addons[i] > 0) {
$("#a"+i).show();
$(".spinner#s"+i).spinner().spinner("value",addons[i]);
}
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
index++;
}

$("#edit").click(function() { var prod = '';
$('.probox').each(function() {
var tid = $(this).attr("id").slice(2);
prod += $("#p"+tid).val();
if($("#a"+tid).css("display") != 'none') { prod += "+".repeat($(".spinner#s"+tid).spinner().spinner("value")); }
prod += ",";
});
prod = prod.slice(0,-1);
$("#choosep").val(prod);
});
$("#img").change(function() {
var finfo = this.files[0];
$(".choose").fadeOut(200,function() { $(this).text(finfo.name).fadeIn(200); });
});
$('.editdeal').ajaxForm({beforeSend:function() { $(".loadbox").fadeIn(300); $('.catload').append('<div class="percent">0%</div>').css("height","42px"); },uploadProgress: function(event, position, total, percentComplete) { 
$(".percent").text(percentComplete + '%');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150);  $(".percent").remove(); $('.catload').css("height","32px");
if(xhr.responseText == 'e1') {
$("#error").text("כותרת המבצע קצרה/ארוכה מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e3') {
$("#error").text("יש להקיש מחיר תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e4') {
$("#error").text("יש לבחור את מוצרי המבצע באופן תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e5') {
$("#error").text("ניתן להעלות רק תמונות מסוג jpg,bmp,png,gif.").slideDown(500);
}else{
$("#error").slideUp(500);
$("input[type=text]").val("");
$("#img").val('');
$(".choose").text("בחר תמונה להעלאה..");
$(".loadbox").fadeIn(300); $(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/deals?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/deals");
}
});
});
} } } } } }); 

});
</script>
<div class="title" style="background:#7d7d7d;">ערוך מבצע</div><div style="padding:10px;">
<form action="$url/paction.php?do=editdeal" method="post" enctype="multipart/form-data" class="editdeal">
<input type="hidden" id="choosep" value="$prod" name="prod">
<input type="hidden" id="id" value="$id" name="id">
<table>
<tr><td>כותרת המבצע:</td><td><input type="text" id="name" name="name" value="$name"></td></tr>
<tr><td>מחיר:</td><td><input type="text" id="price" name="price" value="$price"></td></tr>
<tr><td>מוצרים:</td><td class="prodlist">
<div class="loadmore">הוסף עוד מוצר</div>
</td></tr>
<tr><td>תמונת המבצע:</td><td><div class="file" style="width:160px;overflow:hidden;"><div class="choose">$img</div><input type="file" name="img" id="img" style="opacity:0;width:160px;"></div></td></tr>
$birthday
</table>
<input type="submit" name="edit" id="edit" value="סיים עריכה"><div id="error"></div>
</form>
</div>
Print;
} } 
}else{
if($do == deals) {
if($_GET['from'] == 'js') { ob_end_clean(); } 
$prolist = ""; $id = $_GET['id'];
if($id == "bd") { $checked = "checked"; }
$q95 = $mysqli->query("SELECT * FROM `zz_cat`");
while($of = $q95->fetch_assoc()) { $cid = $of['id']; $name = $of['name']; 
$prolist .= '<option value="{'.$cid.'}">בחירה מהקטגוריה '.$name.'</option>';
$q96 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='$cid' AND `show`='1'");
while($fo = $q96->fetch_assoc()) { $pid = $fo['id']; $pname = $fo['name'];
$prolist .= '<option value="'.$pid.'">'.$pname.'</option>';
} }

$q97 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `show`='1'");
$numadd = $q97->num_rows; $index = 0;
if($allowbd) { $birthday = '<tr><td colspan="2"><input type="checkbox" class="bd" value="1" id="bd" name="bd" '.$checked.'><label for="bd">סמן אם ברצונך שזוהי תהיה הטבה לימי הולדת.</label></td></tr>'; }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var index = 0, link;
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
String.prototype.repeat = function( num ) { return new Array( num + 1 ).join( this ); }
function addpro(i,prolist) { link = '';
if(i > 0) { link = '<a href="javascript:void(0);" style="color:red; text-decoration:none; font-size:10pt;" class="delpro noload">הסר מוצר</a>'; }
return '<div><div style="vertical-align:middle; line-height:30px; margin-bottom:5px;display:none;" class="probox" id="co'+i+'"><div style="font-weight:bold;display:inline;vertical-align:middle;">מוצר:</div> <select class="pro" id="p'+i+'"><option selected disabled>בחר מוצר או קטגוריה</option>$prolist</select> '+link+'</div><div class="addons" id="a'+i+'" style="display:none;margin-bottom:5px;"><b>תוספות בחינם למוצר:</b> <input class="spinner" id="s'+i+'" name="value" value="0" style="width:20px;font-size:10pt;padding:1px;"></div></div>';
}
$("body").off("click",".delpro").on("click",".delpro",function() {
$(this).parent().parent().slideUp(400,function() { $(this).remove(); });
});
$(".indexes").buttonset();
$(".spinner").spinner({spin:function(event, ui) {
if(ui.value <= 0) {
$(this).spinner("value", 0);
return false;
}else{
if(ui.value >= $numadd) {
$(this).spinner("value", $numadd);
return false;
} } }
});
$("body").off("change",".pro").on("change",".pro",function() {
var selectedpro = $(this).val(), tid = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(200);
$.ajax({type:"POST",url:"$url/paction.php?do=checkaddons",data:({selectedpro:selectedpro}),success:function(data) {
$(".loadbox").fadeOut(200);
if(data == 1) { 
$("#a"+tid).slideDown(400);
}else{
$("#a"+tid).slideUp(400);
}
}
});
});
function loadlist(index) {
var content = addpro(index,'$prolist');
$('.loadmore').before(content);
if(index == 0) { $("#co"+index).show(); }else{ $("#co"+index).slideDown(400); }
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
$(".spinner").spinner({spin:function(event, ui) {
if(ui.value <= 0) {
$(this).spinner("value", 0);
return false;
}else{
if(ui.value >= $numadd) {
$(this).spinner("value", $numadd);
return false;
} } }
});
}
$(".loadmore").click(function() {
index++;
loadlist(index);
});
loadlist(index);
$("#add").click(function() { var prod = '';
$('.probox').each(function() {
var tid = $(this).attr("id").slice(2);
prod += $("#p"+tid).val();
if($("#a"+tid).css("display") != 'none') { prod += "+".repeat($(".spinner#s"+tid).spinner().spinner("value")); }
prod += ",";
});
prod = prod.slice(0,-1);
$("#choosep").val(prod);

});
$("#img").change(function() {
var finfo = this.files[0];
$(".choose").fadeOut(200,function() { $(this).text(finfo.name).fadeIn(200); });
});
$('.newdeal').ajaxForm({beforeSend:function() { $(".loadbox").fadeIn(300); $('.catload').append('<div class="percent">0%</div>').css("height","42px"); },uploadProgress: function(event, position, total, percentComplete) { 
$(".percent").text(percentComplete + '%');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150);  $(".percent").remove(); $('.catload').css("height","32px");
if(xhr.responseText == 'e1') {
$("#error").text("כותרת המבצע קצרה/ארוכה מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e3') {
$("#error").text("יש להקיש מחיר תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e4') {
$("#error").text("יש לבחור את מוצרי המבצע באופן תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e5') {
$("#error").text("ניתן להעלות רק תמונות מסוג jpg,bmp,png,gif.").slideDown(500);
}else{
$("#error").slideUp(500);
$("input[type=text]").val("");
$("#img").val('');
$(".choose").text("בחר תמונה להעלאה..");
$(".loadbox").fadeIn(300); $(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/deals?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/deals");
}
});
});
} } } } } }); 
$(".del").click(function() {
var id = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/paction.php?do=deldeal",data:({id:id}),success:function(data) {
$(".loadbox").fadeOut(150);
if(!data) {
$("#d"+id).parent().parent().parent().fadeTo(200,0,function() { $(this).animate({"width":"0px","height":"0px"},200,function() { $(this).remove(); }); });
} }
});
});
});
</script>
<div class="title" style="background:#7d7d7d;">מבצעים</div><div style="padding:10px;">
<div class="subtitle">הוסף מבצע</div>
<form action="$url/paction.php?do=newdeal" method="post" enctype="multipart/form-data" class="newdeal">
<input type="hidden" id="choosep" name="prod">
<table>
<tr><td>כותרת המבצע:</td><td><input type="text" id="name" name="name"></td></tr>
<tr><td>מחיר:</td><td><input type="text" id="price" name="price"></td></tr>
<tr><td>מוצרים:</td><td class="prodlist">
<div class="loadmore">הוסף עוד מוצר</div>
</td></tr>
<tr><td>תמונת המבצע:</td><td><div class="file" style="width:160px;overflow:hidden;"><div class="choose">בחר תמונה להעלאה..</div><input type="file" name="img" id="img" style="opacity:0;width:160px;"></div></td></tr>
$birthday
</table>
<input type="submit" name="add" id="add" value="הוסף מבצע"><div id="error"></div>
</form><BR><BR>
<div class="subtitle">כל המבצעים</div><div style="clear:both;"></div>
Print;
$q98 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `deleted`='0' ORDER BY `id` DESC");
while($jn = $q98->fetch_assoc()) { $id = $jn['id']; $name = $jn['name']; $price = $jn['price']; $img = $jn['img']; $bd = $jn['bd'];
if($bd) { $bd = 'הטבת יום הולדת'; }else{ $bd = ''; }
echo <<<Print
<div class="offp">
<div class="protitle">$name</div>
<div class="prod" style="background:url($url/images/uploads/$img) no-repeat center; background-size: cover;"><div class="infopro">מחיר: $price ₪ <a href="javascript:void(0);" class="del noload" id="d$id"><div class="redb">מחק</div></a><a href="$url/admin.php/editdeal/$id"><div class="redb">ערוך</div></a></div> <div style="background:white; font-size:10pt; width:90px; padding:0 5px;">$bd</div></div>
</div>
Print;
}
echo '<div style="clear:both;"></div></div>';
}else{
if($do == editco) {
$id = $_GET['id'];
if($_GET['from'] == 'js') { ob_end_clean(); }
$q96 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `id`='$id'");
while($dx = $q96->fetch_assoc()) { $code = $dx['code']; $show = $dx['show']; $describe = $dx['describe'];
$limit = $dx['limit']; $from = $dx['from']; $to = $dx['to'];
for($i = 0; $i <= 1; $i++) {
if($i == 0) { $v = $from; }else{ $v = $to; }
$h[$i] = date('H', $v);
$m[$i] = date('i', $v);
$d[$i] = date('d/m/Y', $v);
}
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$.datepicker.regional['he'] = { closeText: 'סגור', prevText: '<הקודם', nextText: 'הבא>', currentText: 'היום', monthNames: ['ינואר','פברואר','מרץ','אפריל','מאי','יוני', 'יולי','אוגוסט','ספטמבר','אוקטובר','נובמבר','דצמבר'], monthNamesShort: ['1','2','3','4','5','6', '7','8','9','10','11','12'], dayNames: ['ראשון','שני','שלישי','רביעי','חמישי','שישי','שבת'], dayNamesShort: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], dayNamesMin: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], weekHeader: 'Wk', dateFormat: 'dd/mm/yy', firstDay: 0, isRTL: true, showMonthAfterYear: false, yearSuffix: ''};
$.datepicker.setDefaults($.datepicker.regional['he']);
$( "#from" ).datepicker({showAnim: "fadeIn",onClose: function( selectedDate ) { $( "#to" ).datepicker( "option", "minDate", selectedDate ); } });
$( "#to" ).datepicker({showAnim: "fadeIn",onClose: function( selectedDate ) { $( "#from" ).datepicker( "option", "maxDate", selectedDate ); } });
$("#s$show").prop("checked",true);
$(".showc").buttonset();
$("#fromm").val('$m[0]'); $("#fromh").val('$h[0]');
$("#tom").val('$m[1]'); $("#toh").val('$h[1]');
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
$("#edit").click(function(eventObject) {
eventObject.preventDefault();
var id = $id,code = $("#code").val(),describe = $("#describe").val(),show = $(".show:checked").val(),limit = $("#limit").val(),from = $("#from").val()+" - "+$("#fromh").val()+":"+$("#fromm").val(),to = $("#to").val()+" - "+$("#toh").val()+":"+$("#tom").val(),reason = $("#reason").val(),loc = $("#loc").val();
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/paction.php?do=editcoupon",data:({id:id,code:code,show:show,describe:describe,from:from,to:to,limit:limit}),success:function(data) {
$(".loadbox").fadeOut(150);
if(data == 'e1') {
$("#error").text("הקוד שהוקש קצר/ארוך מידי.").slideDown(500);
}else{ 
if(data == 'e2') {
$("#error").text("יש לבחור האם להציג את הקופון בדף תשלום או לא.").slideDown(500);
}else{ 
if(data == 'e3') {
$("#error").text("התיאור שהוקש קצר/ארוך מידי.").slideDown(500);
}else{ 
if(data == 'e5') {
$("#error").text("תאריך ההתחלה אינו תקין.").slideDown(500);
}else{ 
if(data == 'e6') {
$("#error").text("תאריך הסיום אינו תקין.").slideDown(500);
}else{ 
if(data == 'e7') {
$("#error").text("מגבלת השימושים צריכה להיות במספרים בלבד.").slideDown(500);
}else{ 
if(data == 'e8') {
$("#error").text("קוד קופון כזה כבר קיים במערכת, הכנס אחר.").slideDown(500);
}else{
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/coupons?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/coupons");
}
});
});
} } } } } } } }
});
});
});
</script>
<div class="title" style="background:#7d7d7d;">עריכת קופון</div><div style="padding:10px;">
<form action='' method="POST">
<table>
<tr><td>קוד קופון:</td><td colspan="2"><input type="text" id="code" value="$code"></td></tr>
<tr><td>הצג קופון בדף תשלום:</td><td colspan="2" class="showc">
<input type="radio" class="show" value="1" id="s1" name="radio"><label for="s1">כן</label>
<input type="radio" class="show" value="0" id="s0" name="radio"><label for="s0">לא</label>
</td></tr>
<tr><td>תיאור הקופון:</td><td colspan="2"><input type="text" id="describe" value="$describe"></td></tr>
<tr><td>תאריך התחלה:</td><td><input type="text" id="from" value="$d[0]"></td><td style="line-height:30px; vertical-align:middle;">שעה: 
Print;
$h = ""; $m = "";
for($i = 0; $i < 60; $i++) { 
if($i < 10) { $i = sprintf("%02s", $i); }
if($i <= 23) { $h .= '<option value="'.$i.'">'.$i.'</option>'; }
$m .= '<option value="'.$i.'">'.$i.'</option>';
}
echo '<select id="fromm">'.$m.'</select> : <select id="fromh">'.$h.'</select>';
echo <<<Print
</td></tr>
<tr><td>תאריך סיום:</td><td><input type="text" id="to" value="$d[1]"></td><td style="line-height:30px; vertical-align:middle;">שעה: 
Print;
$h = ""; $m = "";
for($i = 0; $i < 60; $i++) { 
if($i < 10) { $i = sprintf("%02s", $i); }
if($i <= 23) { $h .= '<option value="'.$i.'">'.$i.'</option>'; }
$m .= '<option value="'.$i.'">'.$i.'</option>';
}
echo '<select id="tom">'.$m.'</select> : <select id="toh">'.$h.'</select>';
echo <<<Print
</td></tr>
<tr><td>כמות מימושים:<div style="font-size:8pt;">להשאיר ריק אם ללא הגבלה</div></td><td colspan="2"><input type="text" id="limit" value="$limit"></td><tr>
</table>
<input type="submit" value="ערוך" id="edit"><div id="error"></div>
</form></div>
Print;
}
}else{
if($do == coupons) { 
if($_GET['from'] == 'js') { ob_end_clean(); }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
$.datepicker.regional['he'] = { closeText: 'סגור', prevText: '<הקודם', nextText: 'הבא>', currentText: 'היום', monthNames: ['ינואר','פברואר','מרץ','אפריל','מאי','יוני', 'יולי','אוגוסט','ספטמבר','אוקטובר','נובמבר','דצמבר'], monthNamesShort: ['1','2','3','4','5','6', '7','8','9','10','11','12'], dayNames: ['ראשון','שני','שלישי','רביעי','חמישי','שישי','שבת'], dayNamesShort: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], dayNamesMin: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], weekHeader: 'Wk', dateFormat: 'dd/mm/yy', firstDay: 0, isRTL: true, showMonthAfterYear: false, yearSuffix: ''};
$.datepicker.setDefaults($.datepicker.regional['he']);
$( "#from" ).datepicker({showAnim: "fadeIn",onClose: function( selectedDate ) { $( "#to" ).datepicker( "option", "minDate", selectedDate ); } });
$( "#to" ).datepicker({showAnim: "fadeIn",onClose: function( selectedDate ) { $( "#from" ).datepicker( "option", "maxDate", selectedDate ); } });
$("#add").click(function(eventObject) {
eventObject.preventDefault();
var code = $("#code").val(),describe = $("#describe").val(),percent = $("#percent").val(),show = $(".show:checked").val(),limit = $("#limit").val(),from = $("#from").val()+" - "+$("#fromh").val()+":"+$("#fromm").val(),to = $("#to").val()+" - "+$("#toh").val()+":"+$("#tom").val(),reason = $("#reason").val(),loc = $("#loc").val();
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/paction.php?do=coupon",data:({code:code,show:show,describe:describe,percent:percent,from:from,to:to,limit:limit}),success:function(data) {
$(".loadbox").fadeOut(150);
if(data == 'e1') {
$("#error").text("הקוד שהוקש קצר/ארוך מידי.").slideDown(500);
}else{ 
if(data == 'e2') {
$("#error").text("יש לבחור האם להציג את הקופון בדף תשלום או לא.").slideDown(500);
}else{ 
if(data == 'e3') {
$("#error").text("התיאור שהוקש קצר/ארוך מידי.").slideDown(500);
}else{ 
if(data == 'e4') {
$("#error").text("אחוז ההנחה צריך להיות מספר בין 1 ל 100.").slideDown(500);
}else{ 
if(data == 'e5') {
$("#error").text("תאריך ההתחלה אינו תקין.").slideDown(500);
}else{ 
if(data == 'e6') {
$("#error").text("תאריך הסיום אינו תקין.").slideDown(500);
}else{ 
if(data == 'e7') {
$("#error").text("מגבלת השימושים צריכה להיות במספרים בלבד.").slideDown(500);
}else{ 
if(data == 'e8') {
$("#error").text("קוד קופון כזה כבר קיים במערכת, הכנס אחר.").slideDown(500);
}else{
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/coupons?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/coupons");
}
});
});
} } } } } } } } }
});
});
$(".del").click(function() {
var id = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/paction.php?do=delcoupon",data:({id:id}),success:function(data) {
$(".loadbox").fadeOut(150);
if(!data) {
$("#d"+id).parent().parent().fadeTo(200,0,function() { $(this).animate({"width":"0px","height":"0px"},200,function() { $(this).remove(); }); });
} }
});
});
$(".showc").buttonset();
});
</script>
<div class="title" style="background:#7d7d7d;">קופונים</div><div style="padding:10px;">
<div class="subtitle">הוסף קופון</div>
<form action='' method="POST">
<table>
<tr><td>קוד קופון:</td><td colspan="2"><input type="text" id="code"></td></tr>
<tr><td>הצג קופון בדף תשלום:</td><td colspan="2" class="showc">
<input type="radio" class="show" value="1" id="s1" name="radio"><label for="s1">כן</label>
<input type="radio" class="show" value="0" id="s0" name="radio"><label for="s0">לא</label>
</td></tr>
<tr><td>תיאור הקופון:</td><td colspan="2"><input type="text" id="describe"></td></tr>
<tr><td>אחוזי הנחה:</td><td colspan="2"><input type="text" id="percent"></td></tr>
<tr><td>תאריך התחלה:</td><td><input type="text" id="from"></td><td style="line-height:30px; vertical-align:middle;">שעה: 
Print;
$h = ""; $m = "";
for($i = 0; $i < 60; $i++) { 
if($i < 10) { $i = sprintf("%02s", $i); }
if($i <= 23) { $h .= '<option value="'.$i.'">'.$i.'</option>'; }
$m .= '<option value="'.$i.'">'.$i.'</option>';
}
echo '<select id="fromm">'.$m.'</select> : <select id="fromh">'.$h.'</select>';
echo <<<Print
</td></tr>
<tr><td>תאריך סיום:</td><td><input type="text" id="to"></td><td style="line-height:30px; vertical-align:middle;">שעה: 
Print;
$h = ""; $m = "";
for($i = 0; $i < 60; $i++) { 
if($i < 10) { $i = sprintf("%02s", $i); }
if($i <= 23) { $h .= '<option value="'.$i.'">'.$i.'</option>'; }
$m .= '<option value="'.$i.'">'.$i.'</option>';
}
echo '<select id="tom">'.$m.'</select> : <select id="toh">'.$h.'</select>';
echo <<<Print
</td></tr>
<tr><td>כמות מימושים:<div style="font-size:8pt;">להשאיר ריק אם ללא הגבלה</div></td><td colspan="2"><input type="text" id="limit"></td><tr>
</table>
<input type="submit" value="הוסף קופון" id="add"><div id="error"></div>
</form><BR>
<div class="subtitle">כל הקופונים</div>
Print;
$now = time();
$q3 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `deleted`='0' ORDER BY `id` DESC");
if($q3->num_rows > 0) {
while($jn = $q3->fetch_assoc()) {  $id = $jn['id']; $code = $jn['code']; $from = $jn['from']; $to = $jn['to']; $describe = $jn['describe']; $limit = $jn['limit']; $percent = $jn['percent'];  
if($from<$now&&$to>$now&&($limit>0||$limit=='')) { $ava = ' | <span style="color:green;">פעיל</span>'; }
$date = date('j.n.y', $from).'<div style="font-size:8pt;">'.date('H:i', $from).'</div>';
$until = date('j.n.y', $to).'<div style="font-size:8pt;">'.date('H:i', $to).'</div>';
$q93 = $mysqli->query("SELECT * FROM `zz_order` WHERE `coupon`='$id'");
$used = $q93->num_rows; 
if($limit == '') { $been='&infin;'; }else{
$been = $used+$limit; }
$from = date('j.n.y', $from); $to = date('j.n.y', $to);
echo <<<Print
<div style="background:white; width:250px; float:right; margin:20px 10px; border:1px solid #E5E5E5; text-align:center;">
<div style="background:#C90628; color:white; padding:12px 10px; font-size:12pt; font-weight:bold;">קוד קופון: $code</div>
<div style="padding:7px; font-size:10pt;"><div style="font-size:15pt;">$percent% הנחה</div>מ <div style="display:inline-block; vertical-align:middle; line-height:15px; margin:0 5px;">$date</div> עד <div style="display:inline-block;vertical-align:middle; line-height:15px;margin:0 5px;">$until</div><BR>$describe<BR>מומש <div style="display:inline;direction:ltr;">$used/$been</div> | <a href="$url/admin.php/editco/$id" style="text-decoration:none;">ערוך</a> | <a href="javascript:void(0);" style="color:red;text-decoration:none;" class="del noload" id="d$id">מחק</a> $ava</div>
</div>
Print;
$ava = '';
} }


}else{
if($do == closed) { 
if($_GET['from'] == 'js') { ob_end_clean(); }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
$.datepicker.regional['he'] = { closeText: 'סגור', prevText: '<הקודם', nextText: 'הבא>', currentText: 'היום', monthNames: ['ינואר','פברואר','מרץ','אפריל','מאי','יוני', 'יולי','אוגוסט','ספטמבר','אוקטובר','נובמבר','דצמבר'], monthNamesShort: ['1','2','3','4','5','6', '7','8','9','10','11','12'], dayNames: ['ראשון','שני','שלישי','רביעי','חמישי','שישי','שבת'], dayNamesShort: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], dayNamesMin: ['א\'','ב\'','ג\'','ד\'','ה\'','ו\'','ש\''], weekHeader: 'Wk', dateFormat: 'dd/mm/yy', firstDay: 0, isRTL: true, showMonthAfterYear: false, yearSuffix: ''};
$.datepicker.setDefaults($.datepicker.regional['he']);
$( "#from" ).datepicker({showAnim: "fadeIn",onClose: function( selectedDate ) { $( "#to" ).datepicker( "option", "minDate", selectedDate ); }
});
$( "#to" ).datepicker({showAnim: "fadeIn",onClose: function( selectedDate ) { $( "#from" ).datepicker( "option", "maxDate", selectedDate ); }
});
$("#submit").click(function(eventObject) {
eventObject.preventDefault();
var from = $("#from").val()+" - "+$("#fromh").val()+":"+$("#fromm").val(),to = $("#to").val()+" - "+$("#toh").val()+":"+$("#tom").val(),reason = $("#reason").val(),loc = $("#loc").val();
$(".loadbox").fadeIn(150);
$.ajax({type:"POST",url:"$url/paction.php?do=closed",data:({from:from,to:to,reason:reason,loc:loc}),success:function(data) {
$(".loadbox").fadeOut(150); 
if(data == 'e1') {
$("#error").text("תאריך או שעת התחלה אינם תקינים.").slideDown(500);
}else{
if(data == 'e2') {
$("#error").text("תאריך או שעת סיום אינם תקינים.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("הסיבה לסגירה קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("הסניף שהוקש אינו תקין.").slideDown(500);
}else{

$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/closed?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/closed");
}
});
});

} } } } }
});
});
$(".del").click(function() {
var id = $(this).attr("id").slice(1);
$.ajax({type:"POST",url:"$url/paction.php?do=delclose",data:({id:id}),success:function(data) {
if(data) { $("#d"+id).parent().slideUp(200,function() { $(this).remove(); }); }
}
});
});
});
</script>
<div class="title" style="background:#7d7d7d;">שעות פעילות</div><div style="padding:10px;">
<div class="subtitle">הוסף טווח זמן שבו העסק יהיה סגור</div>
<form action='' method="POST">
<table>
<tr><td>תאריך התחלה:</td><td><input type="text" id="from"></td><td style="line-height:30px; vertical-align:middle;">שעה: 
Print;
$h = ""; $m = "";
for($i = 0; $i < 60; $i++) { 
if($i < 10) { $i = sprintf("%02s", $i); }
if($i <= 23) { $h .= '<option value="'.$i.'">'.$i.'</option>'; }
$m .= '<option value="'.$i.'">'.$i.'</option>';
}
echo '<select id="fromm">'.$m.'</select> : <select id="fromh">'.$h.'</select>';
echo <<<Print
</td></tr>
<tr><td>תאריך סיום:</td><td><input type="text" id="to"></td><td style="line-height:30px; vertical-align:middle;">שעה: 
Print;
$h = ""; $m = "";
for($i = 0; $i < 60; $i++) { 
if($i < 10) { $i = sprintf("%02s", $i); }
if($i <= 23) { $h .= '<option value="'.$i.'">'.$i.'</option>'; }
$m .= '<option value="'.$i.'">'.$i.'</option>';
}
echo '<select id="tom">'.$m.'</select> : <select id="toh">'.$h.'</select>';
echo <<<Print
</td></tr>
<tr><td>סיבה לסגירה:</td><td colspan="2"><input type="text" id="reason"></td></tr>
<tr><td>סניף:</td><td colspan="2"><select id="loc"><option value="0">כל הסניפים</option>
Print;
$q89 = $mysqli->query("SELECT * FROM `zz_locations` ORDER BY `id`");
while($fi = $q89->fetch_assoc()) { $adress = $fi['adress']; $city = $fi['city']; $id = $fi['id'];  
echo '<option value="'.$id.'">'.$adress.', '.$city.'</option>';
}
echo <<<Print
</td></tr>
</table>
<input type="submit" value="הוסף" id="submit"><div id="error"></div>
</form><BR>
<div class="subtitle">כל שינויי הזמנים</div>
Print;
$now = time();
$q4 = $mysqli->query("SELECT * FROM `zz_closed` ORDER BY `loc`");
if($q4->num_rows == 0) {
echo '<div class="notice">לא נמצאו שינויי זמנים.</div>';
}else{
$lastloc = '';
while($dd = $q4->fetch_assoc()) {$id = $dd['id']; $date = $dd['date']; $until = $dd['until']; $reason = $dd['reason']; $loc = $dd['loc'];
if($date<$now&&$until>$now) { $ava = ' | <span style="color:green;">פעיל</span>'; }
$date = date('j.n.y', $date).'<div style="font-size:8pt;">'.date('H:i', $date).'</div>';
$until = date('j.n.y', $until).'<div style="font-size:8pt;">'.date('H:i', $until).'</div>';
if($lastloc != $loc) {
if($loc == 0) { $locs = 'כל הסניפים'; }else{ 
$q43 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$loc'");
$pg = $q43->fetch_assoc();  $city = $pg['city']; $adress = $pg['adress']; $locs = $city.'<BR>'.$adress; }
if($lastloc != '') { echo '</div>'; }
echo '<div style="background:white; width:180px; float:right; margin:20px 10px; border:1px solid #E5E5E5; text-align:center;">
<div style="background:#C90628; color:white; padding:12px 10px; font-size:12pt; font-weight:bold;">'.$locs.'</div>';
}
echo <<<Print
<div style="padding:7px; font-size:10pt; text-align:center; vertical-align:middle; line-height:15px;">
מ <div style="display:inline-block; vertical-align:middle; line-height:15px; margin:0 5px;">$date</div> עד <div style="display:inline-block;vertical-align:middle; line-height:15px;margin:0 5px;">$until</div><BR>
<b>סיבה:</b> $reason. $ava<BR><a href="javascript:void(0);" style="color:red; text-decoration:none;" class="del noload" id="d$id">מחק</a></div><hr>
Print;
$lastloc = $loc; $ava = '';
} echo '</div>'; }

echo '</div>';


}else{
if($do == editloc) {
if($_GET['from'] == 'js') { ob_end_clean(); }
$id = $_GET['id'];
echo '<div class="title" style="background:#7d7d7d;">עריכת סניף</div><div style="padding:10px;">';
$q11 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$id'");
if($q11->num_rows == 0) { 
echo '<div id="error" style="display:block;">לא נמצא כזה סניף.</div>';
}else{ 
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var work = [];
$(".open,.close").keyup(function() {
if($(this).val().length == 2) { $(this).val($(this).val()+":"); }
});
var id = $id;
$("#submit").click(function(eventObject) {
eventObject.preventDefault();
var adress = $("#adress").val(),city = $("#city").val(),phone = $("#phone").val();
for(i = 0; i < 7; i++) { var c = $("#c"+i).val(), o = $("#o"+i).val();
if(o == 'close' && c == 'close') { work[i] = 'close'; }else{
if(o != '' && c != '') { work[i] = o+" - "+c; }else{ work[i] = ''; } }
}
$(".loadbox").fadeIn(300); 
$.ajax({type:"POST",url:"$url/paction.php?do=editloc",data:({work:work,adress:adress,city:city,id:id,phone:phone}),success:function(data) {
$(".loadbox").fadeOut(150); 
if(data == 'e1') {
$("#error").text("הכתובת שהוקשה קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e2') {
$("#error").text("העיר שהוקשה קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("מספר הטלפון שהוקש לא תקין.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("יש למלא את כל שעות הפעילות.").slideDown(500);
}else{
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/loc?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/loc");
}
});
});
} } } } }
});
});

$(".close").click(function() {
var tid = $(this).attr("id").slice(2);
if($(this).is(":checked")) {
$("#o"+tid+",#c"+tid).attr('disabled',true).val("close");
}else{
$("#o"+tid+",#c"+tid).attr('disabled',false).val("");
}
});
});
</script>
Print;
$q80 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$id'");
while($of = $q80->fetch_assoc()) { $adress = $of['adress']; $city = $of['city']; $phone = $of['phone'];
echo <<<Print
<form action='' method="POST">
<table>
<tr><td>כתובת:</td><td><input type="text" id="adress" value="$adress"></td></tr>
<tr><td>עיר:</td><td><input type="text" id="city" value="$city"></td></tr>
<tr><td>מספר טלפון:</td><td><input type="text" id="phone" value="$phone"></td></tr>
<tr><td><b>שעות פעילות:</b></td></tr>
Print;
$days = array("ראשון","שני","שלישי","רביעי","חמישי","שישי","שבת");
for($i = 0; $i < 7; $i++) { $plusone = $i+1;
if($of['day'.$plusone] == 'close') {
echo '<tr><td>יום '.$days[$i].':</td><td><input type="text" id="o'.$i.'" class="open" placeholder="08:45" style="width:45px;" value="close" disabled> עד <input type="text" id="c'.$i.'" class="close" placeholder="21:55" style="width:45px;" value="close" disabled></td>
<td><label><input type="checkbox" id="cl'.$i.'" class="close" checked> סגור</label></td></tr>';
}else{
$work = explode(" - ",$of["day".$plusone]);
echo '<tr><td>יום '.$days[$i].':</td><td><input type="text" id="o'.$i.'" class="open" placeholder="08:45" style="width:45px;" value="'.$work[0].'"> עד <input type="text" id="c'.$i.'" class="close" placeholder="21:55" style="width:45px;" value="'.$work[1].'"></td>
<td><label><input type="checkbox" id="cl'.$i.'" class="close"> סגור</label></td></tr>';
}
}
echo <<<Print
</table><BR>
<input type="submit" value="ערוך" id="submit"><div id="error"></div>
</form><BR>
Print;
} }
}else{
if($do == loc) {
if($_GET['from'] == 'js') { ob_end_clean(); }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var work = [];
$(".open,.close").keyup(function() {
if($(this).val().length == 2) { $(this).val($(this).val()+":"); }
});
$("#submit").click(function(eventObject) {
eventObject.preventDefault();
var adress = $("#adress").val(),city = $("#city").val(),phone = $("#phone").val();
for(i = 0; i < 7; i++) { var c = $("#c"+i).val(), o = $("#o"+i).val();
if(o == 'close' && c == 'close') { work[i] = 'close'; }else{
if(o != '' && c != '') { work[i] = o+" - "+c; }else{ work[i] = ''; } }
}
$(".loadbox").fadeIn(300); 
$.ajax({type:"POST",url:"$url/paction.php?do=newloc",data:({work:work,adress:adress,phone:phone,city:city}),success:function(data) {
$(".loadbox").fadeOut(150); 
if(data == 'e1') {
$("#error").text("הכתובת שהוקשה קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e2') {
$("#error").text("העיר שהוקשה קצרה/ארוכה מידי.").slideDown(500);
}else{
if(data == 'e4') {
$("#error").text("מספר הטלפון שהוקש לא תקין.").slideDown(500);
}else{
if(data == 'e3') {
$("#error").text("יש למלא את כל שעות הפעילות.").slideDown(500);
}else{
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/loc?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/loc");
}
});
});
} } } } }
});
});
$(".locdel").click(function() {
var locid = $(this).attr("id").slice(3);
if(confirm("מחיקת הסניף תגרום למחיקת כל ההזמנות ממנו, אתה בטוח?")) {
$(".loadbox").fadeIn(300); 
$.ajax({type:"POST",url:"$url/paction.php?do=locdel",data:({id:locid}),success:function(data) {
$(".loadbox").fadeOut(150); 
if(data) { $("#del"+locid).parent().parent().fadeTo(200,0,function() { $(this).animate({"width":"0px","height":"0px"},200,function() { $(this).remove(); }); }); }
}
});
}
});
$(".close").click(function() {
var tid = $(this).attr("id").slice(2);
if($(this).is(":checked")) {
$("#o"+tid+",#c"+tid).attr('disabled',true).val("close");
}else{
$("#o"+tid+",#c"+tid).attr('disabled',false).val("");
}
});
});
</script>
<div class="title" style="background:#7d7d7d;">סניפים</div><div style="padding:10px;">
<div class="subtitle">הוסף סניף</div>
<form action='' method="POST">
<table>
<tr><td>כתובת:</td><td><input type="text" id="adress"></td></tr>
<tr><td>עיר:</td><td><input type="text" id="city"></td></tr>
<tr><td>מספר טלפון:</td><td><input type="text" id="phone"></td></tr>
<tr><td><b>שעות פעילות:</b></td></tr>
Print;
$days = array("ראשון","שני","שלישי","רביעי","חמישי","שישי","שבת");
for($i = 0; $i < 7; $i++) {
echo '<tr><td>יום '.$days[$i].':</td><td><input type="text" id="o'.$i.'" class="open" placeholder="08:45" style="width:45px;"> עד <input type="text" id="c'.$i.'" class="close" placeholder="21:55" style="width:45px;"></td><td><label><input type="checkbox" id="cl'.$i.'" class="close"> סגור</label></td></tr>';
}
echo <<<Print
</table><BR>
<input type="submit" value="הוסף" id="submit"><div id="error"></div>
</form><BR>
<div class="subtitle">כל הסניפים</div>
Print;
$q3 = $mysqli->query("SELECT * FROM `zz_locations` ORDER BY `city`");
while($dd = $q3->fetch_assoc()) { $locid = $dd['id']; $city = $dd['city']; $adress = $dd['adress'];  $phone = $dd['phone'];
$hedays = array("א'","ב'","ג'","ד'","ה'","ו'","ש'");
echo '<div style="background:white; width:180px; float:right; margin:10px; border:1px solid #E5E5E5; text-align:center;">
<div style="background:#C90628; color:white; padding:12px 10px; font-size:12pt; font-weight:bold;">'.$adress.'<BR>'.$city.'<div style="font-size:8pt;">'.$phone.'</div></div>
<div style="padding:7px;">';
for($i = 1; $i <= 7; $i++) {
echo 'יום '.$hedays[$i-1].': ';
if($dd['day'.$i] == 'close') { echo '<b>סגור</b><div style="clear:both;"></div>'; }else{ echo '<div style="direction:ltr;display:inline;">'.$dd['day'.$i].'</div><div style="clear:both;"></div>'; }
}
echo '</div><div style="padding:5px 10px; border-top: 1px solid #E5E5E5; font-size:10pt;word-break:break-all;">
<a href="'.$url.'/admin.php/editloc/'.$locid.'" style="font-weight:bold;text-decoration:none;">ערוך סניף</a> - <a href="javascript:void(0);" id="del'.$locid.'" style="color:red;font-weight:bold;text-decoration:none;" class="noload locdel">מחק סניף</a>
</div></div>';
}
echo '</div>';
}else{
if($do == orders) {
if($_GET['from'] == 'js') { ob_end_clean(); }
$g = $_GET['id']; $loc = $_GET['loc'];
if($g != 'new' && !is_numeric($g)) { $g = 'all'; }
if(!is_numeric($loc)) { $loc = 'all'; }
if($g == 'new') {
if($loc == 'all') {
$q14 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5'"); 
}else{
$q14 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' AND `loc`='$loc'"); 
}
}else{
if($loc == 'all') {
$q14 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='0'"); 
}else{
$q14 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='0' AND `loc`='$loc'");
}
}
$numcus = 0; $lid = 0;
if($q14->num_rows > 0) {$numcus = ceil($q14->num_rows/30);}
if($g == 'new') {
$q15 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' ORDER BY `id` DESC LIMIT 1");
}else{
$q15 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='0' ORDER BY `id` DESC LIMIT 1");
}
$jx = $q15->fetch_assoc();

if($q15->num_rows > 0) {$lid = $jx['id'];} 


echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
Print;
if($g == 'new' || $g == 'all') {
echo <<<Print

var pagew = 1, maxpw = $numcus, loadmore = 1, pro = 0, loaded = 0, x = 0, g = '$g',loc = '$loc',loded=0,nowpage = '$g';

$(".loadmore").click(function() {
if(loadmore == 1 && pro == 0) { pro = 1; 
$(".loadbox").stop().fadeIn(300);
x = pagew*30+loaded-loded;
$.ajax({type:"POST",url:"$url/paction.php?do=loadorder",data:({page:x,g:g,loc:loc}),success:function(data) {
$(".loadbox").stop().fadeOut(300);
$(".loadmore").slideUp(50);
$(data).hide().appendTo(".contex").slideDown(400,function() { $(".loadmore").show(); });
pagew++;
if(pagew == maxpw) { $(".loadmore").remove(); loadmore = 0; }
pro = 0;
}
});
}
});

function printorder(url) {
var printWindow = window.open( url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
printWindow.addEventListener('load', function(){ printWindow.print(); printWindow.close(); }, true);
}

$("body").on("click",".printorder",function() {
printorder("http://demo.pizzer.net/paction.php?do=orderprint&id="+$(this).attr("id").slice(1));
});

var lid = $lid, inpro = false;
function loadorder() {
if(inpro) {
$.ajax({type:"POST",url:"$url/paction.php?do=neworder",data:({id:lid,g:g,loc:loc}),success:function(data) {
if(data) { if(data.match(/--/g).length > 1) { boom = data.split('-/-');  }else{ boom = [data]; }
for(i = 0; i < boom.length; i++) { workas = boom[i];
newd = workas.split(/--/); lid = newd.shift();
if($("#me"+lid).length == 0) {
if($("#print:checked").val() == "1") { printorder("http://demo.pizzer.net/paction.php?do=orderprint&id="+lid); }
var audioElement = document.createElement('audio');
audioElement.setAttribute('src', '$url/images/neworder.mp3'); audioElement.play();
$(newd.join('--')).hide().prependTo(".contex").show("scale",300);
loaded++; $(".notice").hide(); inpro = false; 
} } } }
});
}
}
if(inter != 0) { clearInterval(inter);  }
if(!inpro) {
inter = setInterval(function() {
inpro = true; loadorder();
},1000);
}
Print;
}
$allowed = $_COOKIE['allowed'];
echo <<<Print

$("body").off('click',".statuschange").on("click",".statuschange",function() {
var oid = $(this).attr("id").slice(1);
$(".loadbox").stop().fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=schange",data:({oid:oid}),success:function(data) {
$(".loadbox").stop().fadeOut(300);
$("#info"+oid).html(data);
if(data.indexOf("green") > -1 && nowpage == 'new') { $("#info"+oid).parent().parent().delay(1000).hide("slide",{direction:"right"},300);  loded++;  }
}
});
});

$("body").off('click',".search").on("click",".search",function() {
var orderid = $("#orderid").val(), url = "$url/admin.php/orders/"+orderid;
if(!isNaN(parseInt(orderid))) {
$(".loadbox").fadeIn(300);  
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:url+"?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", url);
}
});
});
}
});
$('#orderid').keyup(function(e){
if(e.keyCode == 13) { $(".search").trigger( "click" ); }
});
$(".newmenu").hover(function() {
$(".submenu").stop().fadeIn(200);
},function() {
$(".submenu").stop().fadeOut(200);
});
$(".newmenu1").hover(function() {
$(".submenu1").stop().fadeIn(200);
},function() {
$(".submenu1").stop().fadeOut(200);
});

function blocked() { if("$allowed" != "1") { var popUp = window.open('','', 'width=0, height=0');
if (popUp == null || typeof(popUp)=='undefined') { alert('יש לאפשר חלונות קופצים.'); }else{ popUp.close();
$.ajax({type:"POST",url:"$url/paction.php?do=allowed"}); } }
}

$("#print").change(function() {
var print = $("#print:checked").val();
if(isNaN(print)) { print = 0; } 
$(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=print",data:({print:print}),success:function() {
$(".loadbox").fadeOut(150); 
if(print == "1") { blocked(); }
}
});
});
if("$autoprint" == "1") { $("#print").prop("checked",true); blocked(); }

});
</script>
<div class="title" style="background:#7d7d7d;">הזמנות</div><div style="padding:10px;">
<ul class="ordermenu">
<li class="newmenu" style="cursor:pointer;">כל ההזמנות<span style="font-size:8pt;">&#9660;</span> | 
<ul class="submenu" style="display:none;">
<a href="$url/admin.php/orders/all/"><li>כל הסניפים</li></a>
Print;
$q73 = $mysqli->query("SELECT * FROM `zz_locations`");
while($xp = $q73->fetch_assoc()) { $id = $xp['id']; $city = $xp['city']; $ad = $xp['adress'];
echo '<a href="'.$url.'/admin.php/orders/all/'.$id.'"><li>סניף '.$city.', '.$ad.'</li></a>';
}
echo <<<Print
</ul></li>
<li class="newmenu1" style="padding-bottom:10px;cursor:pointer;">הזמנות חדשות<span style="font-size:8pt;">&#9660;</span> | 
<ul class="submenu1" style="margin-right:70px;display:none;">
<a href="$url/admin.php/orders/new/"><li>כל הסניפים</li></a>
Print;
$q73 = $mysqli->query("SELECT * FROM `zz_locations`");
while($xp = $q73->fetch_assoc()) { $id = $xp['id']; $city = $xp['city']; $ad = $xp['adress'];
echo '<a href="'.$url.'/admin.php/orders/new/'.$id.'"><li>סניף '.$city.', '.$ad.'</li></a>';
}
echo <<<Print
</ul></li>
<li><input type="text" id="orderid" placeholder="חפש הזמנה לפי מספרה.." style="font-size:8pt;"> <a href="javascript:void(0);" style="font-size:10pt;" class="search noload">חפש</a></li>
</ul><BR> <label><input type="checkbox" name="print" id="print" value="1"> הדפס הזמנות חדשות אוטומטית</label><BR>
<a href="http://pizzer.net/help#print" class="noload" target="_blank" style="font-size:10pt;">מדריך איך להדפיס הזמנות ללא חלון קופץ</a>
<BR><div class="contex">
Print;
if($g == 'new') {
if($loc == 'all') {
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' ORDER BY `date` DESC,`id` DESC LIMIT 30";
}else{
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' AND `loc`='$loc' ORDER BY `date` DESC,`id` DESC LIMIT 30";
}
orderdet($mysqli,$q,$minbill,"admin",0);
}else{
if($g == 'all') {
if($loc == 'all') {
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' ORDER BY `date` DESC,`id` DESC LIMIT 30";
}else{
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `loc`='$loc' ORDER BY `date` DESC,`id` DESC LIMIT 30";
}
orderdet($mysqli,$q,$minbill,"admin",0);
}else{ 
if(strpos($g,'0') == 0) { $q = "SELECT * FROM `zz_order` WHERE `buyer`='$g' AND `status`>'0' ORDER BY `date` DESC"; orderdet($mysqli,$q,$minbill,"admin",0);
}else{ $q = "SELECT * FROM `zz_order` WHERE `id`='$g' AND `status`>'0'"; orderdet($mysqli,$q,$minbill,"adminall",0); }

} }


echo '<div style="clear:both;"></div></div>';
if($numcus > 1 && !is_numeric($g)) {
echo '<div class="loadmore">טען עוד הזמנות..</div>';
}
}else{
if($do == customers) {
if($_GET['from'] == 'js') { ob_end_clean(); }
if(is_numeric($_GET['id'])) { $sphone = $mysqli->real_escape_string($_GET['id']);
$q13 = $mysqli->query("SELECT * FROM `zz_buyers` WHERE `phone` LIKE '%$sphone%'"); }else{
$q13 = $mysqli->query("SELECT * FROM `zz_buyers`"); }
$numcus = $q13->num_rows;

echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
var page = 20,maxp = "$numcus";

$("input").attr("autocomplete","off");
$("body").on("click",".edit",function() {
var tr = $(this).parent().parent(), id = $(this).attr("id").slice(1);
if(!$(".g"+id)[0]) {
tr.find(".name").html('<input type="text" value="'+tr.find(".name").html()+'" class="imini g'+id+'">');
tr.find(".phone").html('<input type="text" value="'+tr.find(".phone").html()+'" class="imini g'+id+'" style="width:80px;">');
tr.find(".street").html('<input type="text" value="'+tr.find(".street").html()+'" class="imini g'+id+'">');
tr.find(".housenum").html('<input type="text" value="'+tr.find(".housenum").html()+'" class="imini g'+id+'" style="width:20px;">');
tr.find(".city").html('<input type="text" value="'+tr.find(".city").html()+'" class="imini g'+id+'" style="width:30px;">');
$(this).removeClass("edit").addClass("endedit").text("סיים");
}
});
$("body").on("click",".endedit",function() {
var tr = $(this).parent().parent(), dthis = $(this),id = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(300); 
var name = tr.find(".g"+id).eq(0).val(),phone = tr.find(".g"+id).eq(1).val(),street = tr.find(".g"+id).eq(2).val(),housenum = tr.find(".g"+id).eq(3).val(),city = tr.find(".g"+id).eq(4).val();
$.ajax({type:"POST",url:"$url/paction.php?do=editcus",data:({name:name,phone:phone,street:street,housenum:housenum,city:city,id:id}),success:function(data) {
$(".loadbox").fadeOut(300); 
if(data == 'e1') {
alert("השם שהוקש קצר/ארוך מידי.");
}else{ 
if(data == 'e3') {
alert("מספר הפלאפון שהוקש לא תקין או שקיים כבר במערכת.");
}else{ 
if(data == 'e4') {
alert("הרחוב שהוקש קצר/ארוך מידי.");
}else{ 
if(data == 'e5') {
alert("מספר הבית שהוקש לא תקין.");
}else{ 
if(data == 'e6') {
alert("העיר שהוקשה קצרה/ארוכה מידי.");
}else{ 
tr.find(".name").html(name); 
tr.find(".phone").html(phone); tr.find(".street").html(street);
tr.find(".housenum").html(housenum); tr.find(".city").html(city);
dthis.addClass("edit").removeClass("endedit").text("ערוך");
} } } } } } 
});
});

$("body").off("click",".delete").on("click",".delete",function() {
if(confirm("מחיקת המשתמש תגרום למחיקת כל ההזמנות שלו.")) {
var id = $(this).attr("id").slice(1); $(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=deletecus",data:({id:id}),success:function() {
$(".loadbox").fadeOut(300);
$("#d"+id).parent().parent().fadeOut(300,function() { $(this).remove(); });
if(page > 0) { page--; }
}
});
}
});
var pro = 0;
function load() {
$(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=loadcus",data:({page:page}),success:function(data) {
$(".loadbox").fadeOut(300);
$(data).hide().appendTo(".cus").fadeIn(300);
page+=20;
if(page >= maxp) { $(".loadmore").remove(); }
}
});
}
$(window).scroll(function() {   
if($(window).scrollTop() + $(window).height() == $(document).height() && loadmore == 1 && pro == 0) {
load();
}
});

$(".loadmore").click(load);


$("body").off('click',".search").on("click",".search",function() {
var orderid = $("#uphone").val(), url = "$url/admin.php/customers/"+orderid;
if(!isNaN(parseInt(orderid))) {
$(".loadbox").fadeIn(300);  
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:url+"?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", url);
}
});
});
}
});
$('#uphone').keyup(function(e){
if(e.keyCode == 13) { $(".search").trigger( "click" ); }
});

});
</script>
<div class="title" style="background:#7d7d7d;">לקוחות</div><div style="padding:10px;">
<div class="subtitle">ניהול לקוחות <input type="text" id="uphone" placeholder="חפש לקוח לפי מספר פלאפון.." style="font-size:8pt;"> <a href="javascript:void(0);" style="font-size:10pt;" class="search noload">חפש</a></div>
<table class="cus">
<tr class="cushead"><td>שם מלא</td><td>מספר פלאפון</td><td>כתובת</td><td>שאלת אבטחה</td><td>תשובה לשאלה</td><td>אפשרויות</td></tr>
Print;
if($sphone!=Null) {
$q12 = $mysqli->query("SELECT * FROM `zz_buyers` WHERE  `phone` LIKE '%$sphone%' ORDER BY `id` DESC LIMIT 20");
}else{
$q12 = $mysqli->query("SELECT * FROM `zz_buyers` ORDER BY `id` DESC LIMIT 20");
}
if($q12->num_rows == 0) {
echo <<<Print
<tr><td colspan="6">אין לקוחות.</td></tr>
Print;
}
while($pl = $q12->fetch_assoc()) { $id = $pl['id']; $name = $pl['name']; $phone = $pl['phone']; $street = $pl['street']; $housenum = $pl['housenum']; $city = $pl['city']; $securea = $pl['securea']; $secureq = $pl['secureq'];
$q13 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$phone'"); $numorders = $q13->num_rows;
echo <<<Print
<tr><td><span class="name">$name</span></td><td><span class="phone">$phone</span></td><td><span class="street">$street</span> <span class="housenum">$housenum</span>, <span class="city">$city</span></td><td>$secureq</td><td>$securea</td><td><a href="javascript:void(0);" style="font-size:10pt; text-decoration:none;" class="edit noload" id="e$id">ערוך</a> - <a href="javascript:void(0);" style="font-size:10pt; color:red; text-decoration:none;" class="delete noload" id="d$id">מחק</a><BR><a href="$url/admin.php/orders/$phone" style="font-size:10pt; text-decoration:none;">הזמנות של הלקוח ($numorders)</a></td></tr>
Print;
}
echo '</table>';
if($numcus > 20) { echo '<BR><div class="loadmore">טען עוד לקוחות..</div>'; }
}else{
if($do == editcat) {
if($_GET['from'] == 'js') { ob_end_clean(); }
$id = $_GET['id'];
echo '<div class="title" style="background:#7d7d7d;">עריכת קטגוריה</div><div style="padding:10px;">';
$q11 = $mysqli->query("SELECT * FROM `zz_cat` WHERE `id`='$id'");
if($q11->num_rows == 0) { 
echo '<div id="error" style="display:block;">לא נמצאה כזו קטגוריה.</div>';
}else{
$xr = $q11->fetch_assoc(); $name = $xr['name']; $pimg = $xr['img']; $cimg = $xr['img'];
if(strrpos($pimg,"/gallery") > -1) { $pimg = 'בחר תמונה להעלאה..'; }

echo <<<Print
<script type="text/javascript">
$(document).ready(function() {

$('.editcat').ajaxForm({beforeSend:function() { $(".loadbox").fadeIn(300); $('.catload').append('<div class="percent">0%</div>').css("height","42px"); },uploadProgress: function(event, position, total, percentComplete) { 
$(".percent").text(percentComplete + '%');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150); $(".percent").remove(); $('.catload').css("height","32px"); $(".percent").remove();
if(xhr.responseText == 'e1') {
$("#error").text("השם שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
$("#error").slideUp(500);
$(".loadbox").fadeIn(300); 
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/cat?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/cat");
}
});
});
} } }); 
});
</script>

<form action="$url/paction.php?do=editcat" method="post" enctype="multipart/form-data" class="editcat">
<input type="hidden" value="$id" name="id">
<table>
<tr><td>שם הקטגוריה:</td><td><input type="text" name="name" value="$name"></td></tr>
</table><BR>
<input type="submit" name="edit" id="edit" value="ערוך קטגוריה"><div id="error"></div>
</form>
Print;
}
}else{
if($do == editpro) { 
if($_GET['from'] == 'js') { ob_end_clean(); }
$id = $_GET['id'];
echo '<div class="title" style="background:#7d7d7d;">עריכת מוצר</div><div style="padding:10px;">';
$q10 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$id' AND `show`='1'");
if($q10->num_rows == 0) { 
echo '<div id="error" style="display:block;">לא נמצא כזה מוצר.</div>';
}else{
$xr = $q10->fetch_assoc(); $name = $xr['name']; $price = $xr['price']; $half = $xr['half']; $type = $xr['type']; $needad = $xr['needad']; $pimg = $xr['img']; $cimg = $xr['img']; $des = $xr['des'];
if(strrpos($pimg,"/gallery") > -1) { $pimg = 'בחר תמונה להעלאה..'; }
if(is_numeric($needad)) { $needad = array($needad); }else{ if($needad != '{0}') { $needad = explode(",",$needad); } }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {

$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
$("input").attr("autocomplete","off");
$(".radio").buttonset();
$(".half").val("$half");
$(".half").data("selectBox-selectBoxIt").refresh();
$("#img").change(function() {
var finfo = this.files[0];
$(".choose").fadeOut(200,function() { $(this).text(finfo.name).fadeIn(200); });
});
$(".addons").change(function() {
if($(this).attr("id").slice(5) == 'all') {
$(".addons").prop('checked',this.checked);
$(".addons").button("refresh");
}else{
if($("#addonall").is(":checked")) {
$(".addons").prop('checked',false);
$(this).prop('checked', true);
$(".addons").button("refresh");
} }
});
$('.editpro').ajaxForm({beforeSend:function() { $(".loadbox").fadeIn(300); $('.catload').append('<div class="percent">0%</div>').css("height","42px"); },uploadProgress: function(event, position, total, percentComplete) { 
$(".percent").text(percentComplete + '%');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150); $(".percent").remove(); $('.catload').css("height","32px"); $(".percent").remove();
if(xhr.responseText == 'e1') {
$("#error").text("השם שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e6') {
$("#error").text("התיאור שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e2') {
$("#error").text("המחיר שהוקש לא תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e3') {
$("#error").text("הבחירה של המיון של התוספות אינה תקינה.").slideDown(500);
}else{
if(xhr.responseText == 'e4') {
$("#error").text("הקטגוריה שנבחרה אינה תקינה.").slideDown(500);
}else{
if(xhr.responseText == 'e5') {
$("#error").text("ניתן להעלות רק תמונות מסוג jpg,bmp,png,gif.").slideDown(500);
}else{
$("#error").slideUp(500);
$(".loadbox").fadeIn(300); 
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/pro?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/pro");
}
});
});
} } } } } } } }); 
$(".imgs").tooltip({show: {effect: "fade", duration: 200},hide: {effect: "fade", duration: 200},content:function() { return $(this).prop('title'); } });

});
</script>
<form action="http://demo.pizzer.net/paction.php?do=editpro" method="post" enctype="multipart/form-data" class="editpro">
<table>
<input type="hidden" value="$id" name="id">
<tr><td>שם המוצר:</td><td><input type="text" name="name" value="$name"></td></tr>
<tr><td>תיאור המוצר:<div style="font-size:8pt;width:100px;">השאר ריק כדי לא להוסיף תיאור.</div></td><td><input type="text" name="des" value="$des"></td></tr>
<tr><td>מחיר:</td><td><input type="text" name="price" value="$price"></td></tr>
<tr><td>מיון תוספות:<div style="font-size:8pt;width:100px;">מאפשר הוספת תוספות למוצר כשלם או תוספות לחצאי מוצר (לדוגמא פיצה).</div></td><td><select name="half" class="half" style="width:168px;"><option value="1">חצי חצי</option><option value="0">שלם</option></select></td></tr>
<tr><td>קטגוריה:<div style="font-size:8pt;width:100px;">בחר את הקטגוריה של המוצר. קטגורית תוספות הינה תוספות למוצרים נבחרים.</div></td><td><div class="radio">
Print;
if($type == 0) {
echo '<input type="radio" value="0" id="cat0" name="cat" checked>';
}else{
echo '<input type="radio" value="0" id="cat0" name="cat">';
}
echo <<<Print
<label for="cat0">
<div class="cat"><div class="catdes">
תוספות
</div></div></label>
Print;
$q5 = $mysqli->query("SELECT * FROM `zz_cat`");
while($xt = $q5->fetch_assoc()) { $id = $xt["id"]; $name = $xt["name"]; $img = $xt["img"];
if($id == $type) {
echo '<input type="radio" value="'.$id.'" id="cat'.$id.'" name="cat" checked>';
}else{
echo '<input type="radio" value="'.$id.'" id="cat'.$id.'" name="cat">';
}
echo <<<Print
<label for="cat$id">
<div class="cat"><div class="catdes">
$name
</div></div></label>
Print;
}
echo <<<Print
</div></td></tr>
<tr><td>תוספות:<div style="font-size:8pt;width:100px;">בחר את התוספות שיהיה ניתן להוסיף למוצר. השאר ריק כדי לא לאפשר הוספת תוספות. </div></td><td><div class="radio">
Print;
if($check == 1 || $needad == '{0}') {
echo '<input type="checkbox" value="all" id="addonall" name="addon[]" class="addons" checked>';
}else{
echo '<input type="checkbox" value="all" id="addonall" name="addon[]" class="addons">';
}
echo <<<Print
<label for="addonall">
<div class="addon"><div class="catdes">
<img style="width:48px; height:48px; border-radius:100px; text-align:center;" src="$url/images/complete.png"><BR>
הכל
</div></div></label>
Print;
$q6 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `show`='1'");
while($xy = $q6->fetch_assoc()) { $id = $xy["id"]; $name = $xy["name"]; $img = $xy["img"]; $check = 0;
if($needad != '{0}') {
for($i = 0; $i < sizeof($needad); $i++) {
if($needad[$i] == $id) { $check = 1; break; }
} }
if($check == 1 || $needad == '{0}') {
echo '<input type="checkbox" value="'.$id.'" id="addon'.$id.'" name="addon[]" class="addons" checked>';
}else{
echo '<input type="checkbox" value="'.$id.'" id="addon'.$id.'" name="addon[]" class="addons">';
}
echo <<<Print
<label for="addon$id">
<div class="addon"><div class="catdes">
<img style="width:48px; height:48px; border-radius:100px; text-align:center;" src="$url/images/uploads/$img">
$name
</div></div></label>
Print;
}
echo <<<Print
</div></td></tr>
<tr><td>תמונת המוצר:</td><td><div class="file" style="width:160px;overflow:hidden;"><div class="choose">$pimg</div><input type="file" name="img" id="img" style="opacity:0;width:160px;"></div> <b>-- או --</b> <div class="radio">
Print;
$q110 = $mysqli->query("SELECT * FROM `zz_gallery` ORDER BY `id` DESC");
while($qd = $q110->fetch_assoc()) { $id = $qd['id']; $image = $qd['image'];
if($pimg == 'בחר תמונה להעלאה..' && '/gallery/'.$image == $cimg) {
echo '<input type="radio" class="imgs" value="'.$image.'" id="img'.$id.'" name="cimg" checked>';
}else{
echo '<input type="radio" class="imgs" value="'.$image.'" id="img'.$id.'" name="cimg">';
}
echo <<<Print
<label for="img$id"><img style="width:40px; height:40px; border-radius:100px;" class="imgs" src="$url/images/uploads/gallery/$image" title='<img src="$url/images/uploads/gallery/$image" style="max-width:300px; max-height:400px;">'></label>
Print;
}
echo <<<Print
</div></td></tr>
</table><BR>
<input type="submit" name="add" id="add" value="ערוך מוצר"><div id="error"></div>
</form><BR>
Print;
}
}else{
if($do == cat) {
if($_GET['from'] == 'js') { ob_end_clean(); }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$('.newcat').ajaxForm({beforeSend:function() { $(".loadbox").fadeIn(300); $('.catload').append('<div class="percent">0%</div>').css("height","42px"); },uploadProgress: function(event, position, total, percentComplete) { 
$(".percent").text(percentComplete + '%');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150);  $(".percent").remove(); $('.catload').css("height","32px");
if(xhr.responseText == 'e1') {
$("#error").text("השם שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
$("#error").slideUp(500);
$("#name").val('');
$(".choose").text("בחר תמונה להעלאה..");
$(".loadbox").fadeIn(300); $(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/cat?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/cat");
}
});
});
} } }); 
$(".deletecat").click(function() {
var id = $(this).attr("id").slice(1);
$(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=deletecat",data:({id:id}),success:function() {
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/cat?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/cat");
}
});
});
}
});
});
});
</script>
<div class="title" style="background:#7d7d7d;">קטגוריות</div><div style="padding:10px;">
<div class="subtitle">הוסף קטגוריה חדשה</div>
<form action="$url/paction.php?do=newcat" method="post" enctype="multipart/form-data" class="newcat">
<table>
<tr><td>שם הקטגוריה:</td><td><input type="text" name="name" id="name"></td></tr>
</table><BR>
<input type="submit" name="add" id="add" value="הוסף קטגוריה"><div id="error"></div>
</form><BR>
<div class="subtitle">כל הקטגוריות</div>
Print;
$q8 = $mysqli->query("SELECT * FROM `zz_cat` ORDER BY `id` DESC");
while($pw = $q8->fetch_assoc()) { $id = $pw['id']; $name = $pw['name']; $img = $pw['img']; 
echo <<<Print
<div style="width:120px; float:right; text-align:center; margin:10px;">
<div style="font-size:15pt;color:#D77B31;">$name</div>
<a href="$url/admin.php/editcat/$id" style="font-size:10pt; text-decoration:none;">ערוך</a> - <a href="javascript:void(0);" style="font-size:10pt; color:red; text-decoration:none;" class="deletecat noload" id="d$id">מחק</a>
</div>
Print;
}
echo '<div style="clear:both;"></div><BR>';
}else{
if($do == pro) {
if($_GET['from'] == 'js') { ob_end_clean(); }
echo <<<Print
<script type="text/javascript">
$(document).ready(function() {
$("select").selectBoxIt({showEffect: "slideDown",showEffectSpeed: 300,hideEffect: "slideUp",hideEffectSpeed: 300});
$("input").attr("autocomplete","off");
$(".radio").buttonset();
$("select").data("selectBox-selectBoxIt").refresh();
$("#img").change(function() {
var finfo = this.files[0];
$(".choose").fadeOut(200,function() { $(this).text(finfo.name).fadeIn(200); });
});
$(".addons").change(function() {
if($(this).attr("id").slice(5) == 'all') {
$(".addons").prop('checked',this.checked);
$(".addons").button("refresh");
}else{
if($("#addonall").is(":checked")) {
$(".addons").prop('checked',false);
$(this).prop('checked', true);
$(".addons").button("refresh");
} }
});
$('.newpro').ajaxForm({beforeSend:function() { $(".loadbox").fadeIn(300); $('.catload').append('<div class="percent">0%</div>').css("height","42px"); },uploadProgress: function(event, position, total, percentComplete) { 
$(".percent").text(percentComplete + '%');
},complete: function(xhr) { 
$(".loadbox").fadeOut(150);  $(".percent").remove(); $('.catload').css("height","32px");
if(xhr.responseText == 'e1') {
$("#error").text("השם שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e6') {
$("#error").text("התיאור שהוקש קצר/ארוך מידי.").slideDown(500);
}else{
if(xhr.responseText == 'e2') {
$("#error").text("המחיר שהוקש לא תקין.").slideDown(500);
}else{
if(xhr.responseText == 'e3') {
$("#error").text("הבחירה של המיון של התוספות אינה תקינה.").slideDown(500);
}else{
if(xhr.responseText == 'e4') {
$("#error").text("הקטגוריה שנבחרה אינה תקינה.").slideDown(500);
}else{
if(xhr.responseText == 'e5') {
$("#error").text("ניתן להעלות רק תמונות מסוג jpg,bmp,png,gif.").slideDown(500);
}else{
$("#error").slideUp(500);
$("input[type=text],checkbox,radio").val("");
$("#img").val('');
$(".choose").text("בחר תמונה להעלאה..");
$(".loadbox").fadeIn(300); 
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/pro?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/pro");
}
});
});

} } } } } } } }); 

function delpro(id,type) {
$(".loadbox").fadeIn(300);
$.ajax({type:"POST",url:"$url/paction.php?do=deletepro",data:({id:id,type:type}),success:function() {
$("#deloption").dialog("close");
$(".contents").parent().fadeOut(300,function() {
$.ajax({url:"$url/admin.php/pro?from=js",success:function(data) {
$(".loadbox").fadeOut(150); 
$(".contents").html(data).parent().show("drop",{direction:"up"},500);
window.history.pushState("string", "title", "$url/admin.php/pro");
}
}); }); } });
}
$("#deloption").dialog({autoOpen:false,show:{effect:"clip",duration:300},hide:{effect:"clip",duration:300},modal: true });

$(".deletepro").click(function() {
var id = $(this).attr("id").slice(1);
$("#deloption").dialog({
buttons:{ " מחיקת מוצר ומחיקת כל ההזמנות" : function() { delpro(id,0); },"הסתרת מוצר" : function() { delpro(id,1); },
"הסתרת מוצר ומחיקת ההזמנות שבתהליך" : function() { delpro(id,2); }, "בטל" : function() { $(this).dialog("close"); }
} });
$("#deloption").dialog("open");
});

$(".imgs").tooltip({show: {effect: "fade", duration: 200},hide: {effect: "fade", duration: 200},content:function() { return $(this).prop('title'); } });
});
</script>
<div class="title" style="background:#7d7d7d;">מוצרים</div><div style="padding:10px;">
<div class="subtitle">הוסף מוצר חדש</div>
<form action="$url/paction.php?do=up" method="post" enctype="multipart/form-data" class="newpro">
<table>
<tr><td>שם המוצר:</td><td><input type="text" name="name"></td></tr>
<tr><td>תיאור המוצר:<div style="font-size:8pt;width:100px;">השאר ריק כדי לא להוסיף תיאור.</div></td><td><input type="text" name="des"></td></tr>
<tr><td>מחיר:</td><td><input type="text" name="price"></td></tr>
<tr><td>מיון תוספות:<div style="font-size:8pt;width:100px;">מאפשר הוספת תוספות למוצר כשלם או תוספות לחצאי מוצר (לדוגמא פיצה).</div></td><td><select name="half" style="width:168px;"><option value="1">חצי חצי</option><option value="0">שלם</option></select></td></tr>
<tr><td>קטגוריה:<div style="font-size:8pt;width:100px;">בחר את הקטגוריה של המוצר. קטגורית תוספות הינה תוספות למוצרים נבחרים.</div></td><td><div class="radio">
<input type="radio" value="0" id="cat0" name="cat"><label for="cat0">
<div class="cat"><div class="catdes">
תוספות
</div></div></label>
Print;
$q5 = $mysqli->query("SELECT * FROM `zz_cat`");
while($xt = $q5->fetch_assoc()) { $id = $xt["id"]; $name = $xt["name"]; $img = $xt["img"]; 
echo <<<Print
<input type="radio" value="$id" id="cat$id" name="cat"><label for="cat$id">
<div class="cat"><div class="catdes">
$name
</div></div></label>
Print;
}
echo <<<Print
</div></td></tr>
<tr><td>תוספות:<div style="font-size:8pt;width:100px;">בחר את התוספות שיהיה ניתן להוסיף למוצר. השאר ריק כדי לא לאפשר הוספת תוספות. </div></td><td><div class="radio">
<input type="checkbox" value="all" id="addonall" name="addon[]" class="addons"><label for="addonall">
<div class="addon"><div class="catdes">
<img style="width:48px; height:48px; border-radius:100px; text-align:center;" src="$url/images/complete.png"><BR>
הכל
</div></div></label>
Print;
$q6 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `show`='1'");
while($xy = $q6->fetch_assoc()) { $id = $xy["id"]; $name = $xy["name"]; $img = $xy["img"];
echo <<<Print
<input type="checkbox" value="$id" id="addon$id" name="addon[]" class="addons"><label for="addon$id">
<div class="addon"><div class="catdes">
<img style="width:48px; height:48px; border-radius:100px; text-align:center;" src="$url/images/uploads/$img">
$name
</div></div></label>
Print;
}
echo <<<Print
</div></td></tr>
<tr><td>תמונת המוצר:</td><td><div class="file" style="width:160px;overflow:hidden;"><div class="choose">בחר תמונה להעלאה..</div><input type="file" name="img" id="img" style="opacity:0;width:160px;"></div> <b>-- או --</b> <div class="radio">
Print;
$q110 = $mysqli->query("SELECT * FROM `zz_gallery` ORDER BY `id` DESC");
while($qd = $q110->fetch_assoc()) { $id = $qd['id']; $image = $qd['image'];
echo <<<Print
<input type="radio" value="$image" id="img$id" name="cimg"><label for="img$id"><img style="width:40px; height:40px; border-radius:100px;" class="imgs" src="$url/images/uploads/gallery/$image" title='<img src="$url/images/uploads/gallery/$image" style="max-width:300px; max-height:400px;">'></label>
Print;
}
echo <<<Print
</div>
</td></tr>
</table><BR>
<input type="submit" name="add" id="add" value="הוסף מוצר"><div id="error"></div>
</form><BR>
<div class="subtitle">כל המוצרים</div>
<div id="deloption" title="בחר את אפשרות המחיקה">
<p><b>מחיקת מוצר ומחיקת כל ההזמנות:</b> מוחק את המוצר ואת כל ההזמנות שמכילות את המוצר הזה.
<BR><b>הסתרת מוצר:</b> מסתיר את המוצר מרשימת המוצרים, אבל לא מוחק אותו ולא את ההזמנות. 
<BR><b>הסתרת מוצר ומחיקת ההזמנות שבתהליך:</b> מסתיר את המוצר ומוחק את ההזמנות שמכילות אותו שבתהליך יצירת עגלת קניות. כל ההזמנות שבוצעו כבר בעבר ישארו כפי שהם.</p>
</div>
Print;
$q7 = $mysqli->query("SELECT * FROM `zz_products` WHERE `show`='1' ORDER BY `id` DESC");
while($pl = $q7->fetch_assoc()) { $id = $pl['id']; $name = $pl['name']; $type = $pl['type']; $price = $pl['price']; $img = $pl['img']; $needad = $pl['needad']; $half = $pl['half'];
$des = $pl['des'];
if(mb_strlen($des,'UTF-8') > 25) { $des = mb_substr($des,0,25,'UTF-8').'..'; }
if($half == 1) { $half = 'חצי חצי'; }else{ $half = 'שלם'; }
if($type == 0) { $type = 'תוספות'; }else{
$q8 = $mysqli->query("SELECT * FROM `zz_cat` WHERE `id`='$type'");
$xo = $q8->fetch_assoc(); $type = $xo['name'];
}
if($needad == '{0}') { $needad = 'הכל'; }else{
if($needad == 0) { $needad = 'אין'; }else{
if(is_numeric($needad)) { $add = array($needad); }else{ $add = explode(",",$needad); } $needad = '<div style="font-size:8pt;width:80px;word-wrap: break-word;">';
for($i = 0; $i < sizeof($add); $i++) {
$q9 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$add[$i]'");
$xy = $q9->fetch_assoc(); $aname = $xy['name']; $needad .= $aname.',';
} $needad = substr($needad,0,-1); $needad .= '</div>'; } }
echo <<<Print
<div style="width:180px; height:230px; float:right; text-align:center; margin:10px;">
<img src="$url/images/uploads/$img" style="border-radius:100px; width:70px; height:70px;"><div style="font-size:15pt;color:#D77B31;">$name</div><div style="font-size:8pt;">$des</div>
<table style="background:#F1F1F1; padding:5px; border: 1px solid #E5E5E5; margin:auto; font-size:10pt;">
<tr><td style="font-weight:bold;">קטגוריה:</td><td>$type</td></tr>
<tr><td style="font-weight:bold;">מחיר:</td><td>$price ₪</td></tr>
Print;
if($needad != 'אין') {
echo <<<Print
<tr><td style="font-weight:bold;">תוספות:</td><td>$needad</td></tr>
<tr><td style="font-weight:bold;">מיון תוספות:</td><td>$half</td></tr>
Print;
}
echo <<<Print
</table>
<a href="$url/admin.php/editpro/$id" style="font-size:10pt; text-decoration:none;">ערוך</a> - <a href="javascript:void(0);" style="font-size:10pt; color:red; text-decoration:none;" class="deletepro noload" id="d$id">אפשרויות מחיקה</a>
</div>
Print;
}
echo '<div style="clear:both;"></div><BR>';
}else{
if($_GET['from'] == 'js') { ob_end_clean(); }
echo <<<Print
<div class="title" style="background:#7d7d7d;">דף ראשי</div><div style="padding:10px;">
<div class="subtitle">ברוך הבא למערכת</div><div style="font-size:10pt;">
מערכת <b>Pizzer</b> מאפשרת לך לנהל את הזמנות הפיצריה שלך ולתת ללקוחות ולך לעקוב אחר ההזמנות. <BR>
בפאנל הניהול תוכלו לנהל את המערכת כולה, כמו הוספת מוצרים, קטגוריות של מוצרים, תוספות למוצרים, ניהול לקוחות, הזמנות, סניפים, שעות פעילות מיוחדות (כמו חגים) בהם הסניף סגור, ליצר קופונים, מבצעים, להוסיף עמודים מותאמים אישית לאתר, לצפות אחר התחברויות במערכת, ולנהל את הגדרות האתר. <BR>
במידה וקיימת בעיה כלשהי, יש לכם שאלה, רעיון או כל דבר אחר, ניתן ליצור איתנו קשר <a href="http://pizzer.net/contact" target="_blank" class="noload">כאן</a>.
</div><BR>
Print;
$user = $_SERVER['HTTP_HOST']; 
$sql = file_get_contents('http://pizzer.net/info.php?do=sql&user='.$user.'&pw='.$sitepw.'&get=left');
if($sql != Null) { echo '<div class="notice">נשארו עוד '.$sql.' ימים עד לתוקף הרישיון. <BR><a href="http://pizzer.net/account" target="_blank" class="noload" style="color:white;">חדש עכשיו</a></div>'; }
echo <<<Print
<div class="subtitle">עדכונים</div>
<div style="font-size:14pt;float:right; margin:1px 0 0 5px;">Pizzer v$version</div><div style="float:right;"><img src="$url/images/icon.png" style="width:35px; height:25px;"></div><div style="clear:both;"></div>
Print;
$lastversion = file_get_contents('http://pizzer.net/info.php?do=lastversion');
if($version == $lastversion) {
echo '<span style="font-size:10pt;">אתה משתמש בגירסא האחרונה של המערכת.</span>';
}else{
echo '<a href="'.$url.'/admin.php/update" style="font-size:10pt;color:#AD6969;">עדכן את המערכת</a><div style="font-size:10pt;">במידה ולא תעדכן את המערכת בזמן, היא תהיה חשופה לבאגים ובעיות.</div>';
}
echo <<<Print

<BR><BR>
<div class="subtitle">סטטיסטיקות</div>
Print;
$q2 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`>'0'"); $earn = 0;
while($x = $q2->fetch_assoc()) { $id = $x['id']; $earn += orderprice($mysqli,$id); }
$numorders = $q2->num_rows; $earn = number_format($earn);
$q3 = $mysqli->query("SELECT * FROM `zz_buyers`");
$numbuyers = $q3->num_rows;
$q4 = $mysqli->query("SELECT * FROM `zz_enters`");
$enters = $q4->num_rows; 
echo <<<Print
<div class="st">
<div style="font-size:16pt;font-weight:bold;">$numorders</div>
הזמנות
</div>
<div class="st">
<div style="font-size:16pt;font-weight:bold;">$numbuyers</div>
לקוחות
</div>
<div class="st">
<div style="font-size:16pt;font-weight:bold;">$earn ₪</div>
רווח מהזמנות
</div>
<div class="st">
<div style="font-size:16pt;font-weight:bold;">$enters</div>
כניסות לאתר
</div>
<div style="clear:both;"></div>
<BR>
<div class="subtitle">ניסיונות התחברות אחרונים</div>
Print;
$q4 = $mysqli->query("SELECT * FROM `zz_adminlogs` ORDER BY `id` DESC LIMIT 9");
while($xk = $q4->fetch_assoc()) { $ip = $xk['ip']; $date = $xk['date']; $user = $xk['user']; $pass = $xk['pass'];
echo <<<Print
<div class="log"><div style="font-size:13pt;font-weight:bold;">$user : $pass</div>
<div style="font-size:12pt;">$ip</div>
<div style="font-size:10pt;">$date</div>
</div>
Print;
}
echo '<div style="clear:both;"></div>
</div>';
} } } } } } } } } } } } } } } } } } }
echo <<<Print
</div></div>
</div>
Print;

}else{
echo <<<Print
<div class="content" style="width:100%;">
<script type="text/javascript">
$(document).ready(function() {
$("#login").click(function(eventObject) {
eventObject.preventDefault();
$(".loadbox").fadeIn(300);
var user = $("#user").val(), pass = $("#pass").val();
$.ajax({type:"POST",url:"$url/paction.php?do=login",data:({user:user,pass:pass}),success:function(data) {
$(".loadbox").fadeOut(150);
if(data == 'e1') {
$("#error").text("שם משתמש או סיסמא שגויים.").slideDown(200);
}else{
window.location.reload();
} }
});
});
});
</script>
<div class="mblock">
<div class="title">התחבר</div><div style="padding:10px;">
<form action='' method="POST">
<table style="font-size:10pt;">
<tr><td>שם משתמש:</td><td><input type="text" id="user" autocomplete="off"></td></tr>
<tr><td>סיסמא:</td><td><input type="password" id="pass" autocomplete="off"></td></tr>
</table>
<input type="submit" value="התחברות" id="login" class="login" style="margin-top:5px;"><div id="error"></div>
</form>
</div></div></div>
Print;
}

?>