
<?php
/**
*认证码验证
*/

require_once "renzhengbao.class.php";
$sn="SQRE-2CBG-SQKN-7BT4";
if(!empty($_GET['sn']))
{
	$sn=$_GET['sn'];
}
$rcode=!empty($_GET['code'])?$_GET['code']:'123456';

$renzhengbao=new renzhengbao();
$res=$renzhengbao->check_rcode($sn, $rcode);
if($res) //认证码验证成功
{

	echo "succ";
}
else
{

	echo $renzhengbao->get_error_msg();
}