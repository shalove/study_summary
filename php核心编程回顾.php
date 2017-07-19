<?php 
表单传值注意事项:
1、POST覆盖GET的值;注意: method=get时,action将不再传值;


上传文件原理:
分两步:
第一步: 将本地的资源上传到服务器的临时目录中.
第二步: 将上传的资源从临时目录中移动到指定的服务器的目录中.

enctype属性: //对上传文件的编码方式,要上传文件必须写，否则传不了文件
<form name="f1" action="" method=""  enctype="multipart/form-data">

对上传文件的判断需要考虑到的:
1)判断是否是HTTP POST上传的  is_uploaded_file($_FILES['表单设置名']['tmp_name'])
2)判断是否有错误 	$_FILES['表单设置名']['error']
3)判断文件大小		$_FILES['表单设置名']['size']
4)获取文件扩展名	strchr($_FILES['表单设置名']['name'])
5)判断文件类型
	$fs = finfo_open(FILEINFO_MIME_TYPE);
	$mime = finfo_file($fs,$_FILES['表单设置名']['tmp_name']);


6)构建临时文件
$destination ="./upload/".uniqid()."文件后缀名"
7)构建目标文件
move_uploaded_file($_FILES['表单设置名']['tmp_name'],$destination);




PHP操作数据库的步骤 (现在都用PDO类库)
说明: php操作数据库全部使用的是函数.
第一步: 登录 mysql_connect()
第二步: 选择(使用)数据库: mysql_select_db()
第三步: 设置字符集:  set names utf8;

$host = "localhost";
$username ="uroot";
$password ="123456";
$db_name = "数据库名";

if(!mysql_connect($host,$username,$password)){
	die("");
}
// mysql_connect 如果成功则返回一个 MySQL 连接标识resouce， 或者在失败时返回 FALSE
if(!mysql_select_db($db_name)){
	die("");
}
// mysql_select_db  成功时返回 TRUE， 或者在失败时返回 FALSE。
mysql_query("set names utf8");

mysql_query() 仅对 SELECT，SHOW，DESCRIBE, EXPLAIN 和其他语句 语句返回一个 resource(也就是有查询结果的返回的是资源型的以便后续函数利用 mysql_fetch_assoc)，如果查询出现错误则返回 FALSE
 对于其它类型的 SQL 语句，比如INSERT, UPDATE, DELETE, DROP 之类， mysql_query() 在执行成功时返回 TRUE，出错时返回 FALSE



1.mysql_num_rows($result) — 取得结果集中行的数目
2.mysql_num_fields($result)  //返回结果集中字段的数
3.mysql_field_name($result,0)  //取得结果中指定字段的字段名
4.mysql_insert_id();  //返回 INSERT 查询中产生的 自增长 的 ID 号

使用PDO来连接操作数据库：
不使用预处理：
    如果是增删改，写完sql语句，直接使用 $pdo->exec($sql)就可以执行了，
    对于查需要使用query语句，再用fetch或fetchAll获取查询结果
    $pdo->query($sql);
    $data = $pdo->fetchAll(PDO::FETCH_ASSOC);

使用预处理：
    统一先使用预处理，获取pdostatement对象
        $stmt = $pdo->prepare($sql);
        对于增删改，直接使用execute()，执行就可以了
        $res = $stmt->execute();
        对于查询：
        先执行，再用fetch或fetchAll获取查询结果
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


$dst = "mysql:host=localhost;dbname=hm4;charset=utf8";
$pdo = new PDO($dst,"root","123456",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM student WHERE id > ?";    //占位符有 ？和 :key两种形式
$stmt = $pdo->prepare($sql);    //使用预处理
$stmt->bindValue(1,20);   //bindValue绑定值从下标1开始绑定，第二个参数是要绑定的值,
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);


目录操作

1、创建目录
mkdir($path)  //返回值为布尔

mkdir($path,0777,true);
再说明
1.path 目录路径
2.0777 目录权限,win下忽略
3.true  目录递归创建(即一次创建多级子目录)

is_dir($path)  //返回布尔值
file_exists(目录或文件路径) //返回布尔，判断目录或文件是否存在

2、打开目录
readdir(句柄),从目录句柄中读取目录中的条目,

有两种特殊情况要注意：
1、目录名为0 的情况
while(($file = readdir("$path")) !== false){}  //采用不全等判断
2、目录有中文
iconv(in_charset, out_charset, str)    //iconv（） 函数可以进行字符编码转换
说明: 
1. in_charset //进去的字符编码
2. out_charset //出来的字编码
3. str;  要转换的字符串

3、关闭目录
closedir(目录句柄)//主要目的是释放内存资源. 有打开(opendir)必须有关闭

目录重命名
rename(oldname, newname);  //重命名一个文件或目录
说明:
1.oldname //旧名
2.newname//新名

移动目录 
rename(old,new)
总结: 在同一个路径下,即为重命名,在不同路径下即为移动

删除目录
rmdir(目录路径)  //删除空目录,返回布尔值




文件操作
fopen(文件路径,"mode")  //返回文件句柄，打开文件的方式

文件锁
flock($handle,LOCK_EX）  //取得独占锁
flock($handle,LOCK_UN)    //释放锁


关闭文件
fclose(文件句柄); //释放内存资源, 有打开(fopen),必须有关闭.

写入文件
fwrite(文件句柄,字符串);
前提条件:文件句柄必须有写入的功能.

读取文件内容:
fgetc(文件句柄) //从文件句柄中读取一个字符.
1、文件名为0 的情况
while(($char = fgetc("$handle")) !== false){}  //采用不全等判断

fgets(文件句柄);//从文件句柄中读取一行字符.
fgets不用考虑为0和中文的情况

读取指定大小的内容,
fread(handle, length)
handle  //文件句柄
length  //设置一次读取的字节大小


feof(handle）测试文件指针是否到了文件结束的位置

不用打开和关闭
直接读取file_get_contents(文件名)
直接写入file_put_contents(文件名,字符串)


拷贝文件
copy(源,目标)
如:copy(‘test.txt’,’./temp/mytest.txt’) 
 //将当前目录下的test.txt拷贝到temp/mytest.txt
注意:只能拷贝文件不能拷贝目录，拷贝目录可以用递归循环拷贝文件创建目录来实现
								删除目录也是用递归循环删除文件再删除空目录

删除文件 
unlink(文件)


http协议特点
1)简单,快速, 传值的方法及接收的页面
2)灵活. header(‘content-type:text/html;charset=utf-8’), header(‘content-type:image/jpeg’)
3)无连接 (连接总是要断开的)
4)无状态(说的是http没有记忆功能)


HTTP请求的构成,
请求部分: 请求行,请求头,空白行,请求体

GET和POST的区别
1、  安全性：    get安全性低，post安全性高
2、  传值大小：  get < 2k ，  post 无限制(默认为8M)
3、  传值方法：  get 可通过 表单、地址栏、链接 传值，post只能通过表单
4、  接受值方法  get 可通过 $_GET['']获取 表单、地址栏、链接的传值
				 post 通过$_POST['']获取 表单的传值
5、  HTTP协议:   get位于http协议 的请求行中，POST位于请求体中




http响应的构成

响应分四部分:
响应行（状态行、消息行）,响应头（消息头）,空白行,响应体


常见的状态码:
200 响应成功
301 永久域名跳转，永久重定向
302 临时域名跳转，临时重定向
304 加载本地加载页面资源
403 forbidden 无权限、拒绝访问
404 notfound 请求的资源不存在
500 服务器内部错误


a)Header(‘content-type:text/html;charset=utf-8’)  //字符集
b)Header(‘location:url’);  //跳转
c)Header(‘refresh:3;url’);  //经过多少秒跳转到指定的页面


如何强制用户下载  
//流的方式发送到浏览器
header("content-type:application/octet-stream"); 
//按照字节的返回给浏览器
header("Accept-Ranges:bytes") ;
//告诉浏览器这个文件的大小
header("Accept-Length: ") ;
//以附件的形式发送到浏览器(也就是弹出,下载的对话窗口)
header("Content-Disposition:attachment;filename=");

PHP扩展:curl实现数据采集
第一步:初始化CURL请求. //如: $link = curl_init(网址)
第二步:设置请求选项.  //如: curl_setopt = ($link,选项名,选项值);
第三步:执行请求并返回结果. //如: $result =  curl_exec($link);
第四步:关闭CURL请求. //如: curl_close($link);


什么是COOKIE?
主要是用来跟踪(识别)用户的. 
cookie过程描述
网站为了辨别用户身份、进行 session 跟踪而储存在用户本地终端上的数据（通常经过加密）
用户第一次访问你的网站->? 在服务器端会将用户的信息设置为cookie(可以理解为制造饼干过程)? 通过http协议发送给用户(浏览器),在用户端,cookie以文本的形式保存下来. 
用户第二次访问同一个网站-> 在http协议的请求头中会携带着cookie信息,服务器对cookie进行验证,第二次响应-> 猜你喜欢


1、设置COOKIE
setcookie (  $name,  $value , $expire,  $path , $domain )
说明:
1.$name //cookie名称,名称为字符串.
2.$value //cookie的值
3.$expire //cookie的过期时间
	Cookie的有效期, 如果过了有效期,则cookie则不能使用.有效时间单位是 “秒”
	设置时是以当前时间戳加多少秒.即: time()+秒数.
	如:
	Setcookie(‘username‘,’admin’,time()+3600); //设置cookie的值为1小时. 
	Setcookie(‘username‘,’admin’,time()+604800); //设置cookie的值为1周. 
4.$path //有效的路径
	a)Cookie在设置的目录及其子目录有效.
	b)默认为 网站的根目录 “ /“
5.$domain //有效域名
	默认当前的域名,如www.abc.com

如果想以数组形式设置其值:
a)设置形式：setcookie('c1[k1]', 值) //setcookie(‘stu[name]’,’alice’)
b)读取形式：$_COOKIE['c1']['k1']  //$_COOKIE[‘stu’][‘name’]

2、读取COOKIE
超全局数组: $_COOKIE[‘cookie名称’]


3、‘删除’COOKIE的方法
注意: 在服务器端无法将用户的cookie删除, 只能设置cookie过期时间.
1.  setcookie(名,值,time()-1); setcookie(名,值,time()-99999999);
2.  setcookie(名,’’);
3.  setcookie(名);



什么是SESSION?

session过程描述 :
	当客户端访问服务器时，服务器根据需求设置session，将会话信息保存在服务器上，同时session_id,以cookie的形式传递给客户端浏览器，浏览器将这个session_id保存在内存中。
	浏览器再次请求都会额外加上这个session_id(也即是cookie的值)，服务器根据这个session_id，在服务端来判断使用哪个session文件.


session 和 cookie 的区别
1、保存位置  
	session 保存在服务器，cookie保存在客户端
2、生命周期
	session 用户浏览器关闭，cookie 可以设置长期保存
3、设置方法
 	$_SESSION['名']=值；  setcookie =（名，值，有效期，【有效路径】，【有效域名】）

4、值类型
	session用的是数组，不限制数据类型，cookie的值只能是字符串
5、数据大小
	session 无限制  ，cookie < 4k；
6、安全性
	session 数据存在服务器 较安全，cookie数据存在客户端 安全性低



SESSION相关操作

1、要使用session 第一步必须先开启session会话

session_start();

1.不管是创建还是使用,判断,删除等,之前必须要开启session. 
2.一个页面只能开启一次.
3、当使用session_start(),会判断当前是否已经开启session. 在一个会话期内只有一个session_id.

获取当前的SESSION的ID值
session_id()

1.一个会话期内只有一个session_id
2.session_id号是 session文件的文件名
3、session_id 号是,cookie的值.
4.也可以通过session_id(自己设置id号); 


添加SESSION数据
格式:  $_SESSION[‘session的名称’] = session的值
注意: session值的类型,没有限制.


读取SESSION数据 
直接通过session的名称直接读取. 即通过session数组的下标直接读取
$_SESSION[‘username’] = ‘admin’;




删除SESSION数据

unset（$_SESSION[‘username’]）;
删除的session在内存中的变量

session_destroy();
//删除的是当前的session文件

$_SESSION=array()
将session赋值为空数组，相当于清空session的内存中的变量

注意: 如果退用户登录状态,则要将当前的session变量销毁（必须）和session文件删除（非必须）. 


SESSION相关配置

1. session.name = PHPSESSID  //即session的cookie的名称
2. session.auto_start =0
3. session.cookie_lifetime=0   //代表浏览器关闭,会话结束
4. session.cookie_path=/     //指定了要session会话 cookie 的有效路径
5. session.cookie_domain    //指定了要session会话 cookie 的有效域名

6.脚本设置:  //当前域名有效
a) ini_set('session.cookie_path', '/');
b) ini_set('session.cookie_domain', '.mydomain.com');
c) ini_set('session.cookie_lifetime', '1800');"

注意: 在所有的ini_set设置,必须在session_start() 之前！！！！！！！






图像基本操作

创建图像
方法一:imagecreate()  //基于调色板来创建 （不建议使用）
方法二:
imagecreatetruecolor() //创建真彩色图像

 分配颜色
imagecolorallocate($image, $red, $green, $blue)
说明:
1.$image //图像资源
2.$red,$green,$blue // 0-255, 三元色

为真彩色图像: 填充颜色
imagefill(image, x, y, color)
说明:
1.image //为哪个图像资源进行填充
2.x,y, 填充的坐标,其范围在图像宽与高之间.
3.color; //用什么颜色来填充



方法三:
Imagecreatefromjpeg() //基于现有的图片来创建
Imagecreatefrompng() //基于现有的图片来创建
Imagecreatefromgif() //基于现有的图片来创建
说明: filename //图片的路径, 返回的一个图像资源






绘制图像
1、直线:
imageline(image, x1, y1, x2, y2, color)

a)image //图像资源
b)x1,y1,线的一个端点
c)x2,y2 线的另一个端点
d)color //线的颜色

2.矩形: 
imagerectangle(image, x1, y1, x2, y2, color)
说明:
1.image //图像资源
2.x1,y1左上角
3.x2,y2,右下角
4.colore //颜色

3、圆:
imagearc(image, cx, cy, width, height, start, end, color)
说明:
1.image //图像资源
2.cx,cy //圆心的坐标
3. width //椭圆的长轴
4.height //椭圆的短轴
5.start //开始的角度0度, 在3点钟方法
6.end //角度的结束位置
7.color //线的颜色

4、在图片中写入字符串
imagestring(image, font, x, y, string, color)
说明:
1.Image //在哪个图像资源中写入字符串
2.Font //字符大小, 取值1-5
3.X,y, //写入时坐标
4.String //将要写入的字符串 , ‘helloworld’
5.Color //字符串的颜色

5、画一个单一像素
imagesetpixel($image,  x, y, $color);  //如果要创建干扰点使用for循环建立
说明: 
1.$image //图像资源
2.x,y 像素点的坐标
3. $color //像素点的颜色


6、在图像上写入TTF字体的文本
imagettftext(image, size, angle, x, y, color, fontfile, text)
说明:
1.image// 图像资源
2.size //文字 的大小没有限制
3.angle //旋转的角度
4.x,y 写入的坐标
5.color //汉字的颜色
6.fontfile //使用的字体的路径
7.text //将要写入汉字


7、生成缩略图
imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
说明:
1.$dst_image //目标图像资源
2.$src_image //源图像资源
3.$dst_x, $dst_y,//目标图像资源的左上角坐标, 0,0
4.$src_x, $src_y, //源图像资源的左上角坐标 0,0
5.$dst_w, $dst_h, //目标图像的宽与高
6.$src_w, $src_h //源图像的宽与高

8、制作图像文字水印效果
imagecolorallocatealpha(image,red,green,blue,alpha)
alpha:  0-127  代表透明度  0 不透明 127完全透明

9、图像图片水印效果
imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
说明:
1.$dst_img //目标图像资源 大图
2.$src_im  //源图像资源 小图
3.$dst_x, $dst_y,  //指定源的左上角, 在目标上面的坐标(即小图的左上角坐标)
4.$src_x, $src_y,  //小图的坐标左上角
5.$src_w, $src_h  //源图宽与高
6.$pct  //透明度0-100




输出图像
两种方法
第一种方法:输出到浏览器: 
header(‘content-type:image/jpeg’);
imagejpeg(图像资源)
第二种方法:输出图片文件
imagejpeg(图像资源,图片文件路径/文件名.jpg)


销毁图像
imagedestroy(图像资源) //释放内存.







?>