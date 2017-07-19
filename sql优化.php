<?php 

1、sql 优化从哪些方面 
	数据结构的设计要合理,少连表
	创建合适的表结构，表的引擎的选择，表的索引的创建
	数据库读写分离
	使用高效的索引


2、myisam 和 innodb 索引结构
	myisam的数据是与索引是分开的。
	innodb的数据是与索引是在一起的。

	myisam的主键索引和普通索引的结构没有区别，只是主键索引的值不能重复，属非聚簇结构
	innodb的主键索引与数据在一起，而普通索引关联主键的ID，所以使用普通索引会先找到主键ID，再根据主键ID去遍历搜索值，也就是有两次查询

	innodb如果你没有创建主键，会自动去查检列看看有没有可以做唯一值的。如果没有。就隐式的创建一个唯一值。
	所以说：innodb的引擎的表，必需创建ID是主索引，并且是auto_increment的。


3、存储引擎的概念
	就是数据库把数据存储到硬盘或者内存中的一种技术


4、MyISAM与InnoDB引擎的区别
	1）数据的存储结构不一样
	2）myisam支持表锁，innodb支持行锁
	3）myisam支持压缩，innodb不支持压缩
	4）myisam不支持事务，innodb支持事务
	5）myisam不支持外键，innodb支持外键
	6）myisam支持地理位置空间，innodb不支持



5、myisam 的数据时一张表一个数据结构表、一个数据表、一个索引表
	innodb的数据是一张表一个数据结构表，数据和索引都存在一个共同的大表

	要把innodb表的数据索引拆开
	set global innodb_file_per_table=1


6、myisam插入的数据和取出来的数据，顺序是保持一致的
	innodb获取数据的时候与插入数据的顺序不一致。是因为innodb的索引是排序的，取出来的数据就是按顺序排列好的。


7、MyISAM引擎的压缩

	myisampack：压缩工具
	myisamchk：解压缩工具

	（1）压缩：
	/usr/local/mysql/bin/myisampack  /usr/local/mysql/data/test/myisam(表名)

	压缩的时候，索引会被破坏。需要重新建立索引
	/usr/local/mysql/bin/myisamchk -rq /usr/local/mysql/data/test/myisam（表名）

	压缩后要将数据刷到硬盘上
	flush tables  （多刷几次）

	压缩好的表，是只读的。不能再进行更新与修改了



	（2）解压缩：
	/usr/local/mysql/bin/myisamchk --unpack /usr/local/mysql/data/test/myisam（表名）

	解压缩完，也要刷硬盘
	flush tables;  多操作几次



	（3）为什么要压缩：
	对于表的数据不经常变化的，而且数据量有一定量的。压缩可以减小IO操作的时候，一次取读取的数量。
	数量小了之后，IO一次性操作，可以读取更多的数据。就这加速了访问了。



8、MyISAM引擎与InnoDB引擎的备份与还原

	mysqldump：就是数据的备份
	mysql：即是客户端，也可以实现还原的操作


	备份表
	/usr/local/mysql/bin/mysqldump –uroot  -p  数据库  表名  >  备份的绝对路径

	还原操作：
	/usr/local/mysql/bin/mysql -uroot -p 库名 < 备份的sql绝对路径

	备份库：
	/usr/local/mysql/bin/mysqldump -uroot -p 库名 > /tmp/test.sql   
	这种备份了指定的数据库里面的所有表与数据