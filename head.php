<?php
require_once('config.php');
echo <<<Print
<style>
@font-face {font-family:title; src: url("$url/images/title.ttf"); }
@font-face {font-family:menu; src: url("$url/images/menu.ttf"); }
.bottomenu {-moz-column-count:$numb; -webkit-column-count:$numb; column-count:$numb; }
</style>
<link type="text/css" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<div style="margin:auto; height:100%;">
$oldnotice
<div class="sidemenu">
<a href="$url">
Print;
if($logo == Null) { echo '<img src="'.$url.'/images/logo.png" class="logo">'; }else{ echo '<img src="'.$url.'/images/uploads/'.$logo.'" class="logo">';}
echo <<<Print
</a>
<ul class="menu">
<li><a href="$url">ראשי</a></li>
<li><a href="$url/order">הזמנה</a></li>
<li><a href="$url/locations">סניפים</a></li>
<li><a href="$url/account">החשבון שלי</a></li>
Print;
$q2 = $mysqli->query("SELECT * FROM `zz_pages` WHERE `showm`='1'");
if($q2->num_rows > 0) {
echo '<li><div class="cmenu"><a href="javascript:void(0);">עוד..</a><ul class="custom">';
while($nf = $q2->fetch_assoc()) { $id = $nf['id']; $title = $nf['title']; $plink = $nf['link'];
if($plink == Null) {
echo '<a href="'.$url.'/pages/'.$id.'"><li>'.$title.'</li></a>';
}else{
echo '<a href="'.$plink.'" target="_blank"><li>'.$title.'</li></a>';
} }
echo '</ul></div></li>';
}

echo <<<Print
<li><a href="$url/contact">צור קשר</a></li>
</ul>
<div style="clear:both;"></div>
</div>
Print;
?>