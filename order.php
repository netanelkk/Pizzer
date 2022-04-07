<?php
function birthday($mysqli,$uid,$myphone) {
$today = date("Y-m-d"); $q153 = $mysqli->query("SELECT * FROM `zz_birthday` WHERE `uid`='$uid' AND `until`>'$today'");
if($q153->num_rows) { $js = $q153->fetch_assoc(); $did = $js['did']; $d2check = '{'.$did;
$q154 = $mysqli->query("SELECT * FROM `zz_order` WHERE `buyer`='$myphone' AND INSTR(`products`,'$d2check') > 0");
if($q154->num_rows == 0) { return array($did,date('j.n.y',strtotime($js['until']))); }else{ return false; } }else{ return false; }
}
function proinorder($mysqli,$search,$type) {
$result = array();
if($type == 0) { $q30 = $mysqli->query("SELECT * FROM `zz_order` WHERE `status`='0'");
}else{ $q30 = $mysqli->query("SELECT * FROM `zz_order`"); }
while($x = $q30->fetch_assoc()) { $oid = $x['id']; $pro = explode(",",$x['products']);
for($i = 0; $i < sizeof($pro); $i++) {
if(substr_count($pro[$i],'{') > 0) { $split = explode(":",$pro[$i]); $check = $split[1];
}else{ $check = $pro[$i]; }
if(substr_count($check,$search) > 0) { array_push($result,$oid); break; }
} }
return $result;
}
function addonmenu($mysqli,$topro,$orderid,$i) {
$noto = '';
$q20 = $mysqli->query("SELECT * FROM `zz_addons` WHERE `toproduct`='$topro' AND `orderid`='$orderid' AND `half`='$i'");
while($ij = $q20->fetch_assoc()) { $adid = explode(",",$ij['adid']);
for($x = 0; $x < sizeof($adid); $x++) {
$noto .= "'".$adid[$x]."',";
$q21 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$adid[$x]'");
while($iq = $q21->fetch_assoc()) { $id = $iq['id']; $name = $iq['name']; $img = $iq['img']; $na = $iq['needad']; $price = $iq['price'];
echo '<div class="addon'.$i.' on dragadd" id="ad'.$id.'" title=""><img src="'.$url.'/images/uploads/'.$img.'"><BR>'.$name.'<div style="font-size:8pt;">'.$price.' ₪</div></div>';
} } }
$noto = substr($noto,0,-1);
echo <<<Print
</div>
<div style="clear:both;"></div>

<fieldset><legend>תפריט תוספות:</legend>
Print;
$itemid = idbyindex($mysqli,$topro,$orderid);
$q2 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$itemid'");
$qu = $q2->fetch_assoc();
$na = $qu['needad'];
if($noto == Null) {
if($na == '{0}') { 
$q19 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `show`='1'");
}else{
$q19 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `id` IN ($na) AND `show`='1'");
}
}else{
if($na == '{0}') { 
$q19 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `id` NOT IN ($noto) AND `show`='1'");
}else{
$q19 = $mysqli->query("SELECT * FROM `zz_products` WHERE `type`='0' AND `id` NOT IN ($noto) AND `id` IN ($na) AND `show`='1'");
}
}

while($ij = $q19->fetch_assoc()) { $id = $ij['id']; $name = $ij['name']; $img = $ij['img']; $price = $ij['price'];
echo '<div class="addon'.$i.' dragadd" id="ad'.$id.'" title="גרור תוספות לפיצה"><img src="'.$url.'/images/uploads/'.$img.'"><BR>'.$name.'<div style="font-size:8pt;">'.$price.' ₪</div></div>';
}
echo <<<Print
<div class="new$i"></div>
</fieldset>
</div>
Print;
}
function idbyindex($mysqli,$topro,$orderid) {
$q1 = $mysqli->query("SELECT * FROM `zz_order` WHERE `id`='$orderid'");
$query = $q1->fetch_assoc(); $p = $query['products'];
$pid = explode(",",$p); 
if(strpos($topro,'.') !== false) {
$in = explode(".",$topro);
$d = explode(":",$pid[$in[0]]);
$pd = explode(".",substr($d[1],0,-1));
$itemid = $pd[$in[1]];
}else{
$itemid = $pid[$topro];
}
return $itemid;
}

function orderprice($mysqli,$id) {
$withaddons = 0;
$q13 = $mysqli->query("SELECT * FROM `zz_order` WHERE `id`='$id'");
$query = $q13->fetch_assoc(); $orderid = $id; $coupon = $query['coupon']; $products = explode(",",$query['products']); $completeprice = $query['price']; $deliver = $query['deliver'];

for($i = 0; $i < sizeof($products); $i++) {
if(strpos($products[$i],'{') !== false) {
$newpro = explode(":",substr($products[$i],1,-1));
$q62 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `id`='$newpro[0]'");
$qu = $q62->fetch_assoc(); $dealprod = explode(",",$qu['products']); $price = $qu['price'];
$completeprice += $price;
$dealpro = explode(".",$newpro[1]);
}else{
$dealpro = $products[$i];
}
for($x = 0; $x < sizeof($dealpro); $x++) {
if(strpos($products[$i],'{') !== false) {
$q14 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$dealpro[$x]'");
}else{
$q14 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$dealpro'");
}

while($jn = $q14->fetch_assoc()) { $id = $jn['id']; $price = $jn['price']; $needad = $jn['needad'];
$used = 1;
if($needad != '0') {
if(strpos($products[$i],'{') !== false) {
$kfn = strval($i.'.'.$x);
$q29 = $mysqli->query("SELECT * FROM `zz_addons` WHERE `orderid`='$orderid' AND `toproduct`='$kfn' ORDER BY `half`");
$pid = idbyindex($mysqli,$kfn,$orderid);
}else{
$q29 = $mysqli->query("SELECT * FROM `zz_addons` WHERE `orderid`='$orderid' AND `toproduct`='$i' ORDER BY `half`");
$pid = idbyindex($mysqli,$i,$orderid);
}
while($yt = $q29->fetch_assoc()) { $adid = explode(",",$yt['adid']); $hnum = $yt['half'];
$q130 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$pid'");
$odk = $q130->fetch_assoc(); $phalf = $odk['half']+1;
for($j = 0; $j < sizeof($adid); $j++) {
$q30 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$adid[$j]'");
$query30 = $q30->fetch_assoc(); 
if(strpos($products[$i],'{') !== false) { 
if((substr_count($dealprod[$x], "+")*$phalf) < $used) {$withaddons += $query30['price'];}
$used++;
}else{
$withaddons += $query30['price'];}
} } } } } }

$completeprice += $withaddons;
if($deliver > 0) { $completeprice += $deliver;}
$q14 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `id`='$coupon'");
$sh = $q14->fetch_assoc(); $percent = $sh['percent'];
if($percent > 0) { $completeprice = $completeprice*((100-$percent)/100); }
return number_format(round($completeprice,2),2);
}

function paidprice($mysqli,$id,$minbill) {
$q13 = $mysqli->query("SELECT * FROM `zz_order` WHERE `id`='$id'");
$query = $q13->fetch_assoc(); $paid = $query['paid']; $deliver = $query['deliver']; $dtext = '';
if($deliver > 0) { 
$completeprice += $deliver;
$dtext .= '(כלול בנוסף '.$deliver.' ₪ משלוח)';
}
$completeprice = orderprice($mysqli,$id,$deliver);

if($paid == 0) {
echo <<<Print
<div style="font-size:10pt;">סה"כ לתשלום:</div> <span style="color:#E3797D; font-weight:bold;">$completeprice ₪</span><div style="font-size:8pt;">$dtext</div>
Print;
}else{
$cp = round($completeprice-$paid,2);
if($cp <= 0) { $cp = 0; }
echo <<<Print
<div style="font-size:8pt;">לתשלום: $completeprice ₪, שולם: $paid ₪.</div> <div style="font-size:13pt;">סה"כ לתשלום:</div> <span style="color:#E3797D; font-weight:bold;">$cp ₪</span><div style="font-size:8pt;">$dtext</div>
Print;
}

}

function orderdet($mysqli,$q,$minbill,$type,$ld) {
if($type == 'side') {
echo '<div style="background:white; text-align:right; color:gray;" class="carti fourty"><div>';
echo '<div style="background:#F55D2D; font-size:14pt; color:white; font-weight:bold;" class="fourty">ההזמנה שלי</div>';
}else{
if($type != 'all' && $type != 'admin' && $type != 'adminall') {
echo '<div class="crt crtbox"><div class="cart"><div class="boxtitle">ההזמנה שלי</div>';
} }
$q13 = $mysqli->query($q);
if($q13->num_rows == 0) {
if($type == 'all' || $type == 'admin') {
echo '<div class="notice">לא נמצאו הזמנות.</div>';
}else{
echo '<div style="background:white; padding:7px 10px; font-size:11pt;">אין פריטים בהזמנה.</div></div></div>';
}
$completeprice = 0;
}else{
while($query = $q13->fetch_assoc()) { $withaddons = 0; $orderid = $query['id']; $coupon = $query['coupon']; $products = explode(",",$query['products']); $completeprice = $query['price']; $date = $query['date']; $status = $query['status']-1; $paid = $query['paid']; $loc = $query['loc']; $adress = $query['adress'];
$deliver = $query['deliver']; $dtext = "";  $buyer = $query['buyer']; $more = $query['more']; $guest = $query['guest'];
$sqltime = strtotime($date); $date = date('j.n.y - H:i',$sqltime);
if($query['products'] == '') { 
echo '<div style="background:white; padding:7px 10px; font-size:11pt;">אין פריטים בהזמנה.</div>';
}

$q14 = $mysqli->query("SELECT * FROM `zz_locations` WHERE `id`='$loc'");
$gq = $q14->fetch_assoc(); $loc = $gq['adress'].','.$gq['city']; if($loc == ',') { $loc = 'סניף לא נמצא.'; }
if($type == 'all' || $type == 'admin') {
$sguide = array("קבלת ההזמנה","הכנה","אפייה","משלוח","הזמנה הושלמה");
if($type == 'admin') { $w = 350; }else{ $w = 460; }
echo '<div class="cart allo" id="me'.$orderid.'"  style="width:'.$w.'px; float:right; margin:15px;"><div class="boxtitle"><div style="font-size:12pt; float:right; font-weight:bold;">הזמנה מספר '.$orderid.'</div><div style="font-size:11pt; float:left; margin-top:0px;">'.$date.'</div><div style="clear:both;"></div></div>';
} 

if($type == 'adminall') {
$sguide = array("קבלת ההזמנה","הכנה","אפייה","משלוח","הזמנה הושלמה");
echo '<div style="width:750px; border:1px solid #E5E5E5; overflow:hidden; margin:20px;" class="crt"><div class="cart"><div class="boxtitle" style="background:#C90628;color:white;padding:12px 10px;">הזמנה מספר '.$orderid.'</div>';
}
for($i = 0; $i < sizeof($products); $i++) {
if(strpos($products[$i],'{') !== false) {
$newpro = explode(":",substr($products[$i],1,-1));
echo '<div style="color:#798538; padding:5px; font-size:11pt; font-weight:bold;">';
$q62 = $mysqli->query("SELECT * FROM `zz_deals` WHERE `id`='$newpro[0]'");
$qu = $q62->fetch_assoc(); $dealprod = explode(",",$qu['products']); $name = $qu['name']; $price = $qu['price'];
$completeprice += $price;
echo $name.' ב- '.$price.' ₪.</div>';
$dealpro = explode(".",$newpro[1]);
}else{
$dealpro = $products[$i];
}
for($x = 0; $x < sizeof($dealpro); $x++) {
if(strpos($products[$i],'{') !== false) { $shadow = "background:#FFF7F4;";
$q14 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$dealpro[$x]'");
}else{
$q14 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$dealpro'");
}
while($jn = $q14->fetch_assoc()) { $id = $jn['id']; $name = $jn['name']; $price = $jn['price']; $img = $jn['img']; $needad = $jn['needad'];

echo '<div style="background:white;border-bottom:1px solid #E5E5E5; padding:7px 10px; font-size:11pt; '.$shadow.'" class="block">';
$shadow = '';
$used = 1;
if($type != 'admin') {
echo <<<Print
<div style="float:right;">
<img src="$url/images/uploads/$img" style="width:40px; height:30px;">
</div>
Print;
}
if($type == 'side') {
echo '<div style="float:right; margin-right:5px;" class="orderdetails">';
}else{
echo '<div style="float:right; margin-right:5px;">';
}
echo <<<Print
<b>$name</b>
<div style="color:#E3797D; font-weight:bold;">
$price ₪ 
Print;
$addlist = '';

if($needad != '0') {
if(strpos($products[$i],'{') !== false) {
$kfn = $i.'.'.$x;
$q29 = $mysqli->query("SELECT * FROM `zz_addons` WHERE `orderid`='$orderid' AND `toproduct`='$kfn' ORDER BY `half`");
$pid = idbyindex($mysqli,$kfn,$orderid);
}else{
$q29 = $mysqli->query("SELECT * FROM `zz_addons` WHERE `orderid`='$orderid' AND `toproduct`='$i' ORDER BY `half`");
$pid = idbyindex($mysqli,$i,$orderid);
}

while($yt = $q29->fetch_assoc()) { $adid = explode(",",$yt['adid']); $halflist = ''; $hnum = $yt['half'];
$q130 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$pid'");
$odk = $q130->fetch_assoc(); $phalf = $odk['half']+1;
for($j = 0; $j < sizeof($adid); $j++) {
$q30 = $mysqli->query("SELECT * FROM `zz_products` WHERE `id`='$adid[$j]'");
$query = $q30->fetch_assoc(); $halflist .= $query['name'].',';
if(strpos($products[$i],'{') !== false) {
if((substr_count($dealprod[$x], "+")*$phalf) < $used) {$withaddons += $query['price'];}
$used++;
}else{
$withaddons += $query['price'];}
}
$halflist = substr($halflist, 0, -1);
if($halflist == Null) { $halflist = '<b>ללא</b>'; }
if($hnum > 0) { $txt = 'חצי '.$hnum; }else{ $txt = 'תוספות'; }
$addlist .= '<div style="font-size:8pt;">'.$txt.': '.$halflist.'.</div>';
}
if($type == 'side') {
if(strpos($products[$i],'{') !== false) {
echo '<a href="javascript:void(0);" style="font-size:10pt; color:#93908B; margin-left:5px;" class="addonbox" id="pro'.$i.'.'.$x.'">הוסף תוספות</a>';
}else{
echo '<a href="javascript:void(0);" style="font-size:10pt; color:#93908B; margin-left:5px;" class="addonbox" id="pro'.$i.'">הוסף תוספות</a>';
} } 


}

echo <<<Print
$addlist
</div></div>
<div style="float:left; border-right:1px solid #DCD8D5; padding-right:5px;">
Print;
if(!(strpos($products[$i],'{') !== false) && $type == 'side') {
echo '<a href="javascript:void(0);" style="font-size:12pt; color:#93908B;" class="delete" id="id'.$i.'">x</a>';
} 
echo <<<Print
</div>
<div style="clear:both;"></div>
</div>
Print;
} } 
if(strpos($products[$i],'{') !== false && $type == 'side') {
echo '<div style="padding:5px 5px 5px 15px; font-size:11pt; font-weight:bold; text-align:left;"><a href="javascript:void(0);" style="font-size:10pt; text-decoration:none; color:#E03350;" class="delete" id="id'.$i.'">הסר הטבה</a></div><div style="clear:both;"></div>';
}else{
if(strpos($products[$i],'{') !== false) {
echo '<div style="background:#FEFAF7;height:15px; font-size:11pt; font-weight:bold; " class="mafrid"></div><div style="clear:both;"></div>';
} }

} 

$completeprice += $withaddons;

if($deliver > 0) { 
$completeprice += $deliver;
$dtext = '(כלול בנוסף '.$deliver.' ₪ משלוח)';
}

$q14 = $mysqli->query("SELECT * FROM `zz_coupons` WHERE `id`='$coupon'");
$sh = $q14->fetch_assoc(); $code = $sh['code']; $percent = $sh['percent'];
if($percent > 0) { 
$completeprice = $completeprice*((100-$percent)/100); 
echo '<div class="coupontext"><div style="float:right;">קופון '.$percent.'% הנחה</div>';
if($type == '') {
echo '<div style="float:left;color:red;cursor:pointer;" class="removec">הסר קופון</div>';
}else{
echo '<div style="float:left;">'.$code.'</div>';
}
echo '<div style="clear:both;"></div></div>';
}

$completeprice = number_format(round($completeprice,2),2);
if($paid == 0) {
echo <<<Print
<div style="padding:7px 10px; font-size:18pt; border-radius:0 0 5px 5px;">
<div style="float:right; margin-bottom:10px;" class="orderp">
<div style="font-size:8pt;">סה"כ לתשלום:</div> <span style="color:#E3797D; font-weight:bold;">$completeprice ₪</span><div style="font-size:8pt;">$dtext</div>
Print;
}else{
$cp = round($completeprice-$paid,2);
if($cp <= 0) { $cp = 0; }
echo <<<Print
<div style="padding:7px 10px; font-size:18pt; border-radius:0 0 5px 5px;">
<div style="float:right; margin-bottom:10px;" class="orderp">
<div style="font-size:8pt;">לתשלום: $completeprice ₪, שולם: $paid ₪.</div> <div style="font-size:13pt;">סה"כ לתשלום:</div><span style="color:#E3797D; font-weight:bold;">$cp ₪</span><div style="font-size:8pt;">$dtext</div>
Print;
}


if($type == 'side') {
echo <<<Print
</div>
<div style="float:left; text-align:left;">
<a href="$url/order?edit=true"><div class="ibutton">ערוך הזמנה</div></a><BR>
Print;
if($minbill > 0) { echo '<div style="font-size:8pt;">מינימום למשלוח: '.$minbill.' ₪.</div>'; }
if (isset($_COOKIE['phone']) || isset($_COOKIE['guest'])) {
echo '<a href="javascript:void(0);" class="clear" style="font-size:8pt;color:red;">בטל הזמנה</a>';
}
echo '</div>';
}else{
if($type == 'all' || $type == 'admin' || $type == 'adminall') {
if($paid == $completeprice) { echo '<div style="font-size:12pt;color:green;font-weight:bold;">שולם מראש</div>'; }else{ echo '<div style="font-size:12pt;">מזומן</div>'; }
echo <<<Print
</div>
<div style="padding:0 10px; font-size:12pt; float:left;" id="info$orderid">
<div style="font-size:8pt;">סטטוס הזמנה:</div>
Print;
if($status == 4) { 
echo '<span style="color:green;">'.$sguide[$status].'.</span>';
}else{
echo $sguide[$status].'.<BR>';
if($type == 'admin' || $type == 'adminall') { 
echo '<a href="javascript:void(0);" style="color:#708EAA; font-size:10pt;" id="o'.$orderid.'" class="statuschange noload">שנה ל'.$sguide[$status+1].'</a><BR><a href="javascript:void(0);" style="color:#708EAA; font-size:10pt;" id="o'.$orderid.'" class="printorder noload">הדפס הזמנה</a>';
}else{
echo '<a href="'.$url.'/follow/'.$orderid.'" style="color:#708EAA; font-size:10pt;">עקוב אחר הזמנה</a>';
}

}

}else{
if($type == 'follow' || $type == 'adminall') { echo '</div>
<div style="font-size:11pt; clear:both;"><b>סניף שהוזמן:</b> '.$loc.'.</div>
<div style="font-size:11pt; clear:both;"><b>משלוח לכתובת:</b> '.$adress.'.</div>';
}else{
echo <<<Print
</div>
<div style="float:left; text-align:left; margin:5px;">
Print;
if($type == 'pp') {
echo '<a href="javascript: window.close()"><div class="ibutton">בטל תשלום</div></a><BR>';
}else{
echo '<a href="'.$url.'/order"><div class="ibutton">חזור לעריכת הזמנה</div></a><BR>';
}
echo '</div>';
} } } echo '</div>';
if($type == 'all') {
echo '<div style="font-size:9pt; clear:both;"><b>סניף שהוזמן:</b> '.$loc.'.</div>
<div style="font-size:9pt; clear:both;"><b>משלוח לכתובת:</b> '.$adress.'.</div>';
}
echo '</div><div style="clear:both;"></div>';
if($type == 'admin' || $type == 'adminall') { 
if(substr_count($buyer,'g') == 0) {
$q41 = $mysqli->query("SELECT * FROM `zz_buyers` WHERE `phone`='$buyer'");
$pl = $q41->fetch_assoc(); $name = $pl['name'];
}else{ 
$name = $guest; $buyer = substr($buyer,1).' (אורח)';
}
echo <<<Print
<div style="padding:5px 10px; border-top: 1px solid #E5E5E5; font-size:10pt;">
<div style="float:right;">
$name - $buyer.<BR>
$adress
</div>
<div style="float:left;font-size:8pt;">
<b>סניף:</b><BR> $loc.
</div>
<div style="clear:both;"></div>
</div>
Print;
}
if($more) {
echo <<<Print
<div style="padding:5px 10px; border-top: 1px solid #E5E5E5; font-size:10pt;word-break:break-all;">
<b>הערות להזמנה:</b> $more
</div>
Print;
}
echo '</div>'; }
if($ld != 2) {
echo <<<Print
<div style="clear:both;"></div>
Print;
} }
}

?>