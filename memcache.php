<?php 

pv:就是访问页面的总数量			（访问的网站总的打开的页面）
uv:就是访问网站的浏览器数量		（自己：人次）
ip:就是外网的IP地址的数量		（自己：同一个路由的是同一个ip地址）


Memcached 高性能的分布式内存缓存服务器,通过缓存数据库查询结果，减少数据库访问次数，以提高动态 Web 
应用的速度、提高可扩展性

memcache与mysql的区别：
	mysql存储的源数据。memcache存储的中间结果集数据(存于内存中)。
	mysql的数据是非常重要的。memcache存储的是临时性的，不那么重要的


memcache特性的：					不持久、键值对、只能存字符、不需要备份
	memcache是不能持久化的。					
	memcache的存储的值是key => value
	memcache的只能存储一种数据类型：字符
	memcache的数据是不重要，不必要备份。

	memcache的key最大值是250字节
	memcache的value最大值是1M


一、window下的memcache启动：

在window下，memcache有绿色版软件可以直接使用，只要在cmd命令窗口进入到软件目录下打开memcache就开启了memcache服务
	
	memcached -p 11211 

有两种开启方式，一种直接开启（不能关闭，关掉服务就关闭了），一种将memcache服务注册到服务表中

	memcached -d install
	memcached -d uninstall
	memcached -d stop

	然后就可以使用memcache服务了

	这里可以在打开一个cmd，使用telnet客户端连接（需要开启telnet服务）
	telnet IP 端口号
	telnet 127.0.0.1 11211

	也可以使用Xshell连接
	telnet IP 端口号
	telnet 127.0.0.1 11211

二、Linux下的memcache
	linux下的memcache需要源码安装，安装前需要安装依赖libevent（也要源码安装）

	开启memcached
	/working/memcached/bin/memcached -d -u root -p 11211

	-d :以守护进程的方式运行
	-u root :指定memcached以root的权限运行
	-p 端口号

	关闭防火墙
	service iptables stop




2、 cmd下memcache指令
	
	add name 0 0 4

	name :是一个key值
	第1个0：是表示压缩符
	第2个0：是存储时间。0表示，不过期（永久有效）。
	4 ：是字节。表示后面输入的值只能是这么多字节。不能多，也不能少

	set key 0 0 8		这个key不存在就添加，存在就修改

	get key

	incr num 4
	decr num 10
	delete key

	flush_all :清空。清除memcache缓存中库里面所有数据
	重点提示：一定不要使用这个命令。因为服务器里面的数据，永远不只是你在使用。你一次清空，就把别人的也清空了。

	stats ：查看memcache的状态



3、使用php操作Memcache

	首先php需要加载php_memcache.dll扩展（没有的话下载之后放到php的扩展目录 ext中）
	到php.ini中加载配置php_memcache.dll
	重启apache

	然后就可以在php中连接使用memcache了

	有两种操作方式，一种使用面向对象方式，一种使用函数

	$mem = new memcache(); 		//创建memcache对象
	$mem -> connect('127.0.0.1',11211); //建立连接

	$str = 'woaini';
	$mem->set('str',$str,0,0);	//存储字符串到memcache缓存中，这里的参数分别对应key，val，是否压缩，存储有效期
	$res = $mem->get($res);



4、如何配置分布式 memcache服务器
	分布式：就是让多台服务器，协同工作的，就是分布式

	memcache的服务器，并不能实现分布式操作。这个操作只能依赖第三方，也就是说客户端来实现这个操作。
	PHP扩展是通过addServer这个方法来帮助我们实现的分布式布局

	$mem = new memcache(); 		//创建memcache对象
	$mem->addServer('127.0.0.1',11211);			//设置了两台服务器用于存储缓存数据,不需要在使用connect了，addServer自动连接
	$mem->addServer('192.168.89.21',11211);		//设置了两台服务器用于存储缓存数据



5、如何将session存储到memcache中
	需要对session存储进行配置，但是不要去改动php.ini中的session，一台服务器，很多人在使用。你的session要保存在memcache里面。别人的不一定要保存memcache里面

	所以使用ini_set（）函数进行临时修改

	ini_set('session.save_handle','memcache');	//将session的保存类型改为memcache
	ini_set('session.save_path','tcp://127.0.0.1:11211');	//设置保存路径为本机上，注意11211是端口不要忘记写


	如何实现session保存的分布式？
	同样的配置下就可以了
	ini_set('session.save_handle','memcache');	//将session的保存类型改为memcache

	//分布保存到两台电脑
	ini_set('session.save_path','tcp://127.0.0.1:11211;tcp://192.168.89.11:11211'); 



！！！！！！！！！！！重要！！！！！！！！！！！！！！！

Memcache内存算法（lazy expiration）

	懒惰算法：把数据存储在服务器的时候，不去管它的过期时间。只有当数据被获取的时候，才去检查是否过期。如果过期，直接返回false，如果没有过期。就返回数据。


Memcache缓存策略（LRU： Least Recently Used）
LRU：最近最少使用原则。就是把最近最少没有使用过的数据直接删除掉。当内存存满的时候，就采用这个策略。


Memcache分布式算法
就是PHP扩展实现的addServer实现的hash算法 。可以实现分布式。



