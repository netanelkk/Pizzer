<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once('config.php');
$post = $_GET['post']; $do = $_GET['do'];
if($post == 'admin') {
if (isset($_COOKIE['admin'])) {
$ch = $mysqli->query("SELECT * FROM `zz_settings`");
$pd = $ch->fetch_assoc(); $cc = md5($pd['user'].','.$pd['pass'].$pd['salt']);
$cuser = $_COOKIE['admin'];
if($cc == $cuser) {
$canlogin = 1; $adminlog = 1;
} }
if($adminlog != 1) { $canlogin = 0; }
if($do == dealbd && $canlogin == 1) {
$did = $_POST['did']; $uid = substr($_POST['uid'],1); $until = date('Y-m-d',strtotime("+1 month"));
$mysqli->query("INSERT INTO `zz_birthday`(`did`,`uid`,`until`) VALUES('$did','$uid','$until')");

}else{
if($do == allowed && $canlogin == 1) {
setcookie("allowed","1", time() + 86400000);
}else{
if($do == 'orderprint' && $canlogin == 1) {
$id = $_GET['id'];
$q = "SELECT * FROM `zz_order` WHERE `id`='$id'";
echo '<link rel="stylesheet" href="'.$url.'/style.css" type="text/css" media="all"/><style>body{direction:rtl;font-family:arial;}.crt{width:calc(100% - 40px)!important;}.boxtitle{background:lightgray!important;color:black!important;}.mafrid{border-top:8px solid gray!important;}</style>';
orderdet($mysqli,$q,$minbill,"adminall",0);
}else{
if($do == 'print' && $canlogin == 1) {
$print = $_POST['print'];
$mysqli->query("UPDATE `zz_settings` SET `autoprint`='$print' WHERE `id`='1'");
}else{
if($do == changebd && $canlogin == 1) {
$bd = $_POST['bd'];
$mysqli->query("UPDATE `zz_settings` SET `allowbd`='$bd' WHERE `id`='1'");
if($bd == 1) { echo 'open'; }
}else{
if($do == deltheme && $canlogin == 1) {
$id = $_POST['id'];
$q131 = $mysqli->query("SELECT * FROM `zz_style` WHERE `id`='$id'");
while($wq = $q131->fetch_assoc()) { $headbg = $wq['headbg']; $sitebg = $wq['sitebg']; }
if(substr_count($headbg,'.')) { unlink('images/uploads/'.$headbg); }
if(substr_count($sitebg,'.')) { unlink('images/uploads/'.$sitebg); }
$mysqli->query("DELETE FROM `zz_style` WHERE `id`='$id'");
if($id == $theme) {
$q130 = $mysqli->query("SELECT * FROM `zz_style` ORDER BY `id` DESC LIMIT 1");
$dm = $q130->fetch_assoc(); $newdefault = $dm['id'];
$mysqli->query("UPDATE `zz_settings` SET `theme`='$newdefault' WHERE `id`='1'");
}
}else{
if($do == loadtheme && $canlogin == 1) {
$id = $_POST['id'];
if($id == 'newv' || $id == 'news') {
if($id == 'newv') { $x = 'vertical'; }else{ $x = 'side'; }
$mysqli->query("INSERT INTO `zz_style`(`headbg`,`headcolor`,`sitebg`,`phonebg`,`phonecolor`,`type`) VALUES('#343131','white','#B18C5F','#C90628','white','$x')");
$id = $mysqli->insert_id;
$mysqli->query("UPDATE `zz_settings` SET `theme`='$id' WHERE `id`='1'");
}
$q125 = $mysqli->query("SELECT * FROM `zz_style` WHERE `id`='$id'");
while($dm = $q125->fetch_assoc()) { $type = $dm['type'];$headbg = $dm['headbg']; $headcolor = $dm['headcolor']; $sitebg = $dm['sitebg']; $style = $dm['type']; $phonebg = $dm['phonebg']; $phonecolor = $dm['phonecolor'];

if(substr_count($headbg,'.')) { $headbg = 'url('.$url.'/images/uploads/'.$headbg.')'; }
if(substr_count($sitebg,'.')) { $sitebg = 'url('.$url.'/images/uploads/'.$sitebg.') fixed no-repeat'; }
if($type == 'vertical') {
echo <<<Print
<div style="background:$sitebg; width:850px;" class="sitebg helpers" title="לחץ לשינוי הרקע">
<div style="width:600px; margin:auto;">
<div style="background:$headbg; width:580px; padding:10px; margin:auto;" class="headbg helpers" title="לחץ לשינוי רקע חלק עליון">
<div style="float:right;"><img src="$url/images/uploads/$logo" style="width:250px; height:80px;" class="logo helpers" title="לחץ לשינוי הלוגו"></div>
<ul class="vertical">
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
</ul>
<div style="clear:both;"></div>
</div>
<div style="background:white; width:600px; height:400px;" class="whitearea helpers" title="ללא שינוי">
<div class="call helpers" style="background:$phonebg; color:$phonecolor; width:200px; padding:5px; z-index:5; position:absolute; margin-top:-10px;
font-size:16pt; font-weight:bold; text-align:center; clear:both; border-radius:5px 0 0 5px;" title="לחץ לשינוי רקע של מספר טלפון"><div style="font-size:10pt; float:right;" >טלפון להזמנות</div><div style="clear:both;"></div>$orderphone</div>
</div></div>
</div>
Print;
}else{
echo <<<Print
<div style="background:$sitebg; width:850px;" class="sitebg helpers" title="לחץ לשינוי הרקע">
<div style="width:600px; margin:auto;">
<div style="background:$headbg; float:right; width:200px; padding:10px; height:380px; text-align:center;" class="headbg helpers" title="לחץ לשינוי רקע תפריט">
<div style="float:right;"><img src="$url/images/uploads/$logo" style="width:250px; height:80px; position:absolute;margin:5px -50px 0 0; padding:0;" class="logo helpers" title="לחץ לשינוי הלוגו"></div>
<ul class="side">
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
<li><a href="javascript:void(0);" style="color:$headcolor;" class="link noload helpers" title="לחץ לשינוי צבע הטקסט"><img src="$url/images/rightmenu.png" class="rmenu"> קישור <img src="$url/images/rightmenu.png" class="lmenu"></a></li>
</ul>
<div style="clear:both;"></div>
</div>
<div style="background:white; width:600px; height:400px;" class="whitearea helpers" title="ללא שינוי">
<div class="call helpers" style="background:$phonebg; color:$phonecolor; width:190px; position:absolute; margin-top:327px; z-index:10; padding:15px;
font-size:16pt; font-weight:bold; text-align:center;" title="לחץ לשינוי רקע של מספר טלפון"><div style="font-size:10pt; float:right;">טלפון להזמנות</div><div style="clear:both;"></div>$orderphone</div>
</div></div>
</div>
Print;
}
}
}else{
if($do == custom && $canlogin == 1) {
function uploadfile($fname,$ext,$file) { 
if(!($ext == "jpg" || $ext == "png" || $ext == "bmp" || $ext == "gif" || $ext == "jpeg")) { return 'no'; }
$newname = dirname(__FILE__).'/images/uploads/'.$fname.'.'.$ext; $i = 1; $imgname = $fname.'.'.$ext;
while(file_exists($newname)) { $newname = dirname(__FILE__).'/images/uploads/'.$fname.$i.'.'.$ext; $imgname = $fname.$i.'.'.$ext; $i++; }
move_uploaded_file($file['tmp_name'],$newname);
return $imgname; }
$headbgc = $_POST['headbgc']; $headcolor = $_POST['headcolor']; $phonebg = $_POST['phonebg']; $phonecolor = $_POST['phonecolor'];
$sitebgc = $_POST['sitebgc']; $themeid = $_POST['themeid'];

if($themeid != $theme) { $theme = $themeid; $mysqli->query("UPDATE `zz_settings` SET `theme`='$theme' WHERE `id`='1'"); }
if($headbgc != Null) {
$mysqli->query("UPDATE `zz_style` SET `headbg`='$headbgc' WHERE `id`='$theme'");
}else{
$headbg = pathinfo($_FILES['headbg']['name']);  $hbname = $headbg['filename']; $hbext = strtolower($headbg['extension']);
$imgname = uploadfile($hbname,$hbext,$_FILES['headbg']);
if($imgname != 'no') { $mysqli->query("UPDATE `zz_style` SET `headbg`='$imgname' WHERE `id`='$theme'"); } 
}
$logo = pathinfo($_FILES['logo']['name']);  $lname = $logo['filename']; $lext = strtolower($logo['extension']);
$imgname = uploadfile($lname,$lext,$_FILES['logo']);
if($imgname != 'no') { $mysqli->query("UPDATE `zz_settings` SET `logo`='$imgname' WHERE `id`='1'"); }
$siteimg = pathinfo($_FILES['siteimg']['name']);  $siname = $siteimg['filename']; $siext = strtolower($siteimg['extension']);
$imgname = uploadfile($siname,$siext,$_FILES['siteimg']);
if($imgname != 'no') { $mysqli->query("UPDATE `zz_style` SET `sitebg`='$imgname' WHERE `id`='$theme'"); }
if($sitebgc != Null) { $mysqli->query("UPDATE `zz_style` SET `sitebg`='$sitebgc' WHERE `id`='$theme'"); }
if($headcolor != Null) { $mysqli->query("UPDATE `zz_style` SET `headcolor`='$headcolor' WHERE `id`='$theme'"); }
if($phonebg != Null) { $mysqli->query("UPDATE `zz_style` SET `phonebg`='$phonebg' WHERE `id`='$theme'"); }
if($phonecolor != Null) { $mysqli->query("UPDATE `zz_style` SET `phonecolor`='$phonecolor' WHERE `id`='$theme'"); }

}else{
if($do == update && $canlogin == 1) {
file_put_contents("update.zip", fopen("http://pizzer.net/download.php?down=true&v=update", 'r'));
$zip = new ZipArchive;
if ($zip->open('update.zip') === TRUE) {
$zip->extractTo('./'); $zip->close();
if(file_exists('run.php')) { include 'run.php'; unlink('run.php'); }
$lastversion = file_get_contents('http://pizzer.net/info.php?do=lastversion');
$mysqli->query("UPDATE `zz_settings` SET `version`='$lastversion' WHERE `id`='1'");
echo 'success';
}else{
echo 'error';
}
unlink('update.zip');
}else{
if($do == logout && $canlogin == 1) {
setcookie("admin","", time() - 86400000);
header("location: ".$url."/admin.php");
}else{
if($do == settings && $canlogin == 1) {
$surl = $_POST['url']; $mail = $_POST['adminmail']; $paypal = $_POST['paypal']; $phone = $_POST['phone']; $name = $_POST['name'];
$minbill = $_POST['minbill']; $logtry = $_POST['logtry']; $logtime = $_POST['logtime']; $user = $_POST['user']; $pass = $_POST['pass'];
$pelp = $_POST['pelep']; $pelu = $_POST['peleu']; $pelm = $_POST['pelem']; $pw = $_POST['li']; $pcall = $_POST['paycall'];
if($pelep != '' && $pelp == '') { $pelp = $pelep; }
$filename = pathinfo($_FILES['logo']['name']); $fname = $filename['filename']; $ext = strtolower($filename['extension']); $deliver = $_POST['deliver'];
if(!($ext == "jpg" || $ext == "png" || $ext == "bmp" || $ext == "gif" || $ext == "jpeg") && $ext != '') {
echo 'e1';
}else{
if(!filter_var($surl, FILTER_VALIDATE_URL)) {
echo 'e2';
}else{
if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $mail)) {
echo 'e3';
}else{
if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $paypal) && $paypal != '') {
echo 'e4';
}else{
if(!($pelp == '' && $pelu == '' && $pelm == '') && !preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $pelm)) {
echo 'e13';
}else{
$client = new SoapClient("https://www.pelepay.co.il/pay/TrancAuthentication.asmx?wsdl");
$param = array('username'=>$pelu,'password'=>$pelp,'dealindex'=>'6575','confirmationcode'=>'0257440','amount'=>'3.00');
if($client->AuthenticateTransaction($param)->AuthenticateTransactionResult == 'wrong username or password' && ($pelp != '' || $pelu != '')) {
echo 'e15';
}else{
if(!is_numeric($pcall) && $pcall != '') {
echo 'e16';
}else{
if(mb_strlen($phone,'UTF-8') < 4 || mb_strlen($phone,'UTF-8') > 15) {
echo 'e5';
}else{
if(mb_strlen($name,'UTF-8') < 2 || mb_strlen($name,'UTF-8') > 20) {
echo 'e6';
}else{
if(!is_numeric($minbill)) {
echo 'e7';
}else{
if(!is_numeric($deliver)) {
echo 'e12';
}else{
if(!is_numeric($logtry)) {
echo 'e8';
}else{
if(!is_numeric($logtime)) {
echo 'e9';
}else{
if(mb_strlen($user,'UTF-8') < 5 || mb_strlen($user,'UTF-8') > 25) {
echo 'e10';
}else{
if((mb_strlen($pass, 'UTF-8') < 6 || mb_strlen($pass, 'UTF-8') > 18 || !(preg_match('/[A-Za-z]/', $pass) && preg_match('/[0-9]/', $pass))) && $pass != '') {
echo 'e11';
}else{
if($pw == Null) {
echo 'e14';
}else{
if($ext != Null) {
$newname = dirname(__FILE__).'/images/uploads/'.$fname.'.'.$ext; $i = 1; $imgname = $fname.'.'.$ext;
while(file_exists($newname)) { $newname = dirname(__FILE__).'/images/uploads/'.$fname.$i.'.'.$ext; $imgname = $fname.$i.'.'.$ext; $i++; }
move_uploaded_file($_FILES['logo']['tmp_name'],$newname);
$mysqli->query("UPDATE `zz_settings` SET `logo`='$imgname' WHERE `id`='1'");
}
if($pass != '') { $pass = md5($pass); $mysqli->query("UPDATE `zz_settings` SET `pass`='$pass' WHERE `id`='1'"); }
$mysqli->query("UPDATE `zz_settings` SET `url`='$surl',`paypal`='$paypal',`mail`='$mail',`phone`='$phone',`name`='$name',`minbill`='$minbill',`deliver`='$deliver',`logtry`='$logtry',`logtime`='$logtime',`user`='$user',`peleu`='$pelu',`pelem`='$pelm',`pelep`='$pelp',`pw`='$pw',`paycall`='$pcall' WHERE `id`='1'");
} } } } } } } } } } } } } } } }
}else{
if($do == clearlog && $canlogin == 1) {
$mysqli->query("DELETE FROM `zz_adminlogs`");
}else{
if($do == editpage && $canlogin == 1) {
$id = $_POST['id']; $title = $mysqli->real_escape_string($_POST['title']); $content = $mysqli->real_escape_string($_POST['content']); $link = $_POST['link'];
$checkcontent = strip_tags($content); $showm = $_POST['showm']; $showb = $_POST['showb'];
if(mb_strlen($title,'UTF-8') < 3 || mb_strlen($title,'UTF-8') > 25) {
echo 'e1';
}else{
if($checkcontent != '' && (mb_strlen($checkcontent,'UTF-8') < 2 || mb_strlen($checkcontent,"UTF-8") > 5000)) {
echo 'e2';
}else{
if($link != '' && !filter_var($link, FILTER_VALIDATE_URL)) {
echo 'e3';
}else{
if($content == '' && $link == '') {
echo 'e4';
}else{
if($link != '') { 
$mysqli->query("UPDATE `zz_pages` SET `title`='$title',`link`='$link',`content`='',`showm`='$showm',`showb`='$showb' WHERE `id`='$id'");
}else{
$mysqli->query("UPDATE `zz_pages` SET `title`='$title',`content`='$content',`link`='',`showm`='$showm',`showb`='$showb' WHERE `id`='$id'");
} 
} } } }
}else{
if($do == delpage && $canlogin == 1) {
$id = $_POST['id'];
$mysqli->query("DELETE FROM `zz_pages` WHERE `id`='$id'");
}else{ 
if($do == addpage && $canlogin == 1) {
$title = $mysqli->real_escape_string($_POST['title']); $content = str_replace('<span></span>','',$_POST['content']); $link = $_POST['link'];
$checkcontent = strip_tags($content); $showm = $_POST['showm']; $showb = $_POST['showb'];
if(mb_strlen($title,'UTF-8') < 3 || mb_strlen($title,'UTF-8') > 25) {
echo 'e1';
}else{
if($checkcontent != '' && (mb_strlen($checkcontent,'UTF-8') < 2 || mb_strlen($checkcontent,"UTF-8") > 5000)) {
echo 'e2';
}else{
if($link != '' && !filter_var($link, FILTER_VALIDATE_URL)) {
echo 'e3';
}else{
if($content == '' && $link == '') {
echo 'e4';
}else{
if($link != '') { 
$mysqli->query("INSERT INTO `zz_pages`(`title`,`link`,`showm`,`showb`) VALUES('$title','$link','$showm','$showb')");
}else{
$content = $mysqli->real_escape_string($content);
$mysqli->query("INSERT INTO `zz_pages`(`title`,`content`,`showm`,`showb`) VALUES('$title','$content','$showm','$showb')");
} 
} } } }
}else{

if($do == editdeal && $canlogin == 1) {
$id = $_POST['id']; $name = $mysqli->real_escape_string($_POST['name']); $price = $_POST['price']; $prod = $_POST['prod'];
$filename = pathinfo($_FILES['img']['name']); $fname = $filename['filename']; $ext = strtolower($filename['extension']); $bd = $_POST['bd'];
if(mb_strlen($name,'UTF-8') < 3 || mb_strlen($name,'UTF-8') > 50) {
echo 'e1';
}else{
if(!is_numeric($price)) {
echo 'e3';
}else{
if($prod == 'null') {
echo 'e4';
}else{
if(!($ext == "jpg" || $ext == "png" || $ext == "bmp" || $ext == "gif" || $ext == "jpeg") && $ext != Null) {
echo 'e5';
}else{
if($ext != Null) {
$newname = dirname(__FILE__).'/images/uploads/'.$fname.'.'.$ext; $i = 1; $imgname = $fname.'.'.$ext;
while(file_exists($newname)) { $newname = dirname(__FILE__).'/images/uploads/'.$fname.$i.'.'.$ext; $imgname = $fname.$i.'.'.$ext; $i++; }
move_uploaded_file($_FILES['img']['tmp_name'],$newname);
$mysqli->query("UPDATE `zz_deals` SET `img`='$imgname' WHERE `id`='$id'");
}
$mysqli->query("UPDATE `zz_deals` SET `name`='$name',`price`='$price',`products`='$prod',`bd`='$bd' WHERE `id`='$id'");
} } } }
}else{
if($do == deldeal && $canlogin == 1) {
$id = $_POST['id'];
$q99 = $mysqli->query("SELECT * FROM `zz_order`");
while($ow = $q99->fetch_assoc()) { 
if(strpos($ow['products'],'{'.$id.':') !== false) {
$mysqli->query("UPDATE `zz_deals` SET `deleted`='1' WHERE `id`='$id'");
die;
} }
$q100 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `id`='$id'");
$zo = $q100->fetch_assoc(); $img = $zo['img'];
unlink('images/uploads/'.$img);
$mysqli->query("DELETE FROM `zz_deals` WHERE `id`='$id'");
}else{
if($do == newdeal && $canlogin == 1) {
$name = $mysqli->real_escape_string($_POST['name']); $price = $_POST['price']; $prod = $_POST['prod']; $bd = $_POST['bd'];
$filename = pathinfo($_FILES['img']['name']); $fname = $filename['filename']; $ext = strtolower($filename['extension']);
if(mb_strlen($name,'UTF-8') < 3 || mb_strlen($name,'UTF-8') > 50) {
echo 'e1';
}else{
if(!is_numeric($price)) {
echo 'e3';
}else{
if($prod == 'null') {
echo 'e4';
}else{
if(!($ext == "jpg" || $ext == "png" || $ext == "bmp" || $ext == "gif" || $ext == "jpeg")) {
echo 'e5';
}else{
$newname = dirname(__FILE__).'/images/uploads/'.$fname.'.'.$ext; $i = 1; $imgname = $fname.'.'.$ext;
while(file_exists($newname)) { $newname = dirname(__FILE__).'/images/uploads/'.$fname.$i.'.'.$ext; $imgname = $fname.$i.'.'.$ext; $i++; }
move_uploaded_file($_FILES['img']['tmp_name'],$newname);
$mysqli->query("INSERT INTO `zz_deals`(`name`,`price`,`products`,`img`,`bd`) VALUES('$name','$price','$prod','$imgname','$bd')");
} } } }
}else{
if($do == checkaddons && $canlogin == 1) {
$sp = $_POST['selectedpro'];
if(strpos($sp,'{') !== false) {
$sp = str_replace("{", "", $sp); $sp = str_replace("}", "", $sp); $need = 0;
$q98 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='$sp'");
while($or = $q98->fetch_assoc()) { if($or['needad'] != 0 || strpos($or['needad'],'{') !== false) { echo '1'; die; } }
echo '0';
}else{
$q99 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$sp'");
$oe = $q99->fetch_assoc();
if($oe['needad'] != 0 || strpos($oe['needad'],'{') !== false) { echo '1'; }else{ echo '0'; }
}
}else{
if($do == editcoupon && $canlogin == 1) {
$code = $mysqli->real_escape_string($_POST['code']); $show = $_POST['show']; $describe = $mysqli->real_escape_string($_POST['describe']);
$from = $_POST['from']; $to = $_POST['to']; $limit = $_POST['limit']; $id = $_POST['id'];
if(mb_strlen($code,'UTF-8') < 3 || mb_strlen($reason,'UTF-8') > 25) {
echo 'e1';
}else{
if(!(strlen($show) == 1 && ($show == 0 || $show == 1))) {
echo 'e2';
}else{
if(mb_strlen($describe,'UTF-8') < 2 || mb_strlen($describe,'UTF-8') > 75) {
echo 'e3';
}else{
$date = DateTime::createFromFormat('d/m/Y - H:i', $from); $e = DateTime::getLastErrors();
if($e['error_count'] > 0) {
echo 'e5';
}else{
$fdate = $date->format('U');
$date = DateTime::createFromFormat('d/m/Y - H:i', $to); $e = DateTime::getLastErrors();
if($e['error_count'] > 0) {
echo 'e6';
}else{
if($limit != '' && !is_numeric($limit)) {
echo 'e7';
}else{
$q92 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `code`='$code' AND `deleted`='0' AND `id`!='$id'");
$codetimes = $q92->num_rows; 
if($codetimes > 0) {
echo 'e8';
}else{
$until = $date->format('U');
$mysqli->query("UPDATE `zz_coupons` SET `code`='$code',`show`='$show',`describe`='$describe',`from`='$fdate',`to`='$until',`limit`='$limit' WHERE `id`='$id'");
} } } } } } }
}else{
if($do == delcoupon && $canlogin == 1) {
$id = $_POST['id'];
$q94 = $mysqli->query("SELECT * FROM `zz_order` WHERE `coupon`='$id'");
if($q94->num_rows == 0) {
$mysqli->query("DELETE FROM `zz_coupons` WHERE `id`='$id'");
}else{
$mysqli->query("UPDATE `zz_coupons` SET `deleted`='1' WHERE `id`='$id'");
}
}else{
if($do == coupon && $canlogin == 1) {
$code = $mysqli->real_escape_string($_POST['code']); $show = $_POST['show']; $describe = $mysqli->real_escape_string($_POST['describe']); $percent = $_POST['percent']; 
$from = $_POST['from']; $to = $_POST['to']; $limit = $_POST['limit'];
if(mb_strlen($code,'UTF-8') < 3 || mb_strlen($reason,'UTF-8') > 25) {
echo 'e1';
}else{
if(!(strlen($show) == 1 && ($show == 0 || $show == 1))) {
echo 'e2';
}else{
if(mb_strlen($describe,'UTF-8') < 2 || mb_strlen($describe,'UTF-8') > 75) {
echo 'e3';
}else{
if($percent > 100 || $percent < 1) {
echo 'e4';
}else{
$date = DateTime::createFromFormat('d/m/Y - H:i', $from); $e = DateTime::getLastErrors();
if($e['error_count'] > 0) {
echo 'e5';
}else{
$fdate = $date->format('U');
$date = DateTime::createFromFormat('d/m/Y - H:i', $to); $e = DateTime::getLastErrors();
if($e['error_count'] > 0) {
echo 'e6';
}else{
if($limit != '' && !is_numeric($limit)) {
echo 'e7';
}else{
$q92 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `code`='$code' AND `deleted`='0'");
$codetimes = $q92->num_rows; 
if($codetimes > 0) {
echo 'e8';
}else{
$until = $date->format('U');
$mysqli->query("INSERT INTO `zz_coupons`(`code`,`show`,`describe`,`percent`,`from`,`to`,`limit`) VALUES('$code','$show','$describe','$percent','$fdate','$until','$limit')");
} } } } } } } }
}else{
if($do == delclose && $canlogin == 1) {
$id = $_POST['id'];
$mysqli->query("DELETE FROM `zz_closed` WHERE `id`='$id'");
if(sizeof($mysqli->affected_rows) > 0) {
echo 'deleted';
}
}else{
if($do == closed && $canlogin == 1) {
$from = $_POST['from']; $to = $_POST['to']; $reason = $mysqli->real_escape_string($_POST['reason']); $loc = $_POST['loc'];
$date = DateTime::createFromFormat('d/m/Y - H:i', $from); $e = DateTime::getLastErrors();
if($e['error_count'] > 0) {
echo 'e1';
}else{
$fdate = $date->format('U');
$date = DateTime::createFromFormat('d/m/Y - H:i', $to); $e = DateTime::getLastErrors();
if($e['error_count'] > 0) {
echo 'e2';
}else{
if(mb_strlen($reason,'UTF-8') < 2 || mb_strlen($reason,'UTF-8') > 25) {
echo 'e3';
}else{
$q90 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$loc'");
if($q90->num_rows == 0 && $loc != 0) {
echo 'e4';
}else{
$until = $date->format('U');
$mysqli->query("INSERT INTO `zz_closed`(`date`,`until`,`reason`,`loc`) VALUES('$fdate','$until','$reason','$loc')");
} } } }
}else{
if($do == editloc && $canlogin == 1) {
$adress = $mysqli->real_escape_string($_POST['adress']); $city = $mysqli->real_escape_string($_POST['city']); $work = $_POST['work']; $id = $_POST['id'];
$phone = $_POST['phone'];
if(mb_strlen($adress,'UTF-8')  < 3 || mb_strlen($adress,'UTF-8') > 50) {
echo 'e1';
}else{
if(mb_strlen($city,'UTF-8') < 2 || mb_strlen($city,'UTF-8') > 50) {
echo 'e2';
}else{
if(!is_numeric($phone) || mb_strlen($phone, 'UTF-8') < 4 || mb_strlen($phone, 'UTF-8') > 15) {
echo 'e4';
}else{
if(sizeof(array_filter($work)) < sizeof($work)) {
echo 'e3';
}else{
$mysqli->query("UPDATE `zz_locations` SET `adress`='$adress',`city`='$city',`phone`='$phone' WHERE `id`='$id'");
for($i = 0; $i < 7; $i++) { $plusone = $i+1; $worki = $work[$i]; 
$mysqli->query("UPDATE `zz_locations` SET `day$plusone`='$worki' WHERE `id`='$id'");
}
} } } }
}else{
if($do == locdel && $canlogin == 1) {
$id = $_POST['id'];
$mysqli->query("DELETE FROM `zz_locations` WHERE `id`='$id'");
$q79 = $mysqli->query("SELECT * FROM `zz_order` WHERE `loc`='$id'");
while($od = $q79->fetch_assoc()) { $thisid = $od['id'];
$mysqli->query("DELETE FROM `zz_addons` WHERE `orderid`='$thisid'");
$mysqli->query("DELETE FROM `zz_order` WHERE `id`='$thisid'");
}
echo 'done';
}else{
if($do == newloc && $canlogin == 1) {
$adress = $mysqli->real_escape_string($_POST['adress']); $city = $mysqli->real_escape_string($_POST['city']); $work = $_POST['work'];
$phone = $_POST['phone'];
if(mb_strlen($adress,'UTF-8')  < 3 || mb_strlen($adress,'UTF-8') > 50) {
echo 'e1';
}else{
if(mb_strlen($city,'UTF-8') < 2 || mb_strlen($city,'UTF-8') > 50) {
echo 'e2';
}else{
if(!is_numeric($phone) || mb_strlen($phone, 'UTF-8') < 4 || mb_strlen($phone, 'UTF-8') > 15) {
echo 'e4';
}else{
if(sizeof(array_filter($work)) < sizeof($work)) {
echo 'e3';
}else{
$mysqli->query("INSERT INTO `zz_locations`(`adress`,`city`,`phone`) VALUES('$adress','$city','$phone')"); $id = $mysqli->insert_id;
for($i = 0; $i < 7; $i++) { $plusone = $i+1; $worki = $work[$i]; 
$mysqli->query("UPDATE `zz_locations` SET `day$plusone`='$worki' WHERE `id`='$id'");
}
} } } }
}else{
if($do == neworder && $canlogin == 1) {
$nowid = $_POST['id']; $g = $_POST['g']; $loc = $_POST['loc'];
if($g == 'new') {
if($loc == 'all') {
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' AND `id`>'$nowid' ORDER BY `date` DESC,`id` DESC";
}else{
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' AND `id`>'$nowid' AND `loc`='$loc' ORDER BY `date` DESC,`id` DESC";
}
}else{
if($loc == 'all') {
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `id`>'$nowid' ORDER BY `date` DESC,`id` DESC";
}else{
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `id`>'$nowid' AND `loc`='$loc' ORDER BY `date` DESC,`id` DESC";
}
}
$qu = $mysqli->query($q);   $c = 1;
if($qu->num_rows > 0) { while($fetch = $qu->fetch_assoc()) {
echo $fetch['id'].'--';
orderdet($mysqli,$q,$minbill,"admin",2);
if($qu->num_rows > 1 && $qu->num_rows != $c) { echo '-/-'; $c++; }else{ $c = 1; }
} }
}else{
if($do == schange && $canlogin == 1) {
$id = $_POST['oid']; $sguide = array("קבלת ההזמנה","הכנה","אפייה","משלוח","הזמנה הושלמה");
$mysqli->query("UPDATE `zz_order` SET `status`=`status`+1 WHERE `id`='$id'");
$q84 = $mysqli->query("SELECT * FROM `zz_order` WHERE `id`='$id'");
$fetch = $q84->fetch_assoc(); $orderid = $fetch['id']; $status = $fetch['status'];
if($status > 5) { $mysqli->query("UPDATE `zz_order` SET `status`='5' WHERE `id`='$id'"); }
echo '<div style="font-size:8pt;">סטטוס הזמנה:</div>';
if($status == 5) { 
echo '<span style="color:green;">'.$sguide[$status-1].'.</span>';
}else{
echo $sguide[$status-1].'.<BR><a href="javascript:void(0);" style="color:#708EAA; font-size:10pt;" id="o'.$orderid.'" class="statuschange noload">שנה ל'.$sguide[$status].'</a>';
}
}else{
if($do == loadorder && $canlogin == 1) {
$page = $_POST['page']; $g = $_POST['g']; $loc = $_POST['loc'];
if($g == 'new') {
if($loc == 'all') {
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' ORDER BY `date` DESC,`id` DESC LIMIT $page,30";
}else{
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `status`!='5' AND `loc`='$loc' ORDER BY `date` DESC,`id` DESC LIMIT $page,30";
}
}else{
if($loc == 'all') {
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' ORDER BY `date` DESC,`id` DESC LIMIT $page,30";
}else{
$q = "SELECT * FROM `zz_order` WHERE `status`!='0' AND `loc`='$loc' ORDER BY `date` DESC,`id` DESC LIMIT $page,30";
}
}

orderdet($mysqli,$q,$minbill,"admin",0);
}else{
if($do == loadcus && $canlogin == 1) {
$page = $_POST['page'];
$q12 = $mysqli->query("SELECT * FROM `zz_buyers` ORDER BY `id` DESC LIMIT $page,20");
while($pl = $q12->fetch_assoc()) { $id = $pl['id']; $name = $pl['name']; $phone = $pl['phone']; $street = $pl['street']; $housenum = $pl['housenum']; $city = $pl['city']; $securea = $pl['securea']; $secureq = $pl['secureq'];
$q13 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$phone'"); $numorders = $q13->num_rows;
echo <<<Print
<tr><td><span class="name">$name</span></td><td><span class="phone">$phone</span></td><td><span class="street">$street</span> <span class="housenum">$housenum</span>, <span class="city">$city</span></td><td>$secureq</td><td>$securea</td><td><a href="javascript:void(0);" style="font-size:10pt; text-decoration:none;" class="edit noload" id="e$id">ערוך</a> - <a href="javascript:void(0);" style="font-size:10pt; color:red; text-decoration:none;" class="delete noload" id="d$id">מחק</a><BR><a href="$url/admin.php/orders/$phone" style="font-size:10pt; text-decoration:none;">הזמנות של הלקוח ($numorders)</a></td></tr>
Print;
}
}else{
if($do == editcus && $canlogin == 1) {
$id = $_POST['id']; $name = $mysqli->real_escape_string($_POST['name']); $phone = $_POST['phone']; $street = $mysqli->real_escape_string($_POST['street']); $housenum = $_POST['housenum']; $city = $mysqli->real_escape_string($_POST['city']); 
if(mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 15) {
echo 'e1';
}else{
$q13 = $mysqli->query("SELECT * FROM `zz_buyers` WHERE `phone`='$phone' AND `id`!='$id'");
if(!is_numeric($phone) || mb_strlen($phone, 'UTF-8') < 4 || mb_strlen($phone, 'UTF-8') > 15 || $q13->num_rows > 0) {
echo 'e3';
}else{
if(mb_strlen($street, 'UTF-8') < 3 || mb_strlen($street, 'UTF-8') > 30) {
echo 'e4';
}else{
if(!is_numeric($housenum) || mb_strlen($housenum, 'UTF-8') < 1 || mb_strlen($housenum, 'UTF-8') > 4) {
echo 'e5';
}else{
if(mb_strlen($city, 'UTF-8') < 2 || mb_strlen($city, 'UTF-8') > 20) {
echo 'e6';
}else{
$mysqli->query("UPDATE `zz_buyers` SET `name`='$name',`phone`='$phone',`street`='$street',`housenum`='$housenum',`city`='$city' WHERE `id`='$id'");
} } } } }
}else{
if($do == deletecus && $canlogin == 1) {
$id = $_POST['id'];
$q83 = $mysqli->query("SELECT * FROM `zz_buyers` WHERE `id`='$id'");
$qu = $q83->fetch_assoc(); $cusphone = $qu['phone'];
$mysqli->query("DELETE FROM `zz_buyers` WHERE `id`='$id'");
$q84 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$cusphone'");
while($od = $q84->fetch_assoc()) { $thisid = $od['id'];
$mysqli->query("DELETE FROM `zz_addons` WHERE `orderid`='$thisid'");
$mysqli->query("DELETE FROM `zz_order` WHERE `id`='$thisid'");
}
}else{
if($do == deletecat && $canlogin == 1) {
$id = $_POST['id'];
$mysqli->query("DELETE FROM `zz_cat` WHERE `id`='$id'");
$mysqli->query("DELETE FROM `zz_products` WHERE `type`='$id'");
}else{
if($do == editcat && $canlogin == 1) {
$id = $_POST['id']; $name = $mysqli->real_escape_string($_POST['name']);
if(mb_strlen($_POST['name'], 'UTF-8') < 2 ||  mb_strlen($_POST['name'], 'UTF-8') > 50) {
echo 'e1';
}else{ 
$mysqli->query("UPDATE `zz_cat` SET `name`='$name' WHERE `id`='$id'");
}
}else{
if($do == newcat && $canlogin == 1) {
$name = $mysqli->real_escape_string($_POST['name']); 
if(mb_strlen($_POST['name'], 'UTF-8') < 2 ||  mb_strlen($_POST['name'], 'UTF-8') > 50) {
echo 'e1';
}else{ 
$mysqli->query("INSERT INTO `zz_cat`(`name`) VALUES('$name')");
}
}else{
if($do == deletepro && $canlogin == 1) {
$id = $_POST['id']; $type = $_POST['type'];
$q12 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$id'");
if($q12->num_rows > 0) { 
if($type == 0) {
$con = proinorder($mysqli,$id,1);
for($i = 0; $i < sizeof($con); $i++) { $coni = $con[$i];
$mysqli->query("DELETE FROM `zz_order` WHERE `id`='$coni'");
$mysqli->query("DELETE FROM `zz_addons` WHERE `orderid`='$coni'"); }
$xr = $q12->fetch_assoc(); $img = $xr['img'];
if(substr_count($img,'gallery') == 0) {
unlink('images/uploads/'.$img);
}
$mysqli->query("DELETE FROM `zz_products` WHERE `id`='$id'");
}else{
$mysqli->query("UPDATE `zz_products` SET `show`='0' WHERE `id`='$id'");
if($type == 2) {
$con = proinorder($mysqli,$id,0);
for($i = 0; $i < sizeof($con); $i++) { $coni = $con[$i];
$mysqli->query("DELETE FROM `zz_order` WHERE `id`='$coni'"); }
}
} }
}else{
if($do == editpro && $canlogin == 1) {
$id = $_POST['id']; $name = $mysqli->real_escape_string($_POST['name']); $price = $_POST['price']; $half = $_POST['half']; $cat = $_POST['cat']; $addon = $_POST['addon']; 
$filename = pathinfo($_FILES['img']['name']); $des = $mysqli->real_escape_string($_POST['des']); $imgc = $_POST['cimg'];
$fname = $filename['filename']; $ext = strtolower($filename['extension']);
if(mb_strlen($_POST['name'], 'UTF-8') < 2 ||  mb_strlen($_POST['name'], 'UTF-8') > 50) {
echo 'e1';
}else{ 
if($des != Null && (mb_strlen($des,'UTF-8') < 2 || mb_strlen($des,'UTF-8') > 150)) {
echo 'e6';
}else{
if(!is_numeric($price)) {
echo 'e2';
}else{
if($half != 0 && $half != 1) {
echo 'e3';
}else{
$q1 = $mysqli->query("SELECT * FROM `zz_cat` WHERE `id`='$cat'");
if($q1->num_rows == 0 && $cat != 0) {
echo 'e4';
}else{
if(!($ext == "jpg" || $ext == "png" || $ext == "bmp" || $ext == "gif" || $ext == "jpeg") && $ext != Null) {
echo 'e5';
}else{
if($addon[0] == 'all') {
$addons = '{0}';
}else{
foreach($addon as $check) {
$addons .= $check.',';
} $addons = substr($addons,0,-1); } 
if($addons == "") { $addons = 0; }
if($ext != Null) {
$newname = dirname(__FILE__).'/images/uploads/'.$fname.'.'.$ext; $i = 1; $imgname = $fname.'.'.$ext;
while(file_exists($newname)) { $newname = dirname(__FILE__).'/images/uploads/'.$fname.$i.'.'.$ext; $imgname = $fname.$i.'.'.$ext; $i++;  }
move_uploaded_file($_FILES['img']['tmp_name'],$newname);
$mysqli->query("UPDATE `zz_products` SET `img`='$imgname' WHERE `id`='$id'");
}else{
if($imgc != Null) { $imgname = '/gallery/'.$imgc;
$mysqli->query("UPDATE `zz_products` SET `img`='$imgname' WHERE `id`='$id'");
} }
$mysqli->query("UPDATE `zz_products` SET `name`='$name',`type`='$cat',`price`='$price',`needad`='$addons',`half`='$half',`des`='$des' WHERE `id`='$id'");
} } } } } }
}else{
if($do == up && $canlogin == 1) {
$name = $mysqli->real_escape_string($_POST['name']); $price = $_POST['price']; $half = $_POST['half']; $cat = $_POST['cat']; $addon = $_POST['addon']; 
$filename = pathinfo($_FILES['img']['name']); $des = $mysqli->real_escape_string($_POST['des']); $imgc = $_POST['cimg'];
$fname = $filename['filename']; $ext = strtolower($filename['extension']);
if(mb_strlen($_POST['name'], 'UTF-8') < 2 ||  mb_strlen($_POST['name'], 'UTF-8') > 50) {
echo 'e1';
}else{ 
if($des != Null && (mb_strlen($des,'UTF-8') < 2 || mb_strlen($des,'UTF-8') > 150)) {
echo 'e6';
}else{
if(!is_numeric($price)) {
echo 'e2';
}else{
if($half != 0 && $half != 1) {
echo 'e3';
}else{
$q1 = $mysqli->query("SELECT * FROM `zz_cat` WHERE `id`='$cat'");
if($q1->num_rows == 0 && $cat != 0) {
echo 'e4';
}else{
if(!($ext == "jpg" || $ext == "png" || $ext == "bmp" || $ext == "gif" || $ext == "jpeg") && $imgc == '') {
echo 'e5';
}else{
if($addon[0] == 'all') {
$addons = '{0}';
}else{
foreach($addon as $check) {
$addons .= $check.',';
} $addons = substr($addons,0,-1); } 
if($addons == "") { $addons = 0; }
if($imgc != '') { $imgname = '/gallery/'.$imgc; }else{
$newname = dirname(__FILE__).'/images/uploads/'.$fname.'.'.$ext; $i = 1; $imgname = $fname.'.'.$ext;
while(file_exists($newname)) { $newname = dirname(__FILE__).'/images/uploads/'.$fname.$i.'.'.$ext; $imgname = $fname.$i.'.'.$ext; $i++;  }
move_uploaded_file($_FILES['img']['tmp_name'],$newname); }
$mysqli->query("INSERT INTO `zz_products`(`name`,`type`,`price`,`img`,`needad`,`half`,`des`,`show`) VALUES('$name','$cat','$price','$imgname','$addons','$half','$des','1')");
} } } } } }
}else{
if($do == login && $canlogin != 1) {
$user = $_POST['user']; $pass = $_POST['pass']; $myip = $_SERVER['REMOTE_ADDR']; $date = date('j.n.y - H:i');
if($user != $suser || md5($pass) != $spass) {
echo 'e1';
if($user != '' && $pass != '') {
$mysqli->query("INSERT INTO `zz_adminlogs`(`ip`,`date`,`user`,`pass`) VALUES('$myip','$date','$user','$pass')");
}
}else{
if($sitesalt == '') {
$sitesalt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$mysqli->query("UPDATE `zz_settings` SET `salt`='$sitesalt' WHERE `id`='1'");
}
$key = md5($user.','.md5($pass).$sitesalt);
setcookie("admin","$key", time() + 86400000);
}
}else{
echo 'error';
} } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } } }

}else{

if($noreg==1) { 
$q0 = $mysqli->query("SELECT * FROM `zz_order` WHERE `salt`='$mysalt' AND `status`='0' ORDER BY `id` DESC LIMIT 1");
}else{
$q0 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`='0' ORDER BY `id` DESC LIMIT 1");
}
$qq = $q0->fetch_assoc(); $orderid = $qq['id']; $orderprice = orderprice($mysqli,$orderid); $opaid = $qq['paid']; $coupon = $qq['coupon'];

if($do == paycall) {
require_once('js/paycall.php');
$orderid = $_POST['orderid'];
$result = micropayment::collection($paycall,$_POST["price"],$_POST["premium"],$_POST["phone"]);
if(!$result) {
echo 'e1';
}else{
$index = $result['UNIQUE_ID'];
$q21 = $mysqli->query("SELECT * FROM `zz_purchases` WHERE `index`='$index'");
if($q21->num_rows > 0) {
echo 'e2';
}else{
$needpay = orderprice($mysqli,$orderid);$amount = round($result['PAY_IN'],2);  
if($amount < $needpay && ($amount+1) > $needpay) {
$mysqli->query("UPDATE `zz_order` SET `paid`='$needpay' WHERE `id`='$orderid'");
}else{
$mysqli->query("UPDATE `zz_order` SET `paid`=`paid`+'$amount' WHERE `id`='$orderid'");
} 
$mysqli->query("INSERT INTO `zz_purchases`(`index`,`uid`) VALUES('$index','$myuid')");
} }
}else{
if($do == contact) {
$name = $_POST['name']; $mail = $_POST['mail']; $sub = $_POST['subject']; $mess = $_POST['mess'];
if(mb_strlen($name,'UTF-8') < 2 || mb_strlen($name,'UTF-8') > 18) {
echo 'e1';
}else{
if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $mail)) {
echo 'e2';
}else{
if(mb_strlen($sub,'UTF-8') < 3 || mb_strlen($sub,'UTF-8') > 35) {
echo 'e3';
}else{
if(mb_strlen($mess,'UTF-8') < 2 || mb_strlen($mess,'UTF-8') > 1000) {
echo 'e4';
}else{ $ip = $_SERVER['REMOTE_ADDR'];
$to=$adminmail; $headers  = 'MIME-Version: 1.0' . "\r\n"; $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";  $headers .= 'from: '.$name.' <'.$mail.'>';
$message= '<div style="background:white; direction:rtl; font-family:arial; width:750px; margin:auto; border:1px solid gray; padding:5px; margin-top:30px;">'.$mess.'<BR><BR><span style="font-size:8pt;">IP השולח: '.$ip.'</span></div>';
$sentmail = mail($to,$sub,$message,$headers);
} } } }
}else{
if($do == loadorder && $canlogin == 1) {
$page = $mysqli->real_escape_string($_POST['page'])*20;
$q = "SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`>'0' ORDER BY `id` DESC LIMIT $page,20";
orderdet($mysqli,$q,$minbill,"all",0);
}else{
if($do == clear && $canlogin == 1) {
$mysqli->query("DELETE FROM `zz_addons` WHERE `orderid`='$orderid'");
$mysqli->query("DELETE FROM `zz_order` WHERE `id`='$orderid'");
}else{
if($do == checkloc && $canlogin == 1) {
$loc = $mysqli->real_escape_string($_POST['loc']);
$q6 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$loc'");
if($q6->num_rows == 0) {
echo 'e1';
}else{
$dayoftheweek = date('w')+1;
$qu = $q6->fetch_assoc(); $day = $qu['day'.$dayoftheweek];
if($day != 'close') { $times = explode(" - ",$day);
$now = DateTime::createFromFormat('H:i', date('H:i')); $open = DateTime::createFromFormat('H:i', $times[0]);
$close = DateTime::createFromFormat('H:i', $times[1]); $midnight = DateTime::createFromFormat('H:i', '00:00');
$result = 1;
if($close >= $midnight && $close < $open) {
if($now > $close && $now < $open) {$result = 0;}
}else{
if ($now < $open || $now > $close) {
$result = 0;
} } }else{ $result = 0; }
$closed = 'no';
$q4 = $mysqli->query("SELECT * FROM `zz_closed` WHERE `loc`='0' OR `loc`='$loc'");
if($q4->num_rows > 0) { $now = time();
while($dd = $q4->fetch_assoc()) { $date = $dd['date']; $until = $dd['until'];
if($date < $now && $until > $now) { $closed = 'yes'; break; }
} }
if($result == 0 || $closed == 'yes') {
echo 'e1';
}else{
$mysqli->query("UPDATE `zz_order` SET `loc`='$loc' WHERE `id`='$orderid'");
} }
}else{
if($do == coff && $canlogin == 1) {
$q14 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `id`='$coupon' AND `deleted`='0'");
$sh = $q14->fetch_assoc(); $percent = $sh['percent'];
if($percent > 0) { 
echo '<div class="coupontext"><div style="float:right;">קופון '.$percent.'% הנחה</div><div style="float:left;color:red;cursor:pointer;" class="removec">הסר קופון</div><div style="clear:both;"></div></div>';
}
}else{
if($do == removec && $canlogin == 1) {
$mysqli->query("UPDATE `zz_order` SET `coupon`='0' WHERE `id`='$orderid'");
$q91 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `id`='$coupon'");
$query = $q91->fetch_assoc(); $limit = $query['limit'];
if($limit != '') {
$mysqli->query("UPDATE `zz_coupons` SET `limit`=`limit`+1 WHERE `id`='$coupon'");
}
}else{
if($do == coupon && $canlogin == 1) {
$c = $mysqli->real_escape_string($_POST['coupon']); $now = time(); 
$q50 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `code`='$c' AND `deleted`='0'");
if($q50->num_rows == 0) {
echo 'e1';
}else{
$js = $q50->fetch_assoc(); $cid = $js['id']; $from = $js['from']; $to = $js['to']; $percent = $js['percent']; $limit = $js['limit'];
if($now < $from || $now > $to) {
echo 'e2';
}else{
if($limit != '' && $limit == 0) {
echo 'e3';
}else{
if($coupon != 0) {
echo 'e4';
}else{
if($orderid != '') {
$mysqli->query("UPDATE `zz_order` SET `coupon`='$cid' WHERE `id`='$orderid'");
if($limit != '') { $mysqli->query("UPDATE `zz_coupons` SET `limit`=`limit`-1 WHERE `id`='$cid'"); }
}
} } } }
}else{
if($do == settings && $canlogin == 1) {
$street = $mysqli->real_escape_string($_POST['street']); $housenum = $mysqli->real_escape_string($_POST['housenum']); $city = $mysqli->real_escape_string($_POST['city']); 
$q = $mysqli->real_escape_string($_POST['q']); $ans = $mysqli->real_escape_string($_POST['ans']);  $code = $mysqli->real_escape_string($_POST['code']); $rows = 0;
$birthday = $_POST['birthday']; $checkbd = explode("-",$birthday);
if(!checkdate($checkbd[1], $checkbd[2], $checkbd[0]) && $birthday != '') { 
echo 'e1';
}else{
if(mb_strlen($street, 'UTF-8') < 3 || mb_strlen($street, 'UTF-8') > 30) {
echo 'e2';
}else{
if(!is_numeric($housenum) || mb_strlen($housenum, 'UTF-8') < 1 || mb_strlen($housenum, 'UTF-8') > 4) {
echo 'e3';
}else{
if(mb_strlen($city, 'UTF-8') < 2 || mb_strlen($city, 'UTF-8') > 20) {
echo 'e4';
}else{
if(mb_strlen($ans, 'UTF-8') < 3 || mb_strlen($ans, 'UTF-8') > 30) {
echo 'e5';
}else{
if(md5($code) != $mycode) {
echo 'e6';
}else{
if($mybd == '0000-00-00') {
$mysqli->query("UPDATE `zz_buyers` SET `birthday`='$birthday' WHERE `phone`='$myphone'");
}
$mysqli->query("UPDATE `zz_buyers` SET `street`='$street',`housenum`='$housenum',`city`='$city',`secureq`='$q',`securea`='$ans' WHERE `phone`='$myphone'");
} } } } } }
}else{
if($do == logout && $canlogin == 1) {
setcookie("phone","$key", time() - 86400000);
header("location: ".$url);
}else{
if($do == paidprice && $canlogin == 1) {
paidprice($mysqli,$orderid,$minbill);
}else{
if($do == paids && $canlogin == 1) {
$cp = orderprice($mysqli,$orderid);
if(round($cp-$opaid,2) == 0) {
echo 0;
}else{
if($opaid == 0) {
echo 'n';
}else{
echo round($cp-$opaid,2);
} }
}else{
if($do == completeorder && $canlogin == 1) {
$name = $mysqli->real_escape_string($_POST['name']); $street = $mysqli->real_escape_string($_POST['street']); $housenum = $mysqli->real_escape_string($_POST['housenum']); $city = $mysqli->real_escape_string($_POST['city']); $loc = $mysqli->real_escape_string($_POST['loc']); 
$capa = $_POST['capa']; $code = $mysqli->real_escape_string($_POST['code']); $more = $mysqli->real_escape_string($_POST['more']);
$adress = $street.' '.$housenum.','.$city;
if(mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 15) {
echo 'e1';
}else{
if(mb_strlen($street, 'UTF-8') < 3 || mb_strlen($street, 'UTF-8') > 30) {
echo 'e3';
}else{
if(!is_numeric($housenum) || mb_strlen($housenum, 'UTF-8') < 1 || mb_strlen($housenum, 'UTF-8') > 4) {
echo 'e4';
}else{
if(mb_strlen($city, 'UTF-8') < 2 || mb_strlen($city, 'UTF-8') > 25) {
echo 'e5';
}else{
if($orderprice < $minbill) {
echo 'e6';
}else{
$q61 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$loc'");
if($q61->num_rows == 0) {
echo 'e7';
}else{
$dayoftheweek = date('w')+1;
$qu = $q61->fetch_assoc(); $day = $qu['day'.$dayoftheweek];
if($day != 'close') { $times = explode(" - ",$day);
$now = DateTime::createFromFormat('H:i', date('H:i'));
$open = DateTime::createFromFormat('H:i', $times[0]);
$close = DateTime::createFromFormat('H:i', $times[1]);
$midnight = DateTime::createFromFormat('H:i', '00:00');
$result = 1;
if($close >= $midnight && $close < $open) {
if($now > $close && $now < $open) {$result = 0;}
}else{
if ($now < $open || $now > $close) {
$result = 0;
} } }else{ $result = 0; }
$closed = 'no';
$q4 = $mysqli->query("SELECT * FROM `zz_closed` WHERE `loc`='0' OR `loc`='$loc'");
if($q4->num_rows > 0) { $now = time();
while($dd = $q4->fetch_assoc()) { $date = $dd['date']; $until = $dd['until'];
if($date < $now && $until > $now) { $closed = 'yes'; break; }
} }
if($result == 0 || $closed == 'yes') {
echo 'e8';
}else{
if($more != Null && mb_strlen($more, 'UTF-8') > 150) {
echo 'e11';
}else{
if(md5($code) != $mycode && $guest != 1) {
echo 'e10';
}else{
if($capa != $_SESSION['cap']) {
echo 'e9';
}else{  $date = date("y.n.j - H:i");
if($guest==1) {
$mysqli->query("UPDATE `zz_order` SET `guest`='$name' WHERE `id`='$orderid'");
}else{
$mysqli->query("UPDATE `zz_buyers` SET `name`='$name',`street`='$street',`housenum`='$housenum',`city`='$city' WHERE `phone`='$myphone'");
}
$q157 = $mysqli->query("SELECT * FROM `zz_order` WHERE `id`>'$orderid' ORDER BY `id` DESC LIMIT 1");
if($q157->num_rows > 0) { $dj = $q157->fetch_assoc(); $lastid = $dj['id']+1; }else{ $lastid = $orderid; }
echo $lastid; 
$mysqli->query("UPDATE `zz_addons` SET `orderid`='$lastid' WHERE `orderid`='$orderid'");
$mysqli->query("UPDATE `zz_order` SET `id`='$lastid',`status`='1',`loc`='$loc',`adress`='$adress',`deliver`='$deliverprice',`more`='$more',`date`='$date' WHERE `id`='$orderid'");
} } } } } } } } } }
}else{
if($do == pizzaplace && $canlogin == 1) {
$topro = $mysqli->real_escape_string($_POST['topro']);
$itemid = idbyindex($mysqli,$topro,$orderid);
$q3 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$itemid' AND `show`='1'");
$ql = $q3->fetch_assoc();
if($ql['half'] == 0) {
echo <<<Print
<div style="float:right; text-align:center; margin:0 15px;" class="percentall">
<fieldset class="addons0"><legend>תוספות בפיצה:</legend><div class="empty0" style="padding:23px;">אין תוספות בפיצה.</div></fieldset>
<BR>
<div class="half0" style="background:url($url/images/full.png); height:338px;">
Print;
addonmenu($mysqli,$topro,$orderid,0);
}else{
for($i = 1; $i <= 2; $i++) {
echo <<<Print
<div style="float:right; text-align:center; margin:0 15px;" class="widted">
<div style="font-size:15pt; font-weight:bold;">חצי $i:</div>
<fieldset class="addons$i"><legend>תוספות בפיצה:</legend><div class="empty$i" style="padding:23px;">אין תוספות בפיצה.</div></fieldset>
<BR>
Print;
if($i == 1) {
echo <<<Print
<div class="half$i" style="background:url($url/images/half$i.png); width:173px; height:338px; float:left;">
Print;
}else{
echo <<<Print
<div class="half$i" style="background:url($url/images/half$i.png); width:173px; height:338px;">
Print;
}
addonmenu($mysqli,$topro,$orderid,$i);
} }
}else{
if($do == loadcat) {
$cat = $mysqli->real_escape_string($_POST['cat']);
if($cat == 0) {
$q2 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `deleted`='0' ORDER BY `id` DESC");
while($jn = $q2->fetch_assoc()) { $id = $jn['id']; $name = $jn['name']; $price = $jn['price']; $img = $jn['img']; $des = $jn['des'];
echo <<<Print
<div class="offp" title="$des">
<div class="protitle">$name</div>
<div class="prod" style="background:url(/images/uploads/$img) no-repeat center; background-size: cover;"><div class="infopro">$price ₪ בלבד!
<a href="$url/deal/$id" style="text-decoration:none;">
<div class="additem" style="cursor:pointer;">הזמן</div></a></div>
</div>
</div>
Print;
}
}else{
$q11 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='$cat' AND `show`='1'");
if($q11->num_rows == 0) {
echo '<div class="notice">אין מוצרים בקטגוריה זו.</div>';
}else{
while($uz = $q11->fetch_assoc()) { $id = $uz['id']; $name = $uz['name']; $price = $uz['price']; $img = $uz['img']; $des = $uz['des'];
echo <<<Print
<div class="offp" title="$des">
<div class="protitle">$name</div>
<div class="prod" style="background:url($url/images/uploads/$img) no-repeat center; background-size: cover;"><div class="infopro">$price ₪
<div class="additem" style="cursor:pointer;" id="item$id">הזמן</div>
</div></div>
</div>
Print;
} } }
}else{
if($do == addons && $canlogin == 1) {
$pro = $mysqli->real_escape_string($_POST['pro']);

$half = intval($_POST['half']); $adid = $mysqli->real_escape_string($_POST['adid']);
if(($half == 0 || $half == 1 || $half == 2) && isset($pro)) {
$q20 = $mysqli->query("SELECT * FROM `zz_addons` WHERE `orderid`='$orderid' AND `toproduct`='$pro'"); $g = 0;
while($qu = $q20->fetch_assoc()) { $h = $qu['half']; if($h == $half) { $g = 1; } }
if($g == 1) {
$mysqli->query("UPDATE `zz_addons` SET `adid`='$adid' WHERE `orderid`='$orderid' AND `toproduct`='$pro' AND `half`='$half'");
}else{
$mysqli->query("INSERT INTO `zz_addons`(`adid`,`toproduct`,`orderid`,`half`) VALUES('$adid','$pro','$orderid','$half')");
} }
}else{
if($do == cart) {
if($noreg==1) { 
$q = "SELECT * FROM `zz_order` WHERE `salt`='$mysalt' AND `products`!='' AND `status`='0' ORDER BY `id` DESC LIMIT 1";
}else{
$q = "SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `products`!='' AND `status`='0' ORDER BY `id` DESC LIMIT 1";
}
orderdet($mysqli,$q,$minbill,"side",1);
}else{
if($do == deleteitem && $canlogin == 1) {
$item = $mysqli->real_escape_string($_POST['item']);
$mysqli->query("DELETE FROM `zz_addons` WHERE round(`toproduct`)='$item' AND `orderid`='$orderid'");
$q136 = $mysqli->query("SELECT * FROM `zz_addons` WHERE `toproduct`>'$item' AND `orderid`='$orderid'");
while($cm = $q136->fetch_assoc()) { $addid = $cm['id']; $topro = $cm['toproduct'];
if(substr_count($topro,'.') > 0) { $newn = explode('.',$topro); $newx = $newn[0]-1;
$done = $newx.'.'.$newn[1]; }else{ $done = $topro-1; } 
$mysqli->query("UPDATE `zz_addons` SET `toproduct`='$done' WHERE `id`='$addid'");
}
$q14 = $mysqli->query("SELECT * FROM `zz_order` WHERE `id`='$orderid'");
$query = $q14->fetch_assoc(); $allprice = $query['price']; $pro = explode(",",$query['products']); $newpro = '';
$q15 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$pro[$item]'");
$q = $q15->fetch_assoc(); $thisprice = $q['price'];
unset($pro[$item]); $pro = array_values($pro);
for($i = 0; $i < sizeof($pro); $i++) {
$newpro .= $pro[$i].',';
if($i == sizeof($pro)-1) {
$newpro = substr($newpro, 0, -1);
} }

$finalprice = number_format($allprice-$thisprice, 2);
$mysqli->query("UPDATE `zz_order` SET `products`='$newpro',`price`='$finalprice' WHERE `id`='$orderid'");
}else{
if($do == additem) {
$itemid = $mysqli->real_escape_string($_POST['itemid']); $date = date("y.n.j - H:i"); $passed = 1; $plode = explode(":",$itemid);
$d2check = $plode[0]; $dealid = substr($d2check,1);
$q152 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `id`='$dealid' AND `bd`='1'");
if($q152->num_rows) { $today = date("Y-m-d"); $q153 = $mysqli->query("SELECT * FROM `zz_birthday` WHERE `uid`='$myuid' AND `until`>'$today'");
if($q153->num_rows) { $js = $q153->fetch_assoc(); $did = $js['did']; $d2check = '{'.$did;
$q154 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND INSTR(`products`,'$d2check') > 0");
if($q154->num_rows > 0) { $passed = 0; } }else{ $passed = 0; } }
if($passed) {
if($canlogin != 1) { $salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$mysqli->query("INSERT INTO `zz_order`(`salt`,`deliver`) VALUES('$salt','$deliverprice')");
$orderid = $mysqli->insert_id; $key = md5($salt.','.$orderid);
setcookie("salt",$key,time()+86400);
}
$q11 = $mysqli->query("SELECT * FROM `zz_order` WHERE `id`='$orderid'");
$q12 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$itemid' AND `show`='1'");
$qu = $q12->fetch_assoc(); $price = $qu['price'];
$que = $q11->fetch_assoc(); $nowprice = $que['price'];
if($que['products'] == Null) {
$products = $itemid;
}else{
$products = $que['products'].','.$itemid;
}
$finalprice = $nowprice+$price;
if($q11->num_rows == 0) {
$mysqli->query("INSERT INTO `zz_order`(`products`,`buyer`,`date`,`price`,`status`,`guest`,`deliver`) VALUES('$itemid','$myphone','$date','$price','0','0','$deliverprice')");
}else{
$mysqli->query("UPDATE `zz_order` SET `products`='$products',`price`='$finalprice',`date`='$date' WHERE `id`='$orderid'");
}
}
}else{
if($do == guest) { $phone = $mysqli->real_escape_string($_POST['phone']); $location = $mysqli->real_escape_string($_POST['loc']); $date = date("y.n.j - H:i");
if(!is_numeric($phone) || mb_strlen($phone, 'UTF-8') < 4 || mb_strlen($phone, 'UTF-8') > 15) {
echo 'e1';
}else{
$q6 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$location'");
if($q6->num_rows == 0) {
echo 'e2';
}else{
$dayoftheweek = date('w')+1;
$qu = $q6->fetch_assoc(); $day = $qu['day'.$dayoftheweek];
if($day != 'close') { $times = explode(" - ",$day);
$now = DateTime::createFromFormat('H:i', date('H:i')); $open = DateTime::createFromFormat('H:i', $times[0]);
$close = DateTime::createFromFormat('H:i', $times[1]); $midnight = DateTime::createFromFormat('H:i', '00:00');
$result = 1;
if($close >= $midnight && $close < $open) {
if($now > $close && $now < $open) {$result = 0;}
}else{
if ($now < $open || $now > $close) {
$result = 0;
} } }else{ $result = 0; }
$closed = 'no';
$q4 = $mysqli->query("SELECT * FROM `zz_closed` WHERE `loc`='0' OR `loc`='$location'");
if($q4->num_rows > 0) { $now = time();
while($dd = $q4->fetch_assoc()) { $date = $dd['date']; $until = $dd['until'];
if($date < $now && $until > $now) { $closed = 'yes'; break; }
} }
if($result == 0 || $closed == 'yes') {
echo 'e3';
}else{
$phone = 'g'.$phone;
if($noreg == 1) {
$mysqli->query("UPDATE `zz_order` SET `buyer`='$phone',`loc`='$location',`date`='$date' WHERE `salt`='$mysalt' AND `status`='0'");
$ch = $mysqli->query("SELECT * FROM `zz_order` WHERE `salt`='$mysalt' AND `status`='0'");
setcookie("salt","", time() - 86400);
}else{ 
$ch = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$phone' AND `status`='0'");
}

if($ch->num_rows == 0) { 
$mysqli->query("INSERT INTO `zz_order`(`buyer`,`date`,`loc`) VALUES('$phone','$date','$location')"); $myid = $mysqli->insert_id;
}else{ 
$od = $ch->fetch_assoc(); $myid = $od['id'];
}
$key = md5($phone.','.$myid);
if($noreg == 1) {
$mysqli->query("UPDATE `zz_order` SET `salt`='' WHERE `salt`='$mysalt' AND `status`='0'");
}
setcookie("guest","$key", time() + 86400);
} } }
}else{
if($do == login) {
$phone = $mysqli->real_escape_string($_POST['phone']); $location = $mysqli->real_escape_string($_POST['location']); $q = $mysqli->real_escape_string($_POST['q']); $a = $mysqli->real_escape_string($_POST['a']); $myip = $_SERVER['REMOTE_ADDR']; $nowd = time();  $more = time()+60*$logtime;
$code = $mysqli->real_escape_string($_POST['code']); $date = date('y.n.j - H:i'); $birthday = $_POST['birthday']; $checkbd = explode("-",$birthday);
$q6 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$location'");
if($q6->num_rows == 0) {
echo 'e1';
}else{
$dayoftheweek = date('w')+1;
$qu = $q6->fetch_assoc(); $day = $qu['day'.$dayoftheweek];
if($day != 'close') { $times = explode(" - ",$day);
$now = DateTime::createFromFormat('H:i', date('H:i'));
$open = DateTime::createFromFormat('H:i', $times[0]);
$close = DateTime::createFromFormat('H:i', $times[1]);
$midnight = DateTime::createFromFormat('H:i', '00:00');
$result = 1;
if($close >= $midnight && $close < $open) {
if($now > $close && $now < $open) {$result = 0;}
}else{
if ($now < $open || $now > $close) {
$result = 0;
} } }else{ $result = 0; }
$closed = 'no';
$q4 = $mysqli->query("SELECT * FROM `zz_closed` WHERE `loc`='0' OR `loc`='$location'");
if($q4->num_rows > 0) { $now = time();
while($dd = $q4->fetch_assoc()) { $date = $dd['date']; $until = $dd['until'];
if($date < $now && $until > $now) { $closed = 'yes'; break; }
} }
if($result == 0 || $closed == 'yes') {
echo 'e2';
}else{
if(!is_numeric($phone) || mb_strlen($phone, 'UTF-8') < 4 || mb_strlen($phone, 'UTF-8') > 15) {
echo 'e3';
}else{
if(!checkdate($checkbd[1], $checkbd[2], $checkbd[0]) && $birthday != '') { 
echo 'e6';
}else{
$q7 = $mysqli->query("SELECT * FROM `zz_buyers` WHERE `phone`='$phone'");
$query = $q7->fetch_assoc(); $qus = $query['secureq']; $ans = $query['securea'];
if($q7->num_rows == 0) {
if((mb_strlen($q, 'UTF-8') < 4 || mb_strlen($q, 'UTF-8') > 70) || (mb_strlen($a, 'UTF-8') < 3 || mb_strlen($a, 'UTF-8') > 30) || (mb_strlen($code, 'UTF-8') < 6 || mb_strlen($code, 'UTF-8') > 18)) {
if($a != '') { $mysqli->query("INSERT INTO `zz_logs`(`ip`,`date`,`phone`,`ans`) VALUES('$myip','$more','$phone','$a')"); }
$q8 = $mysqli->query("SELECT * FROM `zz_logs` WHERE `ip`='$myip' AND `date`>'$nowd'");
$tries = $q8->num_rows;  $qu = $q8->fetch_assoc(); $left = $qu['date']-$nowd;
if($tries >= $logtry) {
$left = gmdate("i:s", $left);
echo 'e5';
echo 'נשארו עוד <div class="time">'.$left.'</div> דקות עד ל-'.$logtry.' הניסיונות הבאים.';
}else{
echo 'e4';
}
}else{
$code = md5($code); $salt = rand(100000,99999999);
$mysqli->query("INSERT INTO `zz_buyers`(`phone`,`secureq`,`securea`,`code`,`salt`,`birthday`) VALUES('$phone','$q','$a','$code','$salt','$birthday')");
$myid = $mysqli->insert_id; $key = md5($phone.','.$myid.$salt);

if($noreg == 1) {
$mysqli->query("UPDATE `zz_order` SET `buyer`='$phone',`loc`='$location',`date`='$date' WHERE `salt`='$mysalt' AND `status`='0'");
$ch = $mysqli->query("SELECT * FROM `zz_order` WHERE `salt`='$mysalt' AND `status`='0'");
setcookie("salt","", time() - 86400);
}else{
$ch = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$phone' AND `status`='0'");
}
if($ch->num_rows == 0) {
$mysqli->query("INSERT INTO `zz_order`(`buyer`,`date`,`loc`) VALUES('$phone','$date','$location')"); $oid = $mysqli->insert_id;
}else{ 
$od = $ch->fetch_assoc(); $oid = $od['id'];
}

if($noreg == 1) {
$mysqli->query("UPDATE `zz_order` SET `salt`='' WHERE `salt`='$mysalt' AND `status`='0'");
}
setcookie("phone","$key", time() + 86400000);
echo 'done';
}
}else{
if((mb_strlen($q, 'UTF-8') < 4 || mb_strlen($q, 'UTF-8') > 70) || (mb_strlen($a, 'UTF-8') < 3 || mb_strlen($a, 'UTF-8') > 30) || $ans != $a) {
if($a != '') { $mysqli->query("INSERT INTO `zz_logs`(`ip`,`date`,`phone`,`ans`) VALUES('$myip','$more','$phone','$a')"); }
$q8 = $mysqli->query("SELECT * FROM `zz_logs` WHERE `ip`='$myip' AND `date`>'$nowd' ORDER BY `id` DESC");
$tries = $q8->num_rows;  $qu = $q8->fetch_assoc(); $left = $qu['date']-$nowd;
if($tries >= $logtry) {
$left = gmdate("i:s", $left);
echo 'e5';
echo 'נשארו עוד <div class="time">'.$left.'</div> דקות עד ל-'.$logtry.' הניסיונות הבאים.';
}else{
$mysqli->query("DELETE FROM `zz_logs` WHERE `ip`='$myip' AND `date`<'$nowd'");
echo $qus.',';
$l = $logtry-$tries;
echo 'נשארו עוד '.$l.' ניסיונות התחברות עד לחסימה של '.$logtime.' דקות.';
}

}else{
$myid = $query['id']; $salt = $query['salt']; $key = md5($phone.','.$myid.$salt);

if($noreg == 1) {
$mysqli->query("UPDATE `zz_order` SET `buyer`='$phone',`loc`='$location',`date`='$date' WHERE `salt`='$mysalt' AND `status`='0'");
$ch = $mysqli->query("SELECT * FROM `zz_order` WHERE `salt`='$mysalt' AND `status`='0'");
setcookie("salt","", time() - 86400);
}else{
$ch = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$phone' AND `status`='0'");
}

if($ch->num_rows == 0) {
$mysqli->query("INSERT INTO `zz_order`(`buyer`,`date`,`loc`) VALUES('$phone','$date','$location')"); $myid = $mysqli->insert_id;
}else{ 
$od = $ch->fetch_assoc(); $oid = $od['id'];
}

setcookie("phone","$key", time() + 86400000);
if($noreg == 1) {
$mysqli->query("UPDATE `zz_order` SET `salt`='' WHERE `salt`='$mysalt' AND `status`='0'");
}
echo 'done';
} } } } } }
}else{
if($do == getstatus) {
$id = $mysqli->real_escape_string($_POST['id']);
$q2 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`!='0' AND `id`='$id'");
$qu = $q2->fetch_assoc(); $thisbuyer = $qu['buyer'];
if(substr_count($thisbuyer,'g') == 0) {
$q2 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND `status`!='0' AND `id`='$id'");
}
echo $qu['status'];
}else{
echo 'error';
} } } } } } } } } } } } } } } } } } } } } } }
?>