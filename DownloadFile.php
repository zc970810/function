<?php 

// 前端：
// <a href="download.php?file=uploads/cyf.rar">下载</a>

// 后端：
// $filename = $_GET['file'];
// DownloadFile($filename,['rar']);


/**
 * 下载文件
 * @method DownloadFile
 * @param  string    $filename     文件名或路径用get传过来
 * @param  array     $allowDownExt 允许下载的文件类型
 * @param  int       $read_buffer  每次读取多少字节
 * @return void
 */
function DownloadFile(string $filename,array $allowDownExt=array('jpeg','jpg','png','gif','txt','html','php','rar','zip'),$read_buffer=1024){
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
  header('Content-Disposition: attachment;filename=zc_'.basename($filename));

  //读取文件中的内容

  //规定每次读取文件的字节数为1024字节，直接输出数据
  // $read_buffer=1024;
  $sum_buffer=0;
  $handle=fopen($filename,'rb');
  while(!feof($handle) && $sum_buffer<$filesize){
    echo fread($handle,$read_buffer);
    $sum_buffer+=$read_buffer;
  }
  fclose($handle);
  exit;
}