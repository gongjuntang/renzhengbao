<?php

/**
* demo 
*/
require_once "renzhengbao.class.php";
$sn="SQRE-2CBG-SQKN-7BT4";
$rcode=$_GET['code'];
$renzhengbao=new renzhengbao("renzhengbaotest","pass");
$res=$renzhengbao->check_rcode($sn, $rcode);
if($res) //认证码验证成功
{

	echo "succ";
}
else
{

	echo $renzhengbao->get_error_msg();
}

?>

