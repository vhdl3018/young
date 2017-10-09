<?php
/*
 * 工具：验证码类
 * 设置：静态类，单一方法调用生成验证码。Code::code();
 * 验证码相关参数配置，通过config.php文件自动配置。
 * 验证码参数：
 *  'CODE_LEN'              => 4,
 *  'CODE_WIDTH'            => 70,
 *  'CODE_HEIGHT'           => 40,
 *  'CODE_TYPE'             => 1,       //0----纯数字，1----纯字母，2----数字与字母混合。
 *
 */
class Code{
    private $img=null;
    public $codeLen;
    public $codeWidth;
    public $codeHeight;
    public $codeType;
    public $codeStr;
    public $code;
    public $codeBg;


    //初始化验证码参数配置项
    public function __construct(){
        //将验证码参数，写入到类的属性
        foreach (C('CODE') as $k=>$v){
            $this->$k = $v;
        }
        
    }


    public function create(){

        //判断是否启gd库
        if (!$this->checkGD()) return false;
        //配置画布的大小
        $w = $this->codeWidth;
        $h = $this->codeHeight;

        //配置画布的背景颜色
        $bgColor = $this->codeBg;
        $gbk = $this->hexTogbk($bgColor);

        //创建画布资源
        $img = imagecreatetruecolor($w,$h);
        $bg = imagecolorallocate($img, $gbk['red'], $gbk['green'], $gbk['blue']);
        //给画布创建白色背景
        imagefill($img, $w, $h, $bg);
        $this->img = $img;

    }

    /**
     * 显示验证码，并输出成图片
     */
    public function show(){
        $this->create();
        header("Content-Type: image/png");
        imagepng($this->img);
        imagedestroy($this->img);
        exit;
    }

    //生成随机的验证码
    private function getCode(){
        //验证码初始化
        $code = '';
        //获取指定长度的随机验证码
        $code = substr(str_shuffle($this->codeStr), mt_rand($this->codeLen,strlen($this->codeStr))-$this->codeLen , $this->codeLen);
        //将获取的验证码，保存到了属性中
        $this->code = $code;
        //将获取的验证码，放到session中，方便登录验证使用
        if (C('SESSION_AUTO_START')) $_SESSION['code'] = $this->code;
    }

    /**
     * @return array
     */
    private function hexTogbk($bg){
        $gbk = array();
        $gbk['red'] = hexdec(substr($bg, 1, 2));
        $gbk['green'] = hexdec(substr($bg, 3, 2));
        $gbk['blue'] = hexdec(substr($bg, 5, 2));
        return $gbk;
    }

    /**
     * 验证GD库是不否打开imagepng函数是否可用
     */
    private function checkGD() {
        return extension_loaded('gd') && function_exists("imagepng");
    }
}

?>