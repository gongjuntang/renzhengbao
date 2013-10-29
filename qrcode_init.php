<?php

/**
*二维码初始化
*/
print_r($_GET);
require_once "renzhengbao.class.php";
$renzhengbao = new renzhengbao();
$res         = $renzhengbao->init_qrcode();
 print_r($res);
if ($res) //获取到二维码结果
{
    print_r($res);  
    //$res=$res[0];
}
else
{

	$msg = $renzhengbao->get_error_msg();
}
