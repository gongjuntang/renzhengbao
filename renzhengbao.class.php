<?php

/**
 * 客户端认证sdk 1.0
 * gongjun#renzhengbao.com 
 * http://www.renzhengbao.com
 */
class renzhengbao
{
    
    var $access_key = "renzhengbaotest";
    var $access_secret = "pass";
    var $api = "http://api.renzhengbao.com/check";
    var $error_no = '';
    public function renzhengbao($access_key='',$access_secret='')
    {
        if(!empty($access_key)&&!empty($access_secret))
        {
            $this->access_key=$access_key;
            $this->access_secret=$access_secret;

        }
    }

    /**
    *认证码验证
    */
    public function check_rcode($sn, $rcode)
    {
        if (empty($sn) || empty($rcode))
        {
            $this->error_no = -3;
            return false;
        }
        $post_data = array(
            'access_key' => $this->access_key,
            'access_secret' => $this->access_secret,
            'sn' => $sn,
            'rcode' => $rcode
        );
        $res       = $this->post_data($this->api, $post_data);
        
        if (!$res)
        {
            $this->error_no = '-4';
            return false;
        }
        $res_arr = json_decode($res, true);
        $status  = $res_arr['code'];
        if ($status < 1) //认证失败
        {
            $this->error_no = $status;
            return false;
        }
        return $status;
    }
    /**
     *获取错误代码
     */
    public function get_error_no()
    {
        
        return $this->error_no;
    }
    
    
    /**
     *获取错误原因，
     */
    public function get_error_msg()
    {
        
        switch ($this->error_no)
        {
            case '0':
                $msg = "认证码错误";
                break;
            case '-1':
                $msg = "认证失败";
                break;
            case '-2':
                $msg = "认证码过期";
                break;
            case '-3':
                $msg = "参数错误";
                break;
            case '-4':
                $msg = "curl请求失败";
                break;
            case '-5':
                $msg = "参数错误";
                break;
            case '-6':
                $msg = "access_key和access_secret验证失败";
                break;
            default:
                $msg = "未知错误,请联系contact@renzhengbao.com";
                break;
        }
        return $msg;
        
    }
    
    /**
     * curl提交数据
     */
    private function post_data($url, $data)
    {
        if ($url == '' || !is_array($data))
        {
            return false;
        }
        
        $ch = @curl_init();
        if (!$ch)
        {
            exit('内部错误：服务器不支持CURL');
        }
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERAGENT, 'renzhengbao API PHP Client/0.1');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }   
}