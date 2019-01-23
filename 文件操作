<?php 
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('PRC');
/**
 * create_file 							创建文件
 * @param  string 		$filemane		要创建的文件名
 * @return mixed 						true|失败信息
 */
function create_file(string $filename)
{
	// 检测文件是否存在
	if (is_file($filename)) {
		echo "文件已存在，不可重复创建";
		return false;
	}
	// 如果添加文件带路径，要判断文件夹是否存在，不存在则创建
	if (!is_dir(dirname($filename))) {
		// mkdir函数创建文件夹，0777是默认最大访问权限，true是允许添加子目录
		mkdir(dirname($filename),0777,true);
	}
	// 通过写入一个空字符串创建文件
	if (file_put_contents($filename,'')!==false) {
		return true;
	}else {
		echo "创建失败";
		return false;
	}
}
// create_file('a/3.txt');


/**
 * del_file 							删除文件
 * @param  string 		$filename 		删除的文件名
 * @return mixed 						true|失败信息
 */
function del_file(string $filename)
{
	// 检测文件是否存在
	if (!is_file($filename)) {
		echo "文件不存在";
		return false;
	}
	if (!is_writable($filename)) {
		echo "您没有删除权限";
		return false;
	}
	if (unlink($filename)) {
		return true;
	}else {
		echo "删除失败";
		return false;
	}
}
// del_file('a/3.txt');

/**
 * copy_file 								复制文件
 * @param  string 			$filename 		源文件名字
 * @param  string 			$dest 			要复制的目录，只写到目录名，不要上带文件名
 * @param  bool 			$dot 			默认false，当$dest需要创建时候文件名不能带点'.',目的判断填写的$dest是目录还是文件名，如果你非要创建带点的文件夹则输入true
 * @return mixed 							true|失败信息
 */
function copy_file(string $filename,string $dest,bool $dot=false)
{
	// 检测文件是否存在
	if (!is_file($filename)) {
		echo "文件不存在";
		return false;
	}
	// 检测要复制进的文件夹是否存在，不存在则创建
	if (!is_dir($dest)) {
		// mkdir($dest,0777,true);
		
		if ($dot) {
			// 如果你非要给文件名加上点
			mkdir($dest,0777,true);
		} else {
			// 这里默认是判断$dest中是否存在'.'，存在点就可能是个文件，而不是文件夹
			if (strpos(basename($dest),'.')) {
				echo "只输入要拷贝进的文件夹名字即可";
				return false;
			}else{
				mkdir($dest,0777,true);
			}
		}
	}
	// 新文件的路径
	$newName = $dest.DIRECTORY_SEPARATOR.basename($filename);
	// 判断$dest中是否存在同名文件
	if (is_file($newName)) {
		echo "该文件加内已有同名文件";
		return false;
	}
	if (copy($filename,$newName)) {
		return true;
	}else {
		echo "拷贝失败";
		return false;
	}
}
// create_file('3.txt');
// copy_file('a3.txt','a.a');


/**
 * rename_file 							重命名文件
 * @param  string 		$oldName 		旧名字
 * @param  string 		$newName 		新名字
 * @return mixed 						true|失败信息
 */
function rename_file(string $oldName,string $newName)
{
	// 检测文件是否存在
	if (!is_file($oldName)) {
		echo "要重命名的文件不存在";
		return false;
	}
	// 定义newName的路径，这样重命名时候新名字不用写目录了
	$dataName = dirname($oldName).DIRECTORY_SEPARATOR.$newName;
	// 检测改后文件是否与已存在文件重名
	if (file_exists($dataName)) {
		echo "改后文件与已有文件重名";
		return false;
	}
	if (rename($oldName,$dataName)) {
		return true;
	}else {
		echo "重命名失败";
		return false;
	}
}
// rename_file('1.txt','4.txt');
// $dataName = dirname('a/1.txt').DIRECTORY_SEPARATOR.'2.txt';
// echo $dataName;


/**
 * cut_file 							剪切文件
 * @param  string 		$filename 		原文件
 * @param  string 		$data 	 		剪切目录
 * @param  bool 		$dot 			默认false，当$ddata需要创建时候文件名不能带点'.',目的判断填写的$dest是目录还是文件名，如果你非要创建带点的文件夹则输入true
 * @return mixed 						true|失败信息
 */
function cut_file(string $filename,string $data,bool $dot=false)
{
	// 检测文件是否存在
	if (!is_file($filename)) {
		echo "要剪切的文件不存在";
		return false;
	}
	// 要判断剪切的目录是否存在，不存在则创建
	if (!is_dir($data)) {
		if ($dot) {
			// 如果你非要给文件名加上点
			mkdir($data,0777,true);
		} else {
			// 这里默认是判断$data中是否存在'.'，存在点就可能是个文件，而不是文件夹
			if (strpos(basename($data),'.')) {
				echo "只输入要剪切进的文件夹名字即可";
				return false;
			}else{
				mkdir($data,0777,true);
			}
		}
	}
	// 定义新文件的路径
	$dataName = $data.DIRECTORY_SEPARATOR.basename($filename);
	if (is_file($dataName)) {
		echo "剪切路径下已有重名文件";
		return false;
	}
	if (rename($filename,$dataName)) {
		return true;
	}else {
		echo "剪切失败";
		return false;
	}
}
// create_file('1.txt');
// cut_file('a.txt','b.b',true);


/**
 * get_file_info 						读取文件信息
 * @param  string 		$filename 		文件名
 * @return mixed 						数组|失败原因
 */
function get_file_info(string $filename)
{
	// 检测文件是否存在
	if (!is_file($filename)) {
		echo "要查看的文件不存在";
		return false;
	}
	if (!is_readable($filename)) {
		echo "您没有权限读取该文件";
		return false;
	}
	$infoName = array(
		'文件名' => basename($filename),
		'文件路径' => dirname($filename),
		'上次访问时间' => date("Y-m-d H:i:s",fileatime($filename)),
		'修改时间' => date("Y-m-d H:i:s",filemtime($filename)),
		'inode修改时间' => date("Y-m-d H:i:s",filectime($filename)),
		'inode' => fileinode($filename),
		'文件大小' => trans_byte(filesize($filename)),
		'文件类型' => filetype($filename)
	 );
	return $infoName;
}
// print_r(get_file_info('1.txt'));

/**
 * 
 * trans_byte					 	字节单位转换的函数
 * @param  int        $byte      	字节
 * @param  integer    $precision 	默认精度，保留小数点后2位
 * @return string                	转换之后的字符串
 */
function trans_byte(int $byte,int $precision=2)
{
	$kb = 1024;
	$mb = 1024*$kb;
	$gb = 1024*$mb;
	$tb = 1024*$gb;
	if ($byte<$kb) {
		return $byte.'B';
	}elseif ($byte<$mb) {
		return round($byte/$kb,$precision).'KB';
	}elseif ($byte<$gb) {
		return round($byte/$mb,$precision).'MB';
	}elseif ($byte<$tb) {
		return round($byte/$gb,$precision).'GB';
	}else {
		return round($byte/$tb,$precision).'TB';
	}
}


/**
 * read_file 							读取文件内容到字符串
 * @param  string 		$filename 		文件名
 * @param  bool 		$tag 			是否过滤HTML标记，默认不过滤
 * @return string 						文件内容|失败原因
 */
function read_file(string $filename,bool $tag=false)
{
	// 检测文件
	if (!is_file($filename)) {
		echo "要读取的文件不存在";
		return false;
	}
	if (!is_readable($filename)) {
		echo "您没有权限读取该文件";
		return false;
	}
	if ($tag) {
		return strip_tags(file_get_contents($filename));
	} else {
		return file_get_contents($filename);
	}
}
// echo read_file('a3.txt',true);


/**
 * read_file_array 						读取文件内容到数组
 * @param  string 		$filename 		文件名字
 * @param  bool 		$blankLine 		是否过滤空行，默认不过滤
 * @param  bool 		$tag 			是否过滤HTML标记，默认不过滤
 * @return miexd 						数组|是不原因
 */
function read_file_array(string $filename,bool $blankLine=false,bool $tag=false)
{
	// 检测文件
	if (!is_file($filename)) {
		echo "要读取的文件不存在";
		return false;
	}
	if (!is_readable($filename)) {
		echo "您没有权限读取该文件";
		return false;
	}
	if ($blankLine) {
		$arrRead = file($filename,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
	} else {
		$arrRead = file($filename);
	}
	if ($tag) {
		$arrRead2 = [];
		foreach ($arrRead as $value) {
			$v = strip_tags($value);
			array_push($arrRead2,$v);
		}
		return $arrRead2;
	}
	return $arrRead;
	
	
	
}
// print_r(read_file_array('1.txt',true));



/**
 * @param  string 		文件名
 * @param  mixed 		要写入的信息
 * @param  bool 		是否清楚原来文件里的信息，默认不清楚往后写入
 * @return mixed 		写入到文件内数据的字节数，失败时返回FALSE 
 */
function write_file(string $filename,$data,bool $cover=true)
{
	// 检测要写入的文件存不存在，不存在创建目录，一会写入时候回自动创建文件名
	$dirName = dirname($filename);
	if(!is_dir($dirName)){
		mkdir($dirName,0777,true);
	}
	// 如果添加内容是数组或者对象，就转换成一个可存储的值的表示 
	if (is_array($data)||is_object($data)) {
		$data = serialize($data);
	}
	// 是否保存原内容，默认保存，则读取原内容，新内容拼在后面然后写入
	if ($cover) {
		// 这里要确认原文件存在才能读取
		if (is_file($filename)) {
			$content = file_get_contents($filename);
			$data = $content.$data;
		}
		return file_put_contents($filename,$data);
	} else {
		return file_put_contents($filename,$data);
	}
	
	
}
// $a = ['a','b','c'];
// write_file('4.txt',$a);



/**
 * 
 * truncate_file 						截断文件到指定大小
 * @param  string        $filename 		文件名
 * @param  int           $length   		长度
 * @return boolean                 		true|false
 */
function truncate_file(string $filename,int $length){
  //检测是否是文件
  if(is_file($filename)&&is_writable($filename)){
    $handle=fopen($filename,'r+');
    // 检测输入长度是否为正数
    $length=$length<0?0:$length;
    ftruncate($handle,$length);
    fclose($handle);
    return true;
  }else{
  	return false;
  }
}
// truncate_file('4.txt',5);



/**
 * 使用时候用a标签get传值就可以了
 * down_file 							下载文件
 * @param  string    $filename     		文件名
 * @param  array     $allowDownExt 		允许下载的文件类型
 * @return void
 */
function down_file(string $filename,array $allowDownExt=array('jpeg','jpg','png','gif','txt','html','php','rar','zip'))
{
  //检测下载文件是否存在，并且可读
  if(!is_file($filename)||!is_readable($filename)){
  	echo "要下载的文件不存在或者没有下载的权限";
    return false;
  }
  //检测文件类型是否允许下载
  $ext=strtolower(pathinfo($filename,PATHINFO_EXTENSION));
  if(!in_array($ext,$allowDownExt)){
  	echo "你不能下载该后缀的文件";
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
  header('Content-Disposition: attachment;filename='.basename($filename));

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
