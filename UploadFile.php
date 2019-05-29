<?php
/**
 * 上传函数封装类
 */
class UploadFile
{

    /**
     * 错误常量
     */
    const UPLOAD_ERROR = [
        UPLOAD_ERR_INI_SIZE => '文件大小超出了php.ini当中的upload_max_filesize的值',
        UPLOAD_ERR_FORM_SIZE => '文件大小超出了MAX_FILE_SIZE的值',
        UPLOAD_ERR_PARTIAL => '文件只有部分被上传',
        UPLOAD_ERR_NO_FILE => '没有文件被上传',
        UPLOAD_ERR_NO_TMP_DIR => '找不到临时目录',
        UPLOAD_ERR_CANT_WRITE => '写入磁盘失败',
        UPLOAD_ERR_EXTENSION => '文件上传被扩展阻止',
    ];

    /**
     * @var $_FILES的name
     */
    protected $field_name;

    /**
     * @var string 存储的路径
     */
    protected $destination_dir;

    /**
     * @var array 允许的mime类型
     */
    protected $allow_mime;

    /**
     * @var array 允许的后缀
     */
    protected $allow_ext;

    /**
     * @var 文件原本的名称
     */
    protected $file_org_name;

    /**
     * @var 文件原本的类型
     */
    protected $file_type;

    /**
     * @var 临时文件名称
     */
    protected $file_tmp_name;

    /**
     * @var 错误类型
     */
    protected $file_error;

    /**
     * @var 文件大小
     */
    protected $file_size;

    /**
     * @var array 
     */
    protected $errors;

    /**
     * @var
     */
    protected $extension;

    /**
     * @var
     */
    protected $file_new_name;

    /**
     * @var float|int
     */
    protected $allow_size;

    /**
     * 构造函数，默认是上传2兆以下的照片
     * @param $keyName
     * @param string $destinationDir
     * @param array $allowMime
     * @param array $allowExt
     * @param float|int $allowSize
     */
    public function __construct($keyName, $destinationDir = './uploads', $allowMime = ['image/png','image/gif','image/jpeg'], $allowExt = ['jpg','png','gif','jpeg','JPG','GIF','PNG','JPEG'], $allowSize = 2*1024*1024)
    {
        $this->field_name = $keyName;
        $this->destination_dir = $destinationDir;
        $this->allow_mime = $allowMime;
        $this->allow_ext = $allowExt;
        $this->allow_size = $allowSize;
    }

    /**
     * @param $destinationDir
     */
    public function setDestinationDir($destinationDir)
    {
        $this->destination_dir = $destinationDir;
    }

    /**
     * @param $allowMime
     */
    public function setAllowMime($allowMime)
    {
        $this->allow_mime = $allowMime;
    }

    /**
     * @param $allowExt
     */
    public function setAllowExt($allowExt)
    {
        $this->allow_ext = $allowExt;
    }

    /**
     * @param $allowSize
     */
    public function setAllowSize($allowSize)
    {
        $this->allow_size = $allowSize;
    }

    /**
     * @return bool 真正用的上传方法
     */
    public function upload()
    {
        // 判断是否为多文件上传
        $files = [];
        if (is_array($_FILES[$this->field_name]['name'])) {
            foreach($_FILES[$this->field_name]['name'] as $k => $v) {
                $files[$k]['name'] = $v;
                $files[$k]['type'] = $_FILES[$this->field_name]['type'][$k];
                $files[$k]['tmp_name'] = $_FILES[$this->field_name]['tmp_name'][$k];
                $files[$k]['error'] = $_FILES[$this->field_name]['error'][$k];
                $files[$k]['size'] = $_FILES[$this->field_name]['size'][$k];
            }
        } else {
            $files[] = $_FILES[$this->field_name];
        }

        foreach($files as $key => $file) {
            // 接收$_FILES参数
            $this->setFileInfo($key, $file);

            // 检查错误
            $this->checkError($key);

            // 检查MIME类型
            $this->checkMime($key);

            // 检查扩展名
            $this->checkExt($key);

            // 检查文件大小
            $this->checkSize($key);

            // 生成新的文件名称
            $this->generateNewName($key);

            if (count((array)$this->getError($key)) > 0) {
                continue;
            }
            // 移动文件
            $this->moveFile($key);
        }
        if (count((array)$this->errors) > 0) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function getError($key)
    {
        return @$this->errors[$key];
    }

    /**
     *
     */
    protected function setFileInfo($key, $file)
    {
        // $_FILES  name type temp_name error size
        $this->file_org_name[$key] = $file['name'];
        $this->file_type[$key] = $file['type'];
        $this->file_tmp_name[$key] = $file['tmp_name'];
        $this->file_error[$key] = $file['error'];
        $this->file_size[$key] = $file['size'];
    }


    /**
     * @param $key
     * @param $error
     */
    protected function setError($key, $error)
    {
        $this->errors[$key][] = $error;
    }


    /**
     * @param $key
     * @return bool
     */
    protected function checkError($key)
    {
        if ($this->file_error > UPLOAD_ERR_OK) {
            switch($this->file_error) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                case UPLOAD_ERR_PARTIAL:
                case UPLOAD_ERR_NO_FILE:
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                    $this->setError($key, self::UPLOAD_ERROR[$this->file_error]);
                    return false;
            }
        }
        return true;
    }


    /**
     * @param $key
     * @return bool
     */
    protected function checkMime($key)
    {
        if (!in_array($this->file_type[$key], $this->allow_mime)) {
            $this->setError($key, '文件类型' . $this->file_type[$key] . '不被允许!');
            return false;
        }
        return true;
    }


    /**
     * @param $key
     * @return bool
     */
    protected function checkExt($key)
    {
        $this->extension[$key] = pathinfo($this->file_org_name[$key], PATHINFO_EXTENSION);
        if (!in_array($this->extension[$key], $this->allow_ext)) {
            $this->setError($key, '文件扩展名' . $this->extension[$key] . '不被允许！');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function checkSize($key)
    {
        if ($this->file_size[$key] > $this->allow_size) {
            $this->setError($key, '文件大小' . $this->file_size[$key] . '超出了限定大小' . $this->allow_size);
            return false;
        }
        return true;
    }


    /**
     * @param $key
     */
    protected function generateNewName($key)
    {
        $this->file_new_name[$key] = uniqid() . '.' . $this->extension[$key];
    }


    /**
     * @param $key
     * @return bool
     */
    protected function moveFile($key)
    {
        if (!file_exists($this->destination_dir)) {
            mkdir($this->destination_dir, 0777, true);
        }
        $newName = rtrim($this->destination_dir, '/') . '/' . $this->file_new_name[$key];
        if (is_uploaded_file($this->file_tmp_name[$key]) && move_uploaded_file($this->file_tmp_name[$key], $newName)) {
            return true;
        }
        $this->setError($key, '上传失败！');
        return false;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_new_name;
    }

    /**
     * @return string
     */
    public function getDestinationDir()
    {
        return $this->destination_dir;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->file_size;
    }

}

// 实例化对象，传入file的name
$uploads = new UploadFile('imooc');

// 设置存储路径
$uploads -> setDestinationDir('./uploads/imooc');
/* 设置MIME类型
超文本标记语言文本 .html text/html
xml文档 .xml text/xml
XHTML文档 .xhtml application/xhtml+xml
普通文本 .txt text/plain
RTF文本 .rtf application/rtf
PDF文档 .pdf application/pdf
Microsoft Word文件 .word application/msword
PNG图像 .png image/png
GIF图形 .gif image/gif
JPEG图形 .jpeg,.jpg image/jpeg
au声音文件 .au audio/basic
MIDI音乐文件 mid,.midi audio/midi,audio/x-midi
RealAudio音乐文件 .ra, .ram audio/x-pn-realaudio
MPEG文件 .mpg,.mpeg video/mpeg
AVI文件 .avi video/x-msvideo
GZIP文件 .gz application/x-gzip
TAR文件 .tar application/x-tar
任意的二进制数据 application/octet-stream
*/
$uploads -> setAllowMime(['image/png','image/gif','image/jpeg']);
// 设置允许上传的后缀名
$uploads -> setAllowExt(['jpg','png','gif','jpeg','JPG','GIF','PNG','JPEG']);
// 设置允许上传的最大字节
$uploads -> setAllowSize(2*1024*1024);

// 如果上传成功
if ($uploads -> upload()) {
    // 成功后存储的名字
    print_r($uploads->getFileName());
    // 存储路径
    print_r($uploads->getDestinationDir());
    // 存储后缀
    print_r($uploads->getExtension());
    // 存储大小
    print_r($uploads->getFileSize());
}else{
    // 失败返回错误信息  key是对应的第几个文件出错 value是错误原因，只有失败的不上传，其他的上传成功
    print_r($uploads->getErrors());
}