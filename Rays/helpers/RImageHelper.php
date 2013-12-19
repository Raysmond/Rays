<?php
/**
 * RImageHelper class file.
 *
 * @author: Raysmond
 */

class RImageHelper
{

    /**
     * Generate a style of image from image source, the result image will match the style described by $option.
     * For example:
     * <pre>
     * $src = "public/images/pic.jpg";
     * $styleSrc = RImageHelper::styleSrc($src,array('path'=>'files/images/styles','name'=>'pic_style','width'=>200,'height'=>200));
     * // a new image file with size 200x200 will be generated in path files/images/styles/pic_style
     * // $styleSrc = "files/images/styles/pic_style/pic.jpg";
     * </pre>
     * @param $src image source
     * @param array $options target image attributes
     * @param bool $updateStyle whether or not update the style directly(delete old target style image file)
     * @return string the target image source or the original image source if there's error.
     */
    public static function styleSrc($src, $options = array(), $updateStyle = false)
    {
        if (!isset($src) || !isset($options['name']) || !isset($options['width']) || !isset($options['height'])) {
            return $src;
        }

        // TODO: fix directory settings
        $dir = Rays::app()->getBaseDir() . '/../' . $options['path'];
        $srcPath = Rays::app()->getBaseDir() . '/../' . $src;
        $name = self::getName($srcPath) . self::getExtension($srcPath);
        $styleDir = $dir . '/' . $options['name'];

        if (!file_exists($styleDir)) {
            mkdir($styleDir);
        }

        $stylePath = $styleDir . '/' . $name;
        $targetSrc = $options['path'] . '/' . $options['name'] . '/' . $name;

        if (!$updateStyle && file_exists($stylePath)) {
            return $targetSrc;
        } else {
            if (file_exists($stylePath)) {
                unlink($stylePath);
            }
            $img = new Img ();
            return $img->thumb_img($srcPath, $stylePath, $options) ? $targetSrc : $src;
        }

    }

    /**
     * Update the target image style file
     * @param $src
     * @param array $options
     * @return string target image style source
     */
    public static function updateStyle($src, $options = array())
    {
        return static::styleSrc($src, $options, true);
    }

    public static function getExtension($filename)
    {
        $x = explode('.', $filename);
        return '.' . end($x);
    }

    public static function getName($filename)
    {
        $x = explode('/', $filename);
        return preg_replace('/\.[a-zA-Z]+$/', '', end($x));
    }

}

// TODO
class RImage{
    private $extensions = ['jpg','jpeg','gif','png','bmp'];

    public function __construct(){
        if(!function_exists('gd_info')){
            throw new RException("GD library is not supported in current PHP environment.");
        }
    }

    public function crop($srcImg,$desImg,$options){

    }
}

/**
 *
 * 图像处理类
 * @author FC_LAMP
 * @internal功能包含：水印,缩略图
 */
class Img
{
    //图片格式
    private $exts = array('jpg', 'jpeg', 'gif', 'bmp', 'png');

    /**
     *
     *
     * @throws Exception
     */
    public function __construct()
    {
        if (!function_exists('gd_info')) {
            throw new Exception ('加载GD库失败！');
        }
    }

    /**
     *
     * 裁剪压缩
     * @param $src_img 图片
     * @param $save_img 生成后的图片
     * @param $option 参数选项，包括： $maxwidth  宽  $maxheight 高
     * array('width'=>xx,'height'=>xxx)
     * @internal
     * 我们一般的压缩图片方法，在图片过长或过宽时生成的图片
     * 都会被“压扁”，针对这个应采用先裁剪后按比例压缩的方法
     */
    public function thumb_img($src_img, $save_img = '', $option)
    {

        if (empty ($option ['width']) or empty ($option ['height'])) {
            return array('flag' => False, 'msg' => '原图长度与宽度不能小于0');
        }
        $org_ext = $this->is_img($src_img);
        if (!$org_ext ['flag']) {
            return $org_ext;
        }

        //如果有保存路径，则确定路径是否正确
        if (!empty ($save_img)) {
            $f = $this->check_dir($save_img);
            if (!$f ['flag']) {
                return $f;
            }
        }

        //获取出相应的方法
        $org_funcs = $this->get_img_funcs($org_ext ['msg']);

        //获取原大小
        $source = $org_funcs ['create_func'] ($src_img);
        $src_w = imagesx($source);
        $src_h = imagesy($source);

        //调整原始图像(保持图片原形状裁剪图像)
        $dst_scale = $option ['height'] / $option ['width']; //目标图像长宽比
        $src_scale = $src_h / $src_w; // 原图长宽比
        if ($src_scale >= $dst_scale) { // 过高
            $w = intval($src_w);
            $h = intval($dst_scale * $w);

            $x = 0;
            $y = ($src_h - $h) / 3;
        } else { // 过宽
            $h = intval($src_h);
            $w = intval($h / $dst_scale);

            $x = ($src_w - $w) / 2;
            $y = 0;
        }
        // 剪裁
        $croped = imagecreatetruecolor($w, $h);
        imagecopy($croped, $source, 0, 0, $x, $y, $src_w, $src_h);
        // 缩放
        $scale = $option ['width'] / $w;
        $target = imagecreatetruecolor($option ['width'], $option ['height']);
        $final_w = intval($w * $scale);
        $final_h = intval($h * $scale);
        imagecopyresampled($target, $croped, 0, 0, 0, 0, $final_w, $final_h, $w, $h);
        imagedestroy($croped);

        //输出(保存)图片
        if (!empty ($save_img)) {

            $org_funcs ['save_func'] ($target, $save_img);
        } else {
            header($org_funcs ['header']);
            $org_funcs ['save_func'] ($target);
        }
        imagedestroy($target);
        return array('flag' => True, 'msg' => '');
    }

    /**
     *
     * 等比例缩放图像
     * @param $src_img 原图片
     * @param $save_img 需要保存的地方
     * @param $option 参数设置 array('width'=>xx,'height'=>xxx)
     *
     */
    function resize_image($src_img, $save_img = '', $option)
    {
        $org_ext = $this->is_img($src_img);
        if (!$org_ext ['flag']) {
            return $org_ext;
        }

        //如果有保存路径，则确定路径是否正确
        if (!empty ($save_img)) {
            $f = $this->check_dir($save_img);
            if (!$f ['flag']) {
                return $f;
            }
        }

        //获取出相应的方法
        $org_funcs = $this->get_img_funcs($org_ext ['msg']);

        //获取原大小
        $source = $org_funcs ['create_func'] ($src_img);
        $src_w = imagesx($source);
        $src_h = imagesy($source);

        if (($option ['width'] && $src_w > $option ['width']) || ($option ['height'] && $src_h > $option ['height'])) {
            if ($option ['width'] && $src_w > $option ['width']) {
                $widthratio = $option ['width'] / $src_w;
                $resizewidth_tag = true;
            }

            if ($option ['height'] && $src_h > $option ['height']) {
                $heightratio = $option ['height'] / $src_h;
                $resizeheight_tag = true;
            }

            if ($resizewidth_tag && $resizeheight_tag) {
                if ($widthratio < $heightratio)
                    $ratio = $widthratio;
                else
                    $ratio = $heightratio;
            }

            if ($resizewidth_tag && !$resizeheight_tag)
                $ratio = $widthratio;
            if ($resizeheight_tag && !$resizewidth_tag)
                $ratio = $heightratio;

            $newwidth = $src_w * $ratio;
            $newheight = $src_h * $ratio;

            if (function_exists("imagecopyresampled")) {
                $newim = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newim, $source, 0, 0, 0, 0, $newwidth, $newheight, $src_w, $src_h);
            } else {
                $newim = imagecreate($newwidth, $newheight);
                imagecopyresized($newim, $source, 0, 0, 0, 0, $newwidth, $newheight, $src_w, $src_h);
            }
        }
        //输出(保存)图片
        if (!empty ($save_img)) {

            $org_funcs ['save_func'] ($newim, $save_img);
        } else {
            header($org_funcs ['header']);
            $org_funcs ['save_func'] ($newim);
        }
        imagedestroy($newim);
        return array('flag' => True, 'msg' => '');
    }

    /**
     *
     * 生成水印图片
     * @param  $org_img 原图像
     * @param  $mark_img 水印标记图像
     * @param  $save_img 当其目录不存在时，会试着创建目录
     * @param array $option 为水印的一些基本设置包含：
     * x:水印的水平位置,默认为减去水印图宽度后的值
     * y:水印的垂直位置,默认为减去水印图高度后的值
     * alpha:alpha值(控制透明度),默认为50
     */
    public function water_mark($org_img, $mark_img, $save_img = '', $option = array())
    {
        //检查图片
        $org_ext = $this->is_img($org_img);
        if (!$org_ext ['flag']) {
            return $org_ext;
        }
        $mark_ext = $this->is_img($mark_img);
        if (!$mark_ext ['flag']) {
            return $mark_ext;
        }
        //如果有保存路径，则确定路径是否正确
        if (!empty ($save_img)) {
            $f = $this->check_dir($save_img);
            if (!$f ['flag']) {
                return $f;
            }
        }

        //获取相应画布
        $org_funcs = $this->get_img_funcs($org_ext ['msg']);
        $org_img_im = $org_funcs ['create_func'] ($org_img);

        $mark_funcs = $this->get_img_funcs($mark_ext ['msg']);
        $mark_img_im = $mark_funcs ['create_func'] ($mark_img);

        //拷贝水印图片坐标
        $mark_img_im_x = 0;
        $mark_img_im_y = 0;
        //拷贝水印图片高宽
        $mark_img_w = imagesx($mark_img_im);
        $mark_img_h = imagesy($mark_img_im);

        $org_img_w = imagesx($org_img_im);
        $org_img_h = imagesx($org_img_im);

        //合成生成点坐标
        $x = $org_img_w - $mark_img_w;
        $org_img_im_x = isset ($option ['x']) ? $option ['x'] : $x;
        $org_img_im_x = ($org_img_im_x > $org_img_w or $org_img_im_x < 0) ? $x : $org_img_im_x;
        $y = $org_img_h - $mark_img_h;
        $org_img_im_y = isset ($option ['y']) ? $option ['y'] : $y;
        $org_img_im_y = ($org_img_im_y > $org_img_h or $org_img_im_y < 0) ? $y : $org_img_im_y;

        //alpha
        $alpha = isset ($option ['alpha']) ? $option ['alpha'] : 50;
        $alpha = ($alpha > 100 or $alpha < 0) ? 50 : $alpha;

        //合并图片
        imagecopymerge($org_img_im, $mark_img_im, $org_img_im_x, $org_img_im_y, $mark_img_im_x, $mark_img_im_y, $mark_img_w, $mark_img_h, $alpha);

        //输出(保存)图片
        if (!empty ($save_img)) {

            $org_funcs ['save_func'] ($org_img_im, $save_img);
        } else {
            header($org_funcs ['header']);
            $org_funcs ['save_func'] ($org_img_im);
        }
        //销毁画布
        imagedestroy($org_img_im);
        imagedestroy($mark_img_im);
        return array('flag' => True, 'msg' => '');

    }

    /**
     *
     * 检查图片
     * @param unknown_type $img_path
     * @return array('flag'=>true/false,'msg'=>ext/错误信息)
     */
    private function is_img($img_path)
    {
        if (!file_exists($img_path)) {
            return array('flag' => False, 'msg' => "加载图片 $img_path 失败！");
        }
        $ext = explode('.', $img_path);
        $ext = strtolower(end($ext));
        if (!in_array($ext, $this->exts)) {
            return array('flag' => False, 'msg' => "图片 $img_path 格式不正确！");
        }
        return array('flag' => True, 'msg' => $ext);
    }

    /**
     *
     * 返回正确的图片函数
     * @param unknown_type $ext
     */
    private function get_img_funcs($ext)
    {
        //选择
        switch ($ext) {
            case 'jpg' :
                $header = 'Content-Type:image/jpeg';
                $createfunc = 'imagecreatefromjpeg';
                $savefunc = 'imagejpeg';
                break;
            case 'jpeg' :
                $header = 'Content-Type:image/jpeg';
                $createfunc = 'imagecreatefromjpeg';
                $savefunc = 'imagejpeg';
                break;
            case 'gif' :
                $header = 'Content-Type:image/gif';
                $createfunc = 'imagecreatefromgif';
                $savefunc = 'imagegif';
                break;
            case 'bmp' :
                $header = 'Content-Type:image/bmp';
                $createfunc = 'imagecreatefrombmp';
                $savefunc = 'imagebmp';
                break;
            default :
                $header = 'Content-Type:image/png';
                $createfunc = 'imagecreatefrompng';
                $savefunc = 'imagepng';
        }
        return array('save_func' => $savefunc, 'create_func' => $createfunc, 'header' => $header);
    }

    /**
     *
     * 检查并试着创建目录
     * @param $save_img
     */
    private function check_dir($save_img)
    {
        $dir = dirname($save_img);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                return array('flag' => False, 'msg' => "图片保存目录 $dir 无法创建！");
            }
        }
        return array('flag' => True, 'msg' => '');
    }
}

