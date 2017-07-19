<?php 
1、配置Linux网络
	打开网络配置文件 vim /etc/sysconfig/network-scripts/ifcfg-eth0
	主要修改 ONBOOT：yes就是开机自启动；no开机不启动。必需设置成yes
			 BOOTPROTO：dhcp表示自动获取IP地址；none手动设置IP地址
	IPADDR：选择手动设置IP地址的时候。这里就设置成IP地址
	NETMASK：子网掩码。选择手动设置IP地址的时候。这里就要设置
	GATEWAY：默认网关。选择手动设置IP地址的时候。这里就要设置

	NM_CONTROLLED：网络管理软件。默认是开启，一定要设置NO。关闭它。


2、ifconfig    显示网卡信息
	在上面能看到自己的电脑的外网和内网IP，外网IP用于其它外部访问本机的地址，内网也是标示本机的一个IP，只是不能被外部访问到，内网的IP固定为 127.0.0.1 

	对于用Xshell远程连接Linux服务器的时候，就需要知道服务器的外网IP，然后根据此IP来进行连接
		ssh root@192.168.89.26   这里假设用root登陆服务器，并且服务器的外网IP为 192.168.89.26 


		ifconfig是单个网卡的操作
		service network 是整个网络服务的操作
		service network [start|stop|restart|status]  

3、如何检测是否连接上相应电脑（服务器），根据能否ping通连接的电脑的IP 
	在命令行使用 ping IP    IP 指要连接的电脑或服务器的外网IP，如果能ping通，收到回复信息，就表示连接成功，如果ping不通，没有回复信息，就表示连接不上


注意 ：  修改重要文件，要养成在修改之前备份的习惯



4、 关于 FTP 和 SSH 
	这两个都是用于实现客户端与服务器端文件上传、下载的
	FTP使用的是明文传输，相对不安全，SSH更安全

	（1）要使用FTP ，首先服务器得安装了FTP服务软件，然后开启FTP服务支持，

	service	vsftpd  [start|stop|restart|status]    分别对应 开启、停止、重启、查看状态

	（2）Linux上一般默认防火墙是不允许访问 FTP 的，所以得先设置防火墙，让其允许访问FTP
		设置防火墙命令  setup

	（3）要想使用客户端能连接上还得关闭SELinux
		临时性关闭设置  setenforce 0
		永久性关闭 	打开文件  vim /etc/selinux/config  		设置 SELINUX =disabled

	（4）然后就可以使用客户端 flashfxp 进行连接 上传下载了



	SSH 的使用
	（1）sshd服务是所有linux操作系统都默认安装好的程序，也是默认就是自启动的程序，所以不用手动去开启和配置防火墙了
	要是想查看状态或开启关闭服务 使用  service sshd [start|stop|restart|status]  

	（2）然后就直接可以使用客户端 WinSCPPortable进行连接上传下载了




	服务器 一般在软件名后面都加个 d 字母

	ftp是端口号是：21号
	ssh的端口号是：22号
	mysql的端口号：3306
	http的端口号是:80：
	重点说明：上面的这些都是一种协议



5、如何卸载Linux上的	AMP

	首先在卸载之前可以使用  rpm -qa | grep 要卸载的文件名 查看一下相关软件，知道要卸载哪些

	卸载的时候因为有些软件都是相互依赖的，所以老是提示，可以使用

	rpm -e 卸载文件名  --nodeps    		强制卸载



6、安装 AMP 环境

	安装顺序 
				首先安装Apache软件
				再次安装MySQL软件
				最后安装PHP软件
				进行顺序安装，可以在安装PHP的时候，直接把php的模块直接加入到apache里面

	（1）将下载好的安装包通过winSSPortable软件上传到Linux服务器上（比如 /root）

		下载的安装包一般是压缩包，需要先解压
		tar –zxf 压缩包路径


		压缩包内容说明
			httpd：解压出来的是一个源码包。没有任何加密编译的代码块。要使用它，就要进行编译安装。
			mysql：是二进制的压缩包文件，已经被别人编译好的，可以直接使用的软件。这个软件就像windows绿色软件。
			php：解压出来的是一个源码包。没有任何加密编译的代码块。要使用它，就要进行编译安装。


			rpm包是默认安装。直接安装的时候，不能进行自定义。一切的配置都是之前就设定好的。
				源码包是一个可以安全自定义的安装方式。你可以配置参数，让这一切都满意自己的需求。

		源码包安装步骤
			安装有三个步骤：
			./configure ：检查当前系统与程序是否匹配。检查通过之后，生成makefile文件。
			make ：去读取makefile文件，生成二进制文件。
			make install：去读取二进制文件，安装程序。
			强调：这个指令必需在同一个目录里面使用。

			如果make和make install 中有任何报错，都要使用make clean清除错误安装文件后，重新使用 ./configure进行安装



	  安装 Apache 
	  	解压后进入 apache解压包目录中

	  	1、使用 ./configure检测生成makefile 文件
	  	./configure --prefix=/working/httpd  --enable-so

	  	--prefix：指定安装目录
		--enable-so：确定apache开启第三方模块的支持


		2、生成makefile文件后 使用 make命令，在使用make install命令

			或直接  make && make install


		apache的服务在 	 /working/httpd/bin/apachectl [start|stop|restart]

		配置文件：
		/working/httpd/conf/httpd.conf




		安装 mysql 

		（1）说明，解压出来的Mysql是别人已经帮助我们编译好的二进制文件。这个时候如果我们要使用，就必需安装到/usr/local/下面。这里是Mysql，所以目录就是/usr/local/mysql。为什么呢？就是安装的时候，要设置某些参数。别人编译的就使用的是默认目录格式来进行安装的


		（2）这里的mysql解压后的包相当于window下的无需安装即可使用的文件夹那种的

		（3）使用 cp -R 解压后的mysqlb包路径   /usr/local/mysql 

		（4）进入复制后的/usr/local/mysql 目录中初始化数据操作
			./scripts/mysql_install_db --basedir=/usr/local/mysql/ --datadir=/usr/local/mysql/data/ --user=mysql

			--basedir ：程序的安装目录 /usr/local/mysql
			--datadir ：mysql的数据目录 /usr/local/mysql/data
			--user  ：使用什么用户来启动mysql的worker进程

			如果没有mysql这个用户就需要先创建这个用户 使用 useradd mysql


		（5）然后修改属主和属组

			把属主修改成root：把属组修改成mysql
			chown -R root:mysql /usr/local/mysql 

			把数据目录的属主也要修改成mysql
			chown –R mysql /usr/local/mysql/data

		（4）创建配置文件
			配置文件放到  /etc/my.cnf 
			这里的配置文件 也是下载好，复制到etc下的

		（5）启动mysql服务端
			/usr/local/mysql/support-files/mysql.server start

		（6）启动客户端连接
			/usr/local/mysql/bin/mysql  -uroot -p 回车输密码（第一次安装好没有密码）

			在mysql中修改密码   set password = PASSWORD（'123456'）;



	 安装 PHP 

		（1）
			./configure  --prefix=/working/php5.4/  \
			--with-config-file-path=/working/php5.4/etc/  \
			--with-apxs2=/working/httpd/bin/apxs \
			--with-mysql=/usr/local/mysql \
			--with-zlib --enable-bcmath  --disable-fileinfo --enable-ftp  \
			--with-gettext  --with-mhash --enable-mbstring \
			--with-mysqli --with-pdo-mysql  --with-mysql \
			--enable-shmop --enable-pcntl  \
			--enable-soap --enable-sockets  \
			--enable-sysvsem --enable-zip  \

			这里的反斜杠表示还没输入完，下行接着输入

			安装php之前要先安装链各个依赖文件
			libxml2 ：是程序文件。必需先安装它。
			libxml2-devel ：是开发者文件。依赖于libxml2

			使用 rpm -ivh 安装


			（2）make && make install

			（3）配置文件
				复制解压缩包里的 php.ini-development 到 /working/php5.4/etc/php.ini

			（4）打开apache的配置文件：
					vim /working/httpd/conf/httpd.conf
					搜索AddType 
					插入 AddType application/x-httpd-php .php

				重启apache


		配置快捷启动

			ln -s /working/httpd/bin/apachectl   /bin/httpd 

			这样就建立了软连接
			要启动apache 就可以使用  httpd start 即可 


		配置自启动
			将mysql或者apache的启动方式，写入 /etc/rc.d/rc.local




		对于使用源码安装的可以直接删除安装目录就可以，对于rpm安装的必须用 rpm -e 删除
		yum安装的用yum删除