<?php

namespace App\Services;

use Cache;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Wechat
{
    private $appId;
    private $mchId;
    private $token;
    private $appSecret;
    private $wechatkey;

    public function __construct() {
        $this->appId = config('wechat.appid');
        $this->mchId = config('wechat.mchid');
        $this->token = config('wechat.token');
        $this->appSecret = config('wechat.appsecret');
        $this->wechatkey = config('wechat.key');
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getMchId()
    {
        return $this->mchId;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    public function getAccessToken()
    {
        // 若缓存里没有 access_token 则从微信服务器获取
        if ( ! Cache::has('access_token')) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s";
            $url = sprintf($url, $this->appId, $this->appSecret);

            $json = $this->getCurlTransfer($url);
            $params = json_decode($json);

            if (isset($params->errcode)) {
                die ('微信接口调用错误，' . $params->errcode . ': ' . $params->errmsg);
            }

            Cache::put('access_token', $params->access_token,
                ($params->expires_in / 60) - 10);  // 缓存1小时50分钟
        }

        return Cache::get('access_token');
    }

    public function getMenu()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s";
        $url = sprintf($url, $this->getAccessToken());

        $json = $this->getCurlTransfer($url);

        return $json;
    }

    public function createMenu($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s";
        $url = sprintf($url, $this->getAccessToken());

       // $data = Storage::get('../storage/app/menu.txt');
        return $this->postCurlTransfer($url, $data);
    }

    private function getCurlTransfer($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);

        return $str;
    }

    private function postCurlTransfer($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $str = curl_exec($curl);
        curl_close($curl);

        return $str;
    }

    public function receive($xml)
    {
        $dom = simplexml_load_string($xml);

        switch ($dom->MsgType) {
            case 'text':
                    if($dom->Content=="红包"){
                        return $this->replyText($dom->FromUserName,
                            $dom->ToUserName,
                            $dom->CreateTime,
                            $dom->MsgType,
                            "欢迎您使用红包功能123");
                    }else{
                        return $this->replyText($dom->FromUserName,
                            $dom->ToUserName,
                            $dom->CreateTime,
                            $dom->MsgType,
                            $dom->Content);
                    }

                break;
            case 'event':
             if($dom->Event=="subscribe"){

                 return $this->replyText($dom->FromUserName,
                     $dom->ToUserName,
                     $dom->CreateTime,
                     "text",
                     "欢迎关注");

             }

            break;


            default:
                return '';
        }
    }

    private function replyText($toUserName, $fromUserName, $createTime, $msgType, $content)
    {
        $xml = "
        <xml>
        <ToUserName><![CDATA[$toUserName]]></ToUserName>
        <FromUserName><![CDATA[$fromUserName]]></FromUserName>
        <CreateTime>$createTime</CreateTime>
        <MsgType><![CDATA[$msgType]]></MsgType>
        <Content><![CDATA[$content]]></Content>
        </xml>";

        return $xml;
    }

    public function getJsApiTicket()
    {
        if ( ! Cache::has('jsapi_ticket')) {
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=%s";
            $url = sprintf($url, $this->getAccessToken());

            $json = json_decode($this->getCurlTransfer($url));

            Cache::put('jsapi_ticket', $json->ticket, ($json->expires_in / 60) - 10);
        }

        return Cache::get('jsapi_ticket');
    }

    private function getJsApiSignature($url)
    {
        $params = array(
            'noncestr' => md5(Session::get('jsapi_timestamp')),
            'jsapi_ticket' => $this->getJsApiTicket(),
            'timestamp' => Session::get('jsapi_timestamp'),
            'url' => $url,
        );

        ksort($params);

        return rawurldecode(http_build_query($params));
    }

    public function getJsConfig($url)
    {
        $config = array(
            'debug' => false,
            'appId' => $this->appId,
            'timestamp' => Session::get('jsapi_timestamp'),
            'nonceStr' => md5(Session::get('jsapi_timestamp')),
            'signature' => sha1($this->getJsApiSignature($url)),
            'jsApiList' => array(
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'onMenuShareQZone',
                'getNetworkType',
                'openLocation',
                'getLocation',
                'chooseImage',
                'uploadImage',
                'previewImage',
                'downloadImage',
                'scanQRCode',
            ),
        );

        return json_encode($config, JSON_UNESCAPED_SLASHES);
    }

    public function getJsApiAccessToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";
        $url = sprintf($url, $this->appId, $this->appSecret, $code);

        return $this->getCurlTransfer($url);
    }

    public function getUserInfo($openid, $accessToken)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN";
        $url = sprintf($url, $accessToken, $openid);

        return $this->getCurlTransfer($url);
    }

    public function getAddrConfig($accessToken, $url)
    {
        $timestamp = Session::get('jsapi_timestamp');

        $signature = array(
            'appid' => $this->appId,
            'url' => $url,
            'timestamp' => $timestamp,
            'noncestr' => $timestamp,
            'accesstoken' => $accessToken,
        );

        ksort($signature);
        $addrSign = sha1(rawurldecode(http_build_query($signature)));

        $params = array(
            'appId' => $this->appId,
            'scope' => 'jsapi_address',
            'signType' => 'SHA1',
            'addrSign' => $addrSign,
            'timeStamp' => "$timestamp",
            'nonceStr' => "$timestamp",
        );

        return json_encode($params);
    }
    //发送模板消息
    public function sendtpl($openid, $url, $template_id, $content, $topcolor="#FF0000")
    {
        $arr = array(
            'touser' => $openid,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => $topcolor,
            'data' => $content,
        );

        $arrjson = json_encode($arr);

        $accesstoken = $this->getAccessToken();
        $sendurl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accesstoken;

        return $this->postCurlTransfer($sendurl, $arrjson);
    }
    //发起支付
    public function sendpay($openid, $title, $out_trade_no, $total_fee, $notify_url, $attach = '')
    {
        $time = time();

          $arr = array(
                'appid' => $this->appId,
                'mch_id' => $this->mchId,
                'nonce_str' => md5($time),
                'body'=>$title,
                'out_trade_no'=>$out_trade_no,
                'total_fee' =>$total_fee,
                'spbill_create_ip' =>"127.0.0.1",
                'notify_url' => $notify_url,
                'trade_type' => "JSAPI",
                'openid' => $openid,
                'attach' => $attach,
          );
          $biaozhi ='sign';
       $arr = $this->createsign($arr,$biaozhi);

        //echo $this->wechatkey;

        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {

                $xml.="<".$key.">".$val."</".$key.">";

        }
        $xml.="</xml>";



       $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";

      $resxml =  $this->postCurlTransfer($url,$xml);

      $res = simplexml_load_string($resxml);


       $cutime = time();
      $jsapiarr=array(

            'appId'=>$this->appId,
            'timeStamp'=>"$cutime",
            'nonceStr'=>md5($cutime),
            'package'=>"prepay_id=".$res->prepay_id,
            'signType'=>"MD5",
      );
      $biaozhi = 'paySign';
      $jsapi = $this->createsign($jsapiarr,$biaozhi);
      $jsapijson = json_encode($jsapi);
      return $jsapijson;
    }
    //生成大写签名
    function createsign($arr,$biaozhi){
         ksort($arr);

         $string="";
         $i=1;
        foreach($arr as $key=>$val){

            if($i==1){

                $string.=$key."=".$val;
            }else{
                $string.="&".$key."=".$val;

            }

            $i++;

        }

     $signtemp = "$string&key=".$this->wechatkey;
     $sign =strtoupper(MD5($signtemp));
     $arr[$biaozhi] = $sign;
     //$json = json_encode($arr);
     return $arr;

    }

    public function refund($out_trade_no,$out_refund_no,$total_fee,$refund_fee){


        $time = time();



            $arr = array(

                    'appid' =>$this->appId,
                    'mch_id' =>$this->mchId,
                    'nonce_str'=>md5($time),
                    'out_trade_no' =>$out_trade_no,
                    'out_refund_no' =>$out_refund_no,
                    'total_fee'=>$total_fee,
                    'refund_fee'=>$refund_fee,
                    'op_user_id'=>$this->mchId,
            );


            $biaozhi = 'sign';
            $arrtemp = $this->createsign($arr,$biaozhi);


            $xml = "<xml>";
            foreach ($arrtemp as $key=>$val)
            {

                    $xml.="<".$key.">".$val."</".$key.">";

            }
            $xml.="</xml>";
            $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
            $res = $this->postssl($url,$xml);


            $dom = simplexml_load_string($res);

            if($dom) {

                $code = $dom->return_code;

                $result = $dom->result_code;
                if(strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']=1;
                    $ret['message']="success";
                    return $ret;

                } else {
                    $error = $dom->err_code_des;
                    $ret['code']=-2;
                    $ret['message']=$error->__toString();
                    return $ret;
                }
            } else {
                $ret['code']=-3;
                $ret['message']="error response";
                return $ret;
            }

            // $data = $this->postssl('https://api.mch.weixin.qq.com/secapi/pay/refund', 'merchantid=1001000');
            // print_r($data);


    }
    //企业向用户付款
    public function transfers($openid, $amount, $des, $partner_trade_no)
    {
        $time = time();
        $arr = array(
            'mch_appid'         => $this->appId,
            'mchid'             => $this->mchId,
            'nonce_str'         => md5($time),
            'partner_trade_no'  => $partner_trade_no,
            'openid'            => $openid,
            'check_name'        => 'NO_CHECK',
            'amount'            => $amount * 100,
            'desc'              => $des,
            'spbill_create_ip'  =>'127.0.0.1',
            //$_SERVER['SERVER_ADDR'],
            //122.114.100.240
        );

        $biaozhi = 'sign';
        $arrtemp = $this->createsign($arr, $biaozhi);

        $xml = "<xml>";
        foreach ($arrtemp as $key => $val) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        }
        $xml .= "</xml>";

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $res = $this->postssl($url, $xml);

        $dom = simplexml_load_string($res);

        if ($dom) {
            $code = $dom->return_code;
            $result = $dom->result_code;

            if (strtolower($code) == 'success' && strtolower($result) == 'success') {
                $ret['code'] = 1;
                $ret['message'] = "success";
            } else {
                $error = $dom->err_code_des;
                $ret['code'] = -2;
                $ret['message'] = $error->__toString();
            }
        } else {
            $ret['code'] = -3;
            $ret['message'] = "error response";
        }

        return $ret;
    }

    public function postssl($url, $xml){

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            //因为微信红包在使用过程中需要验证服务器和域名，故需要设置下面两行
        //    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 只信任CA颁布的证书
        //    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配

            curl_setopt($ch,CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, storage_path('app/apiclient_cert.pem'));
             curl_setopt($ch,CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, storage_path('app/apiclient_key.pem'));
            // curl_setopt($ch, CURLOPT_CAINFO, '/var/www/paimai/storage/app/rootca.pem'); // CA根证书（用来验证的网站证书是否是CA颁布）


            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;


    }
    //保存微信图片
    public function getmedia($media_id, $file, $picname)
    {
        $foldername = public_path() . $file;

        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $this->getAccessToken() . "&media_id=" . $media_id;

        if (!file_exists($foldername)) {
            mkdir($foldername, 0777, true);
        }

        $targetName = $foldername . '/' . $picname;

        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $pic = $file . $picname;
        //$this->img2thumb($targetName,$foldername."thumb_".$picname,75,75,0,0);
        // $ret = $this->img2thumb($targetName,$foldername."thumb_".$picname,75,75,0,0);

        list($width, $height, $type, $attr) = getimagesize($targetName);

        if ($width > 300 && $height > 300) {
            if ($width > $height) {
                $ratio = $height / 300;
                $thumbHeight = 300;
                $thumbWidth = $width / $ratio;
            } elseif ($width < $height) {
                $ratio = $width / 300;
                $thumbWidth = 300;
                $thumbHeight = $height / $ratio;
            } elseif ($width == $height) {
                $thumbWidth = 300;
                $thumbHeight = 300;
            }
        } else {
            $thumbWidth = $width;
            $thumbHeight = $height;
        }

        $ret = $this->mkThumbnail($targetName, $thumbWidth, $thumbHeight, $foldername . "thumb_" . $picname);

        if ($width > 100 && $height > 100) {
            if ($width > $height) {
                $ratio = $height / 100;
                $thumbHeight = 100;
                $thumbWidth = $width / $ratio;
            } elseif ($width < $height) {
                $ratio = $width / 100;
                $thumbWidth = 100;
                $thumbHeight = $height / $ratio;
            } elseif ($width == $height) {
                $thumbWidth = 100;
                $thumbHeight = 100;
            }
        } else {
            $thumbWidth = $width;
            $thumbHeight = $height;
        }

        $ret = $this->mkThumbnail($targetName, $thumbWidth, $thumbHeight, $foldername . "thumb__" . $picname);

        return $pic;
    }
    //发送客服消息
    public function sendkf($data)
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $accessToken;

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $res = $this->postCurlTransfer($url,$json);

        return $res;
    }

    private function mkThumbnail($src, $width = null, $height = null, $filename = null)
    {
        if (!isset($width) && !isset($height))
            return false;
        if (isset($width) && $width <= 0)
            return false;
        if (isset($height) && $height <= 0)
            return false;

        $size = getimagesize($src);
        if (!$size)
            return false;

        list($src_w, $src_h, $src_type) = $size;
        $src_mime = $size['mime'];

        switch($src_type) {
            case 1 :
                $img_type = 'gif';
                break;
            case 2 :
                $img_type = 'jpeg';
                break;
            case 3 :
                $img_type = 'png';
                break;
            case 15 :
                $img_type = 'wbmp';
                break;
            default :
                return false;
        }

        if (!isset($width))
            $width = $src_w * ($height / $src_h);
        if (!isset($height))
            $height = $src_h * ($width / $src_w);

        $imagecreatefunc = 'imagecreatefrom' . $img_type;
        $src_img = $imagecreatefunc($src);
        $dest_img = imagecreatetruecolor($width, $height);
        imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

        $imagefunc = 'image' . $img_type;
        if ($filename) {
            $imagefunc($dest_img, $filename);
        } else {
            header('Content-Type: ' . $src_mime);
            $imagefunc($dest_img);
        }

        imagedestroy($src_img);
        imagedestroy($dest_img);

        return true;
    }
}
