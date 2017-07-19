<?php 

什么是架构：	搭建服务器的方法，就称之架构

数据库服务器：	某台服务器，主要干数据库工作的事情，就称之为：数据库服务器
				某台服务器，主要是做静态资源访问的，就是静态资源服务器


静态资源：图片，html，css，视频，js
动态资源：就是PHP资源

静态网页：就是纯静态资源组成的网页
动态网页：就是PHP组成的网页


cdn服务器：
		把主服务器的内容推送到很多节点服务器上面，这些节点服务器，就可以称之为CDN服务器（内容分发服务器）


高可用服务器：
		就是实现主备服务器，就是指有备用服务器的，她会一直监听主服务器，一旦主服务器挂掉，他就立马接管，防止服务器出问题，网站瘫痪。


正向代理服务器：就是给用户提供网络访问的服务器	（代理用户）
反向代理服务器：就是给服务器提供访问的服务器。（代理服务器）





1、Nginx特点
1）热部署：就是修改了配置文件，直接加载配置文件，不用重启。
2）可以高并发连接：相对于apache能够承受更多的请求
3）低的内存消耗：相对于apache相同的实现，相同的请求。使用的内存更少。
4）处理响应请求很快
5）具有很高的可靠性

2、Apache与Nginx服务器区别
1）nginx和apache的软件底层架构不一样。
①：Nginx的并发性要比apache好很多；
②：nginx属于轻量级服务器软件，apache属于重量级软件；
③：nginx在处理静态页的效率要比apache好很多，apache在处理动态页面上的效率要比nginx高
④：apache在安全性要比nginx要好。

2）运行模式不同的。
①：apache运行PHP是通过加载php5模块运行。由于是apache去加载php5模块，所以每次修改了php.ini配置文件需要重启apache。
②：nginx运行php是通过网络连接php-fpm（fastCGI）方式运行。 php-fpm是一个独立的软件（默认端口：9000）。因此在nginx下修改了php.ini配置文件需要重启php-fpm。





3、Nginx安装
	也是源码安装，需要先安装依赖软件 pcre
	然后 ./configure --prefix=/working/nginx1.8 --user=www --group=www   
		make && make install 

	nginx的运行配置文件
		nginx.conf

	nginx站点目录：
		html 

	主程序目录：
		sbin/nginx


	nginx启动：
		/working/nginx1.8/sbin/nginx 

		/working/nginx1.8/sbin/nginx -s [stop|reload|quit]



4、查看进程
	ps -ef | grep nginx 

	查看端口号
	netstat -tnl


5、 nginx PHP   fpm
	nginx的php也得重新编译安装，在参数编译要加入fpm
	安装完成后启动fpm

	/working/php-5.4-ngx/sbin/php-fpm

	php-fpm停止得用 杀死进程的方法

	使用ps –ef |grep php-fpm 查看进程号
	然后 	kill 进程号


6、nginx 缓存，压缩

	nginx的缓存、压缩都需要配置 nginx.conf


7、nginx 的负载均衡配置
	
	①打开nginx的配置文件：加入

		upstream  名称 {
			server ip地址  weight = 1;
			server ip地址  weight = 1;
		}

	2、配置server里面的内容
	server{
		listen  80；
		server_name localhost；	

		location / {
			proxy_pass  http：// upsteam中起的名称； 
			root 站点目录的绝对路径；
			index index.html； 
		}	
	}


然后将负载均衡服务器和其他web服务器都启动，以及 php-fpm也都启动

这时候在其他web服务器的站点下放置php文件，访问负载均衡服务器就可以访问到了


8、负载均衡 session丢失问题
	由于使用了负载均衡，当用户登录后，如果被分到了A服务器，我们把session保存在A 服务器上，用户再次请求时，请求又被分到了B服务器，这时就会出现问题，B上没有用户的session，用户变成了未登录状态。也就是session丢失了。

	如何解决？

	（1）使用 ip_hash 
		在nginx配置文件中，做负载均衡时 upstream加入 ip_hash；
		ip_hash会将同一IP的用户的请求分配到同一服务器上

		缺点：可能会造成有的服务器很忙，有的服务器很闲。。。


	（2）使用 memcache来实现session存储 
		将每台服务器上的session都存到memcache中，这样就可以共用session了。

		需要在每个服务器配置
		 ini_set('session.save_handle','memcache');
		 ini_set('session.save_path','tcp://127.0.0.1:11211');

		 注意ini_set()必须在session_start()之前