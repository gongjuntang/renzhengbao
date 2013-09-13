<?php
/**
 * 客户端 sdk 1.1
 * gongjun#renzhengbao.com 
 * http://www.renzhengbao.com
 */
class renzhengbao
{
    var $access_key = "weibo";
    var $access_secret = "7bd318a9b50815f374ccb944e4431d58";
    var $rcode_check_api = "http://api.renzhengbao.com/check";
    var $qrcode_init_api = "http://api.renzhengbao.com/qrcode_init";
    var $qrcode_token_api = "http://api.renzhengbao.com/qrcode_token";
    var $error_no = '';
    var $level = "H";
    var $size = "5";
    var $margin = "2";
    var $mc;
    /**
     * 构造函数,设置access_key,access_secret
     */
    public function renzhengbao($access_key = '', $access_secret = '')
    {
        if (!empty($access_key) && !empty($access_secret))
        {
            $this->access_key    = $access_key;
            $this->access_secret = $access_secret;
        }
    }
    /**
     * 认证码验证
     * @param  String $sn 认证宝app序列号
     * @param String $rcode 认证宝动态码
     * @return int $status 认证状态码,1 表示认证通过
     */
    public function check_rcode($sn, $rcode)
    {
        if (empty($sn) || empty($rcode))
        {
            $this->error_no = -3;
            return false;
        }
        $post_data         = array(
            'access_key' => $this->access_key,
            'sn' => $sn,
            'rcode' => $rcode
        );
        $post_data['sign'] = $this->create_sign($post_data);
        $res               = $this->post_data($this->rcode_check_api, $post_data);
        if (!$res)
        {
            $this->error_no = '-5';
            return false;
        }
        $res_arr = json_decode($res, true);
        $status  = $res_arr['stat'];
        if ($status < 1) //认证失败
        {
            //            echo $res; eixt;
            $this->error_no = $status;
            return false;
        }
        return $status;
    }
    /**
     *获取错误代码
     * @return $error_no 上一次api调用的错误代码
     */
    public function get_error_no()
    {
        return $this->error_no;
    }
    /**
     * 获取错误原因
     * @return $msg 上一次调用api的错误代码中文含义
     */
    public function get_error_msg()
    {
        switch ($this->error_no)
        {
            case '1':
                $msg = "操作成功";
                break;
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
                $msg = "数据校验失败";
                break;
            case '-5':
                $msg = "curl请求失败";
                break;
            default:
                $msg = $this->error_no . "未定义错误代码,请联系contact@renzhengbao.com";
                break;
        }
        return $msg;
    }
    /**
     * curl提交数据
     * @param String $url 提交地址
     * @param Array $data 提交数据
     * @return String 请求结果
     */
    private function post_data($url, $data)
    {
        if ($url == '' || !is_array($data))
        {
            return false;
        }
        if (!function_exists("curl_init"))
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
    /**
     * 初始化二维码信息
     * @return Array 返回二维码信息,包含token,二维码图片url,有效时间
     */
    public function init_qrcode()
    {
        $data         = array(
            'access_key' => $this->access_key,
            'level' => $this->level,
            'size' => $this->size,
            'margin' => $this->margin,
            'limit' => 1,
            'timestamp' => time(),
            'nonce' => substr(md5(rand(10000, 99999) . microtime()), 10, 5)
        );
        $data['sign'] = $this->create_sign($data);
        $url          = $this->qrcode_init_api . "?" . http_build_query($data);
        $res          = file_get_contents($url);
        if (!$res)
        {
            $this->error_no = '-5';
            return false;
        }
        $res_arr = json_decode($res, true);
        $status  = $res_arr['stat'];
        if ($status < 1) //认证失败
        {
            $this->error_no = $status;
            return false;
        }
        if ($this->memcache_init() && !empty($res_arr['data'])) //数据放入memcache
        {
            foreach ($res_arr['data'] as $line)
            {
                memcache_set($this->mc, $line['token'], -1, $line['expire_time'] - time());
            }
        }
        return $res_arr['data'];
    }
    /**
     * 查询某一个二维码状态
     * @param String $token 会话token
     * @return -1，未扫描，false 失效了，string 当前sn
     */
    public function get_token_sn($token)
    {
        if (empty($token))
            return false;
        if ($this->memcache_init()) //memcache中获取
        {
            $sn = memcache_get($this->mc, $token);
            if ($sn > 0)
            {
                return $sn;
            }
        }
        $info = $this->get_token_info($token);
        if (empty($info) || $info['expire_time'] < time()) //云端没有信息，页面刷新吧
        {
            return 0;
        }
        return $info['sn'] ? $info['sn'] : -1;
    }
    /**
     *从认证宝云端查询一条token的信息
     * @param String $token 会话token
     * @return false/Array 服务端的token查询结果
     */
    public function get_token_info($token)
    {
        $data         = array(
            'access_key' => $this->access_key,
            'nonce' => substr(md5(rand(1000, 9999) . microtime()), 10, 5),
            'timestamp' => time(),
            'token' => $token
        );
        $data['sign'] = $this->create_sign($data);
        $url          = $this->qrcode_token_api . "?" . http_build_query($data);
        // echo $url;
        $res          = file_get_contents($url);
        $res_arr      = json_decode($res, true);
        $status       = $res_arr['stat'];
        if ($status == 1)
        {
            return $res_arr['data'];
        }
        return false;
    }
    /**
     * 接收认证宝推送的数据，需要认证宝配置推送地址
     */
    public function recv_token($data)
    {
        // print_r($data);
        if (empty($data['sn']) || empty($data['token']) || empty($data['sign']))
        {
            return false;
        }
        if (!$this->check_sign($data))
        {
            return false;
        }
        if ($this->get_token_sn($data['token']) < 0) //存在token，并且未扫描,记录日志，写入库
        {
            if ($this->memcache_init())
            {
                memcache_set($this->mc, $data['token'], $data['sn']);
            }
            return $data['token'];
        }
        return true;
    }
    /**
     * 生成签名
     * @param Array $data 签名的数据源
     * @return String $sign 签名结果
     */
    public function create_sign($data)
    {
        ksort($data);
        $data_str = http_build_query($data);
        // echo $data_str . $this->access_secret;
        $sign     = md5($data_str . $this->access_secret);
        return $sign;
    }
    /**
     * 签名检查
     * @param Array $data 数据集
     * @return bool 校验结果   
     */
    public function check_sign($data)
    {
        $sign = trim($data['sign']);
        if (empty($data['sign']))
        {
            return false;
        }
        $access_key = $data['access_key'];
        unset($data['sign']);
        ksort($data);
        $data_str      = http_build_query($data);
        $access_secret = $this->access_secret;
        $check_sign    = md5($data_str . $access_secret);
        return ($sign === $check_sign) ? true : false;
    }
    /**
     * memcache init
     */
    private function memcache_init()
    {
        if (!function_exists("memcache_init"))
        {
            return false;
        }
        $this->mc = @memcache_init();
        if ($this->mc == false)
        {
            return false;
        }
        return $this->mc;
    }
}
