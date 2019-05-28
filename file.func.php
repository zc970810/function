<?php
/*
// 创建文件，可自定义目录
create_file($filename); 

// 删除文件
del_file($filename);

// 拷贝文件,没有目录会自己创建
copy_file($filename,'指定目录');

// 重命名
rename_file($oldname,$newname)

// 剪切文件,没有目录会自己创建
cut_file($filename,'指定目录');

// 获取文件信息，返回数组，包括创建时间、修改时间、最后访问时间、大小，文件类型
get_file_info($filename);

// 字节转换，根据大小自适应b kb mb gb tb
trans_byte(字节,[精度，默认保留两位小数]);

// 读取文件内容，返回字符串
read_file($filename);

// 读取文件中的内容到数组中,按行读取
read_file_array($filename,是否过滤空行);

// 向文件中写入内容，如果是数字或对象会序列化数据
write_file($filename,$data)

// 向文件中写入内容，之前内容不清空，如果是数字或对象会序列化数据
write_file1($filename,$data)

// 截断文件到指定大小
truncate_file($filename,int $length);

// 下载文件
down_file($filename,array 允许下载类型);

// 下载大文件，会分块下载，建议使用这个
down_file1($filename,array 允许下载类型);


// 单文件上传，返回文件上传后的名字
upload_file(array 上传文件信息, ['文件上传路径',是否检测为真实照片默认检测,array 允许上传文件类型默认为照片, int 最大文件字节默认2mb]);

// 压缩单个文件
zip_file($filename);

// 多文件压缩
zip_files(最后生成压缩包的名字,需要压缩的文件名字...);

// 解压文件
unzip_file($zipName,解压路径)

<!-- 下面是关于目录的操作 --> 

// 检测目录是否为空
check_empty_dir($path)

// 回目录下所有内容,返回数组
read_directory($path)

// 读取目录中的所有文件，包括内部文件夹下的文件，不建议使用
get_all_files($path)

// 得到目录大小
get_dir_size($path)

// 重命名目录
rename_dir($oldName,$newName)

// 剪切目录
cut_dir($src,$dst)

// 拷贝目录
copy_dir($src,$dst)

// 删除非空目录
del_dir($path)
*/


/**
 * 创建文件操作
 * @method create_file
 * @param  string      $filename 文件名
 * @return boolean                true|false
 */
function create_file(string $filename){
  //检测文件是否存在，不存在则创建
  if(file_exists($filename)){
    return false;
  }
  //检测目录是否存在，不存在则创建
  if(!file_exists(dirname($filename))){
    //创建目录，可以创建多级
    mkdir(dirname($filename),0777,true);
  }
  // if(touch($filename)){
  //   return true;
  // }
  // return false;
  if(file_put_contents($filename,'')!==false){
    return true;
  }
  return false;
}
// var_dump(create_file('2.txt'));
// var_dump(create_file('a/4.txt'));

/**
 * 删除文件操作
 * @method del_file
 * @param  string   $filename 文件名
 * @return boolean             true|false
 */
function del_file(string $filename){
  //检测删除的文件是否存在,并且是否有权限操作
  if(!file_exists($filename)||!is_writable($filename)){
    return false;
  }
  if(unlink($filename)){
    return true;
  }
  return false;
}
// var_dump(del_file('a/3.txt'));

/**
 * 拷贝文件操作
 * @method copy_file
 * @param  string    $filename 文件名
 * @param  string    $dest     指定目录
 * @return boolean              true|false
 */
function copy_file(string $filename,string $dest){
  //检测$dest是否是目标并且这个目录是否存在，不存在则创建
  if(!is_dir($dest)){
    mkdir($dest,0777,true);
  }
  $destName=$dest.DIRECTORY_SEPARATOR.basename($filename);
  //检测目标路径下是否存在同名文件
  if(file_exists($destName)){
    return false;
  }
  //拷贝文件
  if(copy($filename,$destName)){
    return true;
  }
  return false;
}
// var_dump(copy_file('2.txt','a'));

/**
 * 重命名操作
 * @method rename_file
 * @param  string      $oldName 原文件
 * @param  string      $newName 新文件名
 * @return boolean               true|false
 */
function rename_file(string $oldName,string $newName){
  //检测原文件并且存在
  if(!is_file($oldName)){
    return false;
  }
  //得到原文件所在的路径
  $path=dirname($oldName);
  $destName=$path.DIRECTORY_SEPARATOR.$newName;
  if(is_file($destName)){
    return false;
  }
  if(rename($oldName,$newName)){
    return true;
  }
  return false;
}
// var_dump(rename_file('2.txt','333.txt'));

/**
 * 剪切文件操作
 * @method cut_file
 * @param  string   $filename 原文件
 * @param  string   $dest     目标路径
 * @return boolean             true|false
 */
function cut_file(string $filename,string $dest){
  if(!is_file($filename)){
    return false;
  }
  if(!is_dir($dest)){
    mkdir($dest,0777,true);
  }
  $destName=$dest.DIRECTORY_SEPARATOR.basename($filename);
  if(is_file($destName)){
    return false;
  }
  if(rename($filename,$destName)){
    return true;
  }
  return false;
}
// var_dump(cut_file('333.txt','a'));
// var_dump(cut_file('22.txt','a'));


/**
 * 返回文件信息
 * @method get_file_info
 * @param  string        $filename 文件名
 * @return mixed                  文件信息相关数组|false
 */
function get_file_info(string $filename){
  if(!is_file($filename)||!is_readable($filename)){
    return false;
  }
  return [
    'atime'=>date("Y-m-d H:i:s",fileatime($filename)),
    'mtime'=>date("Y-m-d H:i:s",filemtime($filename)),
    'ctime'=>date("Y-m-d H:i:s",filectime($filename)),
    'size'=>trans_byte(filesize($filename)),
    'type'=>filetype($filename)
  ];
}
// var_dump(get_file_info('22.txt'));

/**
 * 字节单位转换的函数
 * @method trans_byte
 * @param  int        $byte      字节
 * @param  integer    $precision 默认精度，保留小数点后2位
 * @return string                转换之后的字符串
 */
function trans_byte($byte,int $precision=2){
  $kb=1024;
  $mb=1024*$kb;
  $gb=1024*$mb;
  $tb=1024*$gb;
  if($byte<$kb){
    return $byte.'B';
  }elseif($byte<$mb){
    return round($byte/$kb,$precision).'KB';
  }elseif($byte<$gb){
    return round($byte/$mb,$precision).'MB';
  }elseif($byte<$tb){
    return round($byte/$gb,$precision).'GB';
  }else{
    return round($byte/$tb,$precision).'TB';
  }
}
// var_dump(trans_byte(12345678));


/**
 * 读取文件内容，返回字符串
 * @method read_file
 * @param  string    $filename 文件名
 * @return mixed              文件内容|false
 */
function read_file(string $filename){
  //检测是否是一个文件并且文件已存在
  if(is_file($filename) && is_readable($filename)){
    return file_get_contents($filename);
  }
  return false;
}
// var_dump(read_file('232.txt'));

/**
 * 读取文件中的内容到数组中
 * @method read_file_array
 * @param  string          $filename         文件名
 * @param  boolean         $skip_empty_lines 是否过滤空行
 * @return mixed                            array|false
 */
function read_file_array(string $filename,bool $skip_empty_lines=false){
  if(is_file($filename)&&is_readable($filename)){
    if($skip_empty_lines){
      return file($filename,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
    }else{
      return file($filename);
    }
  }
  return false;
}
// var_dump(read_file_array('22.txt',true));


/**
 * 向文件中写入内容
 * @method write_file
 * @param  string     $filename 文件名
 * @param  mixed      $data     数据，数组和对象需要处理
 * @return boolean               true|false
 */
function write_file(string $filename,$data){
  $dirname=dirname($filename);
  //检测目标路径是否存在
  if(!file_exists($dirname)){
    mkdir($dirname,0777,true);
  }
  //判断内容是否是数组或者对象
  if(is_array($data)||is_object($data)){
    //序列化数据
    $data=serialize($data);
  }
  //向文件中写入内容
  if(file_put_contents($filename,$data)!==false){
    return true;
  }else{
    return false;
  }
}
// var_dump(write_file('22.txt','abcdsdflkjsdlkfj'));
// var_dump(write_file('b/1.txt','hello king'));
// var_dump(write_file('c/1.txt',['a','b','c']));


/**
 * 向文件中写入内容，之前内容不清空
 * @method write_file1
 * @param  string      $filename  文件名
 * @param  mixed       $data      数据
 * @param  boolean     $clearFlag 是否清空文件
 * @return boolean                 true|false
 */
function write_file1(string $filename,$data,bool $clearFlag=false){
  $dirname=dirname($filename);
  //检测目标路径是否存在
  if(!file_exists($dirname)){
    mkdir($dirname,0777,true);
  }
  //检测文件是否存在并且可读
  if(is_file($filename)&&is_readable($filename)){
    //读取文件内容，之后和新写入的内容拼装到一起
    if(filesize($filename)>0){
      $srcData=file_get_contents($filename);
    }
  }

  //判断内容是否是数组或者对象
  if(is_array($data)||is_object($data)){
    //序列化数据
    $data=serialize($data);
  }
  //拼装到一起
  $data=$srcData.$data;
  //向文件中写入内容
  if(file_put_contents($filename,$data)!==false){
    return true;
  }else{
    return false;
  }
}
// var_dump(write_file1('22.txt','hello world'));

/**
 * 截断文件到指定大小
 * @method truncate_file
 * @param  string        $filename 文件名
 * @param  int           $length   长度
 * @return boolean                 true|false
 */
function truncate_file(string $filename,int $length){
  //检测是否是文件
  if(is_file($filename)&&is_writable($filename)){
    $handle=fopen($filename,'r+');
    $length=$length<0?0:$length;
    ftruncate($handle,$length);
    fclose($handle);
    return true;
  }
  return false;
}
// var_dump(truncate_file('22.txt',2));


/**
 * 下载文件
 * @method down_file
 * @param  string    $filename     文件名
 * @param  array     $allowDownExt 允许下载的文件类型
 * @return void
 */
function down_file(string $filename,array $allowDownExt=array('jpeg','jpg','png','gif','txt','html','php','rar','zip')){
  //检测下载文件是否存在，并且可读
  if(!is_file($filename)||!is_readable($filename)){
    return false;
  }
  //检测文件类型是否允许下载
  $ext=strtolower(pathinfo($filename,PATHINFO_EXTENSION));
  if(!in_array($ext,$allowDownExt)){
    return false;
  }
  //通过header()发送头信息
  //告诉浏览器输出的是字节流
  header('Content-Type:application/octet-stream');

  //告诉浏览器返回的文件大小是按照字节进行计算的
  header('Accept-Ranges: bytes');

  //告诉浏览器返回的文件大小
  header('Accept-Length: '.filesize($filename));

  //告诉浏览器文件作为附件处理，告诉浏览器最终下载完的文件名称
  header('Content-Disposition: attachment;filename=king_'.basename($filename));

  //读取文件中的内容
  readfile($filename);
  exit;

}

/**
 * 下载文件
 * @method down_file
 * @param  string    $filename     文件名
 * @param  array     $allowDownExt 允许下载的文件类型
 * @return void
 */
function down_file1(string $filename,array $allowDownExt=array('jpeg','jpg','png','gif','txt','html','php','rar','zip')){
  //检测下载文件是否存在，并且可读
  if(!is_file($filename)||!is_readable($filename)){
    return false;
  }
  //检测文件类型是否允许下载
  $ext=strtolower(pathinfo($filename,PATHINFO_EXTENSION));
  if(!in_array($ext,$allowDownExt)){
    return false;
  }
  //通过header()发送头信息

  //告诉浏览器输出的是字节流
  header('Content-Type:application/octet-stream');

  //告诉浏览器返回的文件大小是按照字节进行计算的
  header('Accept-Ranges: bytes');

  $filesize=filesize($filename);
  //告诉浏览器返回的文件大小
  header('Accept-Length: '.$filesize);

  //告诉浏览器文件作为附件处理，告诉浏览器最终下载完的文件名称
  header('Content-Disposition: attachment;filename=king_'.basename($filename));

  //读取文件中的内容

  //规定每次读取文件的字节数为1024字节，直接输出数据
  $read_buffer=1024;
  $sum_buffer=0;
  $handle=fopen($filename,'rb');
  while(!feof($handle) && $sum_buffer<$filesize){
    echo fread($handle,$read_buffer);
    $sum_buffer+=$read_buffer;
  }
  fclose($handle);
  exit;
}

/**
 * 单文件上传
 * @method upload_file
 * @param  array       $fileInfo   上传文件的信息，是一个数组
 * @param  string      $uploadPath 文件上传默认路径
 * @param  boolean     $imageFlag  是否检测真实图片
 * @param  array       $allowExt   允许上传的文件类型
 * @return mixed                  成功返回文件最终保存路径及名称，失败返回false
 */
function upload_file(array $fileInfo,string $uploadPath='./uploads',bool $imageFlag=true,array $allowExt=array('jpeg','jpg','png','gif'),int $maxSize=2097152){
  define('UPLOAD_ERRS',[
    'upload_max_filesize'=>'超过了PHP配置文件中upload_max_filesize选项的值',
    'form_max_size'=>'超过了表单MAX_FILE_SIZE选项的值',
    'upload_file_partial'=>'文件部分被上传',
    'no_upload_file_select'=>'没有选择上传文件',
    'upload_system_error'=>'系统错误',
    'no_allow_ext'=>'非法文件类型',
    'exceed_max_size'=>'超出允许上传的最大值',
    'not_true_image'=>'文件不是真实图片',
    'not_http_post'=>'文件不是通过HTTP POST方式上传上来的',
    'move_error'=>'文件移动失败'
  ]);

  //检测是否上传是否有错误
  if($fileInfo['error']===UPLOAD_ERR_OK){
    //检测上传文件类型
    $ext=strtolower(pathinfo($fileInfo['name'],PATHINFO_EXTENSION));
    if(!in_array($ext,$allowExt)){
      echo  UPLOAD_ERRS['no_allow_ext'];
      return false;
    }
    //检测上传文件大小是否符合规范
    if($fileInfo['size']>$maxSize){
      echo UPLOAD_ERRS['exceed_max_size'];
      return false;
    }
    //检测是否是真实图片
    if($imageFlag){
      if(@!getimagesize($fileInfo['tmp_name'])){
        echo UPLOAD_ERRS['not_true_image'];
        return false;
      }
    }
    //检测文件是否通过HTTP POST方式上传上来的
    if(!is_uploaded_file($fileInfo['tmp_name'])){
      return UPLOAD_ERRS['not_http_post'];
    }
    //检测目标目录是否存在，不存在则创建
    if(!is_dir($uploadPath)){
      mkdir($uploadPath,0777,true);
    }
    //生成唯一文件名，防止重名产生覆盖
    $uniName=md5(uniqid(microtime(true),true)).'.'.$ext;
    $dest=$uploadPath.DIRECTORY_SEPARATOR.$uniName;

    //移动文件
    if(@!move_uploaded_file($fileInfo['tmp_name'],$dest)){
      echo UPLOAD_ERRS['move_error'];
      return false;
    }
    echo '文件上传成功';
    return $dest;
  }else{
    switch($fileInfo['error']){
      case 1:
      // $mes='超过了PHP配置文件中upload_max_filesize选项的值';
      $mes=UPLOAD_ERRS['upload_max_filesize'];
      break;
      case 2:
      $mes=UPLOAD_ERRS['form_max_size'];
      break;
      case 3:
      $mes=UPLAOD_ERRS['upload_file_partial'];
      break;
      case 4:
      $mes=UPLOAD_ERRS['no_upload_file_select'];
      break;
      case 6:
      case 7:
      case 8:
      $mes=UPLAOD_ERRS['upload_system_error'];
      break;
    }
    echo $mes;
    return false;
  }
}
/**
 * 压缩单个文件
 * @method zip_file
 * @param  string   $filename 文件名
 * @return boolean             true|false
 */
function zip_file(string $filename){
  if(!is_file($filename)){
    return false;
  }
  $zip=new ZipArchive();
  $zipName=basename($filename).'.zip';
  //打开指定压缩包，不存在则创建，存在则覆盖
  if($zip->open($zipName,ZipArchive::CREATE|ZipArchive::OVERWRITE)){
    //将文件添加到压缩包中
    if($zip->addFile($filename)){
      $zip->close();
      @unlink($filename);
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}
// var_dump(zip_file('22.txt'));
// func_get_args
// test1.zip
/**
 * 多文件压缩
 * @method zip_files
 * @param  string    $zipName 压缩包的名称，.zip结尾
 * @param  string     $files   需要压缩文件名，可以是多个
 * @return boolean             true|false
 */
function zip_files(string $zipName,...$files){
  //检测压缩包名称是否正确
  $zipExt=strtolower(pathinfo($zipName,PATHINFO_EXTENSION));
  if('zip'!==$zipExt){
    return false;
  }
  $zip=new ZipArchive();
  if($zip->open($zipName,ZipArchive::CREATE|ZipArchive::OVERWRITE)){
    foreach($files as $file){
      if(is_file($file)){
        $zip->addFile($file);
      }
    }
    $zip->close();
    return true;
  }else{
    return false;
  }
}
// var_dump(zip_files('test1.zip','22.txt'));
// var_dump(zip_files('test2.zip','doUpload.php','downLoad.html','upload.html'));

/**
 * 解压缩
 * @method unzip_file
 * @param  string     $zipName 压缩包名称
 * @param  string     $dest    解压到指定目录
 * @return boolean              true|false
 */
function unzip_file(string $zipName,string $dest){
  //检测要解压压缩包是否存在
  if(!is_file($zipName)){
    return false;
  }
  //检测目标路径是否存在
  if(!is_dir($dest)){
    mkdir($dest,0777,true);
  }
  $zip=new ZipArchive();
  if($zip->open($zipName)){
    $zip->extractTo($dest);
    $zip->close();
    return true;
  }else{
    return false;
  }
}
// var_dump(unzip_file('test2.zip','a'));


/**
 * 检测目录是否为空
 * @method check_empty_dir
 * @param  string          $path 目录名
 * @return boolean         true|false
 */
function check_empty_dir(string $path){
  //检测目录是否存在，存在则打开
  if(!is_dir($path)){
    return false;
  }
  //打开指定目录
  $handle=opendir($path);
  //读取
  while(($item=@readdir($handle))!==false){
    //去掉.和..操作
    if($item!='.'&&$item!='..'){
      return false;
    }
  }
  //关闭句柄
  closedir($handle);
  return true;
}
// var_dump(check_empty_dir('a'));
// var_dump(check_empty_dir('d'));




/**
 * 返回目录下所有内容
 * @method read_directory1
 * @param  string          $path 目录名称
 * @return mixed            false|array
 */
function read_directory(string $path){
  if(!is_dir($path)){
    return false;
  }
  $handle=opendir($path);
  while(($item=@readdir($handle))!==false){
    if($item!='.'&&$item!='..'){
      $pathName=$path.DIRECTORY_SEPARATOR.$item;
      if(is_file($pathName)){
        $arr['file'][]=$pathName;
      }elseif(is_dir($pathName)){
        $arr['dir'][]=$pathName;
      }
    }
  }
  closedir($handle);
  return $arr;
}
// $res=read_directory1('a');
// print_r($res);


/**
 * 读取目录中的所有文件，包括内部文件夹下的文件，不建议使用
 * @method get_all_files
 * @param  string        $path 目录名称
 * @return mixed              false|array
 */
function get_all_files(string $path){
  if(!is_dir($path)){
    return false;
  }
  if($handle=opendir($path)){
    $res=[];
    while(($item=readdir($handle))!==false){
      if($item!='.'&&$item!='..'){
        $pathName=$path.DIRECTORY_SEPARATOR.$item;
        is_dir($pathName)?$res[$pathName]=get_all_files($pathName):$res[]=$pathName;
      }
    }
    closedir($handle);
    return $res;
  }else{
    return false;
  }
}

// print_r(get_all_files('a'));
/**
 * 得到目录大小
 * @method get_dir_size
 * @param  string       $path 目录名称
 * @return mixed             false|int
 */
function get_dir_size(string $path){
  if(!is_dir($path)){
    return false;
  }
  static $sum=0;
  $handle=opendir($path);
  while(($item=readdir($handle))!==false){
    if($item!='.'&&$item!='..'){
      $pathName=$path.DIRECTORY_SEPARATOR.$item;
      if(is_file($pathName)){
        $sum+=filesize($pathName);
      }else{
        $func=__FUNCTION__;
        $func($pathName);
      }
    }
  }
  closedir($handle);
  return $sum;
}
// echo get_dir_size('a');
/**
 * 重命名目录
 * @method rename_dir
 * @param  string     $oldName 原目录
 * @param  string     $newName 新目录
 * @return boolean              true|false
 */
function rename_dir(string $oldName,string $newName){
  //检测原文件是否存在,或者当前目录下存在同名目录
  $dest=dirname($oldName).DIRECTORY_SEPARATOR.$newName;
  if(!is_dir($oldName)|| file_exists($dest)){
    return false;
  }
  if(rename($oldName,$dest)){
    return true;
  }
  return false;
}
//重命名
// var_dump(rename_dir('a','aaaa'));
// var_dump(rename_dir('b','c'));
/**
 * 剪切目录
 * @method cut_dir
 * @param  string  $src 原目录
 * @param  string  $dst 新目录位置
 * @return boolean       true|false
 */
function cut_dir(string $src,string $dst){
  //检测原目录是否存在，不存在返回false
  if(!is_dir($src)){
    return false;
  }
  //检测目录路径是否存在，不存在则创建
  if(!is_dir($dst)){
    mkdir($dst,755,true);
  }
  //检测目录路径下是否存在同名目录
  $dest=$dst.DIRECTORY_SEPARATOR.basename($src);
  if(is_dir($dest)){
    return false;
  }
  //剪切
  if(rename($src,$dest)){
    return true;
  }
  return false;
}
// var_dump(cut_dir('d','uploads'));
// var_dump(cut_dir('c','test1'));
// var_dump(cut_dir('test1','test2'));
/**
 * 拷贝目录操作
 * @method copy_dir
 * @param  string   $src 原目录
 * @param  string   $dst 目标路径
 * @return boolean        true|false
 */
function copy_dir(string $src,string $dst){
  //检测原目录是否存在
  if(!is_dir($src)){
    return false;
  }
  //检测目标目录是否存在，不存在则创建
  if(!is_dir($dst)){
    mkdir($dst,755,true);
  }
  //检测目标目录下是否存在同名文件
  $dest=$dst.DIRECTORY_SEPARATOR.basename($src);
  if(is_dir($dest)){
    return false;
  }
  $handle=opendir($src);
  while(($item=@readdir($handle))!==false){
    if($item!='.'&&$item!='..'){
      if(is_file($src.DIRECTORY_SEPARATOR.$item)){
        copy($src.DIRECTORY_SEPARATOR.$item,$dst.DIRECTORY_SEPARATOR.$item);
      }
      if(is_dir($src.DIRECTORY_SEPARATOR.$item)){
        $func=__FUNCTION__;
        $func($src.DIRECTORY_SEPARATOR.$item,$dst.DIRECTORY_SEPARATOR.$item);
      }
    }
  }
  closedir($handle);
  return true;
}
// var_dump(copy_dir('uploads','test2/uploads'));

/**
 * 删除非空目录
 * @method del_dir
 * @param  string  $path 目录名称
 * @return boolean        true|false
 */
function del_dir(string $path){
  //检测目录是否存在
  if(!is_dir($path)){
    return false;
  }
  $handle=opendir($path);
  while(($item=@readdir($handle))!==false){
    if($item!='.'&&$item!='..'){
      $pathName=$path.DIRECTORY_SEPARATOR.$item;
      if(is_file($pathName)){
        @unlink($pathName);
      }else{
        $func=__FUNCTION__;
        $func($pathName);
      }
    }
  }
  closedir($handle);
  rmdir($path);
  return true;
}
