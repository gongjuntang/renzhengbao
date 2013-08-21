认证宝
===========

认证宝sdk 1.0

##认证码验证接口

1. 接口地址：http://api.renzhengbao.com/check 
2. 请求方式：POST

##输入参数说明

1. access_key: 申请接入认证宝服务的唯一标识号 
2. access_secret:申请接入认证宝服务的验证 
3. sn:用户app序列号
4. rcode:认证码，6位数字

##返回数据


{"code":1}


##返回代码解析

1. 1验证成功
2. 0验证失败 
3. -1认证失败 
4. -2认证码过期 
5. -3参数错误 
6. -4access_key或者access_secret验证失败

##请求实例


    
    curl -X POST http://api.renzhengbao.com/check -d 'access_key=weibo&access_secret=pass&sn=SQRE-2CBG-SQKN-7BT4&rcode=123456'
    

##联系我们

如果有什么疑问或者建议请联系：contact@renzhengbao.com
