<?php
require_once('../config.php');
require_once('../order.php');
if($_GET['action'] == 'ipn') {
if($p->validate_ipn()) {
$amount = $_GET['amount']; $id = $_GET['id']; $needpay = orderprice($mysqli,$id);
if($amount > $needpay) { 
$mysqli->query("UPDATE `zz_order` SET `paid`='$needpay' WHERE `id`='$id'");
}else{
$mysqli->query("UPDATE `zz_order` SET `paid`=`paid`+'$amount' WHERE `id`='$id'");
}
} }
?>