认证宝
===========

认证宝sdk 1.0

##认证码验证接口

接口地址：http://api.renzhengbao.com/check 请求方式：POST
##输入参数说明

access_key: 申请接入认证宝服务的唯一标识号 access_secret:申请接入认证宝服务的验证 sn:用户app序列号 rcode:认证码，6位数字
##返回数据

{"code":1}
##返回代码解析

1验证成功 
0验证失败 
-1认证失败 
-2认证码过期 
-3参数错误 
-4access_key或者access_secret验证失败
##请求实例

curl -X POST http://api.renzhengbao.com/check -d 'access_key=weibo&access_secret=pass&sn=SQRE-2CBG-SQKN-7BT4&rcode=123456'
##联系我们

如果有什么疑问或者建议请联系：contact@renzhengbao.com
