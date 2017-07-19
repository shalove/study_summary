关于ip地址   每台电脑都有一个环回地址（内部ip），还有一个外部ip（这个可以被别人访问到）
这两个ip都标示了这台电脑


1、文件查找   find 
	
	示例  find 目录参数  选项参数  搜索内容

	-name  以名字来搜索
	例： find /root  -name  user.txt 

	当搜索一类的时候 
	find  /root  -name  ‘*.txt’    查找所有以.txt结尾的文件

	注意：使用通配符（*）的时候，必需加个引号


	-user：以属主来进行搜索
	find /home/sha  -user  root 	查找所有属主是root的文件 

	-group： 以属组来进行搜索
	find /home/sha  -group  root 	查找所有属组是root的文件  



2、显示磁盘信息
	df 

	-h	 	以1024进制来显示大小
	-H		以1000进制来显示大小
	-T	 	显示文件系统类型
	-t		必须加文件系统类型 ：显示指定的文件系统的类型
	-x		必须加文件系统类型 ：显示排除掉指定的文件的系统类型

 
3、挂载磁盘
	光驱在系统硬件的位置是：/dev/cdrom

	mount 挂载 

	示例：
	mount  光驱位置   挂载到的位置


	umount 卸载
	umount 挂载的目录  			
		卸载掉挂载的目录，当前目录必需不在这个挂载目录里面。而且没有任何程序在操作这个目录里面的内容。才能完成卸载 

	eject ： 弹出




4、网卡操作指令
	ifconfig ： 显示网卡信息，只是显示已经启动的网卡信息。

	eth0 	：就是我们的独立网卡，用于连接外部的ip地址，外部可以通过这个ip访问到本机
	lo 		:环回网卡地址 ，就是内部地址 127.0.0.1


	ifup 	：启动指定的网卡
		ifup eth0


	ifdown  ：停止指定网卡
		ifdown eth0



	4.2
	service ：是以服务的方式来操作网络
	service network [start|stop|restart|status]


	ifconfig是单个网卡的操作
	service network 是整个网络服务的操作



5、ping指令			用于验证网络是否启动
	ping
		-c ：一共拼多少次



6、使用FTP软件
	service	vsftpd  [start|stop|restart|status]

	FTP软件需要设置防火墙，因为它不在防火墙的默认允许访问中

	设置防火墙 	setup 

	还需要关闭SElinux
	临时性关闭  setenforce 0

	永久性关闭，需要修改配置文件
	vim /etc/selinux/config
	设置其中的 SElinux = disabled



7、使用ssh软件
	
	service sshd [start|stop|restart|status]

	ftp是端口号是：21号
	ssh的端口号是：22号
	mysql的端口号：3306
	http的端口号是:80


	ssh可以使用root账户。ftp是不可以的！！！
	ftp是明文传输不安全，ssh安全，传输重要文件用ssh


	scp 本地文件 远程文件  ：上传文件
	scp /root/user.txt  root@192.168.89.76:/home

	scp 远程文件 本地文件  ：下载文件
	scp root@192.168.89.76:/home/my.cnf /tmp

	scp –r 文件夹 文件夹  ：传输文件夹



	使用ssh登陆linux 
	ssh 用户名@ip地址




3、在linux上安装软件的步骤

	（1）首先把下载好的安装包用ftp或ssh上传到linux服务器的文件夹下（比如 /root）
	（2）如果上传的是压缩包，就进行解压

		解压文件指令 
		tar –zxf 安装包路径			注意选项参数f必须在最后面

	
	关于安装包的说明：
		一种是源码包，没有任何加密编译的代码块。要使用它，就要进行编译安装。
		另外一种是二进制的压缩包文件，已经被别人编译好的，可以直接使用的软件，这个软件就像windows绿色软件


		源码包与rpm安装包的区别
		rpm包是默认安装。直接安装的时候，不能进行自定义。一切的配置都是之前就设定好的。
		源码包是一个可以安全自定义的安装方式。你可以配置参数，让这一切都满意自己的需求。


		源码包安装步骤
		安装有三个步骤：
		（1）./configure ：检查当前系统与程序是否匹配。检查通过之后，会配置程序。生成makefile文件。
		（2）make ：去读取makefile文件，生成二进制文件。
		（3）make install：去读取二进制文件，安装程序。
		强调：这个指令必需在同一个目录里面使用。

			make 和 make install 可以合在一起使用，  make && make install
			注意如果安装过程中在make和make install过程出错，必须用 make clean清除后，重头开始安装。