<?php

/**
*二维码会话信息查询
*/

if(empty($_GET['token']))
{
	die("0");
}
require_once "renzhengbao.class.php";
$renzhengbao = new renzhengbao();
$token_info = $renzhengbao->get_token_info($_GET['token']);
if(!$token_info)
{
	die("0");
}

if(!empty($token_info['sn'])) //已经扫描
{

echo "<p>认证宝sn:",$token_info['sn'],"</p>";
echo "<p>二维码过期时间:",date("Y-m-d H:i:s",$token_info['expire_time']),"</p>";
echo "<p>认证时间:",date("Y-m-d H:i:s",$token_info['recv_time']),"</p>";
echo "<p>客户端ip:",$token_info['recv_ip'],"</p>";
//echo "<p>推送时间:",$token_info['send_time'],"</p>";

}
else //没有人扫描
{
	echo "-1";
}