<?php

/**
*二维码会话信息查询
*/

require_once "renzhengbao.class.php";
$renzhengbao = new renzhengbao();
$token_info = $renzhengbao->get_token_info($_GET['token']);

print_r($token_info); //包含用户信息
