<?php
/**
 * RUploadHelper class file
 *
 * @author: Raysmond
 */

class RUploadHelper
{

    public $allowed_types = '';
    public $file_name = '';
    public $original_file_name = '';
    public $file_temp;
    public $file_type = '';
    public $file_size = 0;
    public $file_extention = '';
    public $upload_path = '';
    public $allow_override = false;
    public $error;

    public function __construct($config = array())
    {
        if (!empty($config)) {
            $this->init($config);
        }
    }

    public function init($config = array())
    {
        $properties = array(
            'allowed_types' => '',
            'file_name' =>'',
            'original_file_name'=>'',
            'file_temp'=>'',
            'file_type'=>'',
            'file_size'=>0,
            'file_extention'=>'',
            'upload_path'=>'',
            'allow_override'=>false,
        );
        foreach($properties as $key=>$val){
            if(isset($config[$key])){
                $this->$key = $config[$key];
            }
            else{
                $this->$key = $val;
            }
        }
    }

    public function upload($field='userfile')
    {
        if(!isset($_FILES[$field])){
            $this->error = 'No upload file selected';
            return false;
        }

        if(!is_dir($this->upload_path)){
            $this->error = 'Upload directory not exists.('.$this->upload_path.')';
            return false;
        }
        $this->file_temp = $_FILES[$field]['tmp_name'];
        $this->file_size = $_FILES[$field]['size'];
        $this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        $this->original_file_name = ($_FILES[$field]['name']);
        if(!isset($this->file_name)||$this->file_name==''){
            $this->file_name = $this->original_file_name;
        }
        $this->file_extention = self::get_extension($this->original_file_name);
        if($this->upload_path[strlen($this->upload_path)-1]!='/'){
            $this->upload_path = $this->upload_path.'/';
        }
        if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name))
        {
            if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name))
            {
                $this->error = 'Move file to directory failed.';
                return false;
            }
        }
        return true;
    }

    public static function get_extension($filename)
    {
        $x = explode('.', $filename);
        return '.'.end($x);
    }

    public static function get_name($file_name) {
        return preg_replace('/\.[a-zA-Z]+$/', '', $file_name);
    }
}