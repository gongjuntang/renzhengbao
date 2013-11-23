
<?php
/**
*认证码验证
*/
//header("Content-type: text/html; charset=utf-8");
require_once "renzhengbao.class.php";
if(!empty($_POST['sn']))
{
	$sn=$_POST['sn'];
	$rcode=!empty($_POST['rcode'])?$_POST['rcode']:'123456';
	$renzhengbao=new renzhengbao();
	$res=$renzhengbao->check_rcode($sn, $rcode);
	if($res) //认证码验证成功
	{
		echo "succ,认证码正确，序列号:".$sn;
	}
	else
	{
		echo $renzhengbao->get_error_msg();
	}
	echo "<br/>";
	echo "<a href=\"javascript:history.go(-1);\">back</a>";
	exit;
}

?>
<html>
<head>
<title>认证宝动态密码验证demo</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
</head>
<body>
<div style="margin-top : 50px;	margin-left :100px;">
	<h3>认证宝动态密码认证接口演示</h3>
<form action="?check" method="post">
<p>
认证宝 app sn:<input type="text" name="sn" placeholder="例如:SQRE-2CBG-SQKN-7BT4" />(请查看app主界面上的序列号)
</p>
<p>
动态验证码:&nbsp;&nbsp; <input type="text" name="rcode" placeholder="6位数字" value="" />(请查看app上的动态密码)
</p>
<input type="submit" value="提交认证"/>
</form>

</div>
</body>
</html>