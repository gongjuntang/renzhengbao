<?php

/**
*二维码初始化
*/
 
require_once "renzhengbao.class.php";
$renzhengbao = new renzhengbao();
$res         = $renzhengbao->init_qrcode();
if (!$res) //获取到二维码结果
{
	echo	$msg = $renzhengbao->get_error_msg();
	exit;
}

?>
<html>
<head>
<title>二维码认证演示-认证宝</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
 <script src="http://www.renzhengbao.com/js/jquery.js"></script>
</head>
<body>
<div style="margin-top:50px;text-align:center">
	<h3>认证宝二维码认证演示</h3>
      <p style="text-align:center">请使用认证宝app扫描下面二维码 <a target="blank" href="http://www.renzhengbao.com/img/home/reg_help_content.jpg">查看帮助</a></p>
						<div  id="qrcode">
						<p>
							<img src="<?php echo $res[0]['url'];?>"/>
						</p>
						<p>还没有安装认证宝?</p>
						 <p>
						 	<a href="https://itunes.apple.com/cn/app/ren-zheng-bao/id721186193?mt=8" target="_blank" ><img style="width:30px;" src="http://www.renzhengbao.com/img/home/download-ios.png"/></a> 
						 	<a href="http://www.renzhengbao.com/" target="_blank" ><img style="width:30px;" src="http://www.renzhengbao.com/img/home/download-android.png"/></a>
						 </p>
						</div>

</div>
<script type="text/javascript">
    function check_qrcode_info()
    {
      var token='<?php echo $res[0]['token'];?>';
      var login_url="qrcode_info.php?token="+token;
      $.get(login_url, function(data){
        if(data==0)
        {

          top.location.reload();
        	return ;
        }
        else if(data==-1)
        {
        	return ;
        }
        $("#qrcode").html(data);
      });

    }
    window.setInterval(check_qrcode_info, 2000); 


    </script>

</body>
</html>