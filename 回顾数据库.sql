show databases;
show create database 数据表名/G
create database 数据库名 ;
use database 数据库名;
alter database 数据库名 charset 新字符集名;

drop database 数据库名;




数据表

show tables;
create table 数据表名(
字段名  字段类型 字段属性,
id int primary key auto_increment,
name varchar(10) not null default "小明",
sex enum("男","女") not null default "男",
hobby set("游泳","跑步") not null default "游泳，跑步",
born datetime ,
introduce text
);

drop table 数据表名;

增：
alter table 数据表名 add 字段名1  字段类型 【字段属性】 first/after 字段名,
					 add 字段名2  字段类型 【字段属性】 first/after 字段名;

删
alter table 数据表名 drop 字段名;
改
alter table 数据表名 change 旧字段名  新字段名 字段类型 【字段属性】；
alter table 数据表名 modify 修改的字段名 修改的字段类型 修改的【字段属性】;
alter  table 数据表名称 charset 新字符集
查
desc 数据表名;
show create table 数据表名\G




数据
增：
insert into 数据表名 字段名1，字段名。。 values（值1，值2.。），（值1，值2。。）;
删：
delete from 数据表名 where 条件（五子句）;
改：
update 数据表名 set 字段1=值1，字段2=值2.。。  where 条件（五子句）;
查：
select 字段1，字段2.。/ *  from 数据表名  where 条件  group by 字段名 having 条件
	order by 字段名 desc/asc  limit 偏移量,显示行数;

where子句中使用的字段只能来自数据表中的字段，不能使用别名，
数据表名 可以用  as 起别名    
对于null  的判断 只能用 is null  、  is not null
between 。。and。。     	not between。。 and。。
like “张%”  like“_张” 模糊查询		not like 。。
in（值，值2） 在。。范围内     not in。。

聚合函数   sum（），avg（），max（），min（），count（）

删除： drop 表结构    delete 数据    truncate 数据表 


联合查询  select * from table1 where 条件
			union distinct(默认删除重复项)/all
			select * from table1 where 条件
纵向拼接，要求字段顺序一致



连接查询
交叉连接查询   
select 字段 from tb1 cross join tb2;  结果是笛卡尔积结果集

内连接查询
select 字段 from tb1 as 别名1  (inner) join tb2 as 别名2   on  连接条件 where 条件;
左外连接
select 字段 from tb1  left (outer) join tb2   on  连接条件 where 条件;
select 字段 from tb1  right(outer) join tb2   on  连接条件 where 条件;


子查询
标量子查询：子查询返回单一值（一行一列） =
列子查询：子查询返回一列N行	in（），>any（），>all（）
行子查询：子查询返回一行N列   （a，b）=（子查询结果集）
表子查询：子查询返回多行多列
select * from （select * from tb1 where 五子句） as 别名  where 五子句;

exists子查询
select  字段列表  from  tb where [not] exists (子查询);



数据类型：
数值型：  整数型： tinyint、smallint、mediumint，int，bigint
			小数型： float 、 double 、decimal
日期时间型： year、date、time、datetime、timestamp
字符串型：char、varchar、enum、set、text、blob



1)整个数据库备份

cmd> mysqldump[.exe] [–h主机名]  [-p端口号] –u用户名  -p密码 [-d] db [tb] > 导出文件的路径 

-d代表仅导出结构（无插入数据的语句）

2.数据还原
cmd> mysql[.exe]  [–h主机名] [–p端口号] -u用户名 –p密码 db < 文件路径 


2)单表备份
mysql > select *|字段列表 into outfile ‘文件路径’ from 表名;
–还原语法：
mysql >load data infile 文件路径 into table 表名[字段列表];

复制原先数据表单的结构(创建数据表)：
create table tb_name like 表名；



1.查看用户
select  user,host  from  mysql.user;
2.创建用户
create user ‘用户名’@’主机名’ [identified by ‘用户密码’];

1)修改密码
set password [for ‘用户’@’主机’] =password(密码);

3.删除用户
drop user ‘用户名’@’主机’;

2)授权语法
grant 权限名称 on 数据库.数据表 to ‘用户名’@’主机’ [with grant option];

•权限名字可以小写。全部权限（除授权权限之外）可以使用all代替
•数据对象即数据库.数据表，可以使用db.*
•[with grant option]，带上之后用户有授权的权限。通常情况下不给授权的权限
•不同权限之间使用,分割

5.取消用户授权
revoke 权限  on db.tb from ‘用户名’@‘主机’;


3)外键创建语法
create table tb(
…，
foreign key (外键名称) references  数据表（字段）
);
4)修改数据表--添加外键
alter table tb_name add foreign key（外键字段） references 父表 （父表主键） ;
5)删除外键
alter table tb_name drop foreign key 外键约束名称;


视图

create  [algorithm  = undefined/merge/temptable]  view  view_name  as select 语句;


触发器
delimiter ||
create trigger 触发名称 before/ after insert/update/delete  on tb1  for each  row
begin
	触发的操作（SQL语句）;
end
||
delimiter ;


使用别名old,new来引用被操作数据表中的列




事务具有ACID特性：原子性、一致性、隔离性、持久性
A（atomicity ）:原子性。事务被视作为不可分割的最小的工作单位。事务中的一批SQL语句，要么全部执行成功，要么全部失败，回滚。
C（consistency ）:一个状态到另外一个状态的一致性。老王抓出2000，小花转入2000.
I（isolation,）：隔离性。一个事务所做的修改最终提交之前，对其他事务是不可见的（没有刷出到数据表文件，保存在内存中）。打开新的窗口无法读到实时的数据。
D（durability）：持久性。一旦提交，事务所做的修改会永久的保存到数据库中。


MySQL函数调用的方法：
select 存储函数名称（参数）;

2.系统函数
1)字符串处理函数
concat(str1,str2):连接子字符串

LTrim()：去除左边的空格.RTrim()：去除右边的空格
Upper()：返回大写字符
Char_length()：返回字符的个数。区别PHP中的strlen();
Left(str,len) ：返回串左边指定数目的字符.Right(str,len)：返回串右边指定数目的字符
SubString(str,start[,len])：返回子串的字符
Instr(父串，子串)：第一个出现的位置。位置从1开始，不区分大小写。没有找到，返回0.类似于PHP的strpos().

2)日期时间函数
Date() ：返回日期时间的日期部分.当前日期curdate().
Time() ：返回一个日期的时间部分.当前时间curtime().
DateDiff() ：求两个日期的差
Year()：返回一个日期的年份部分
now():获取当前时间日期
Date_add(日期，interval 数字 单位)，向日期添加时间间隔

ABS(x)   返回x的绝对值
CEILING(x)   返回大于x的最小整数值，向上取整。
FLOOR(x)   返回小于x的最大整数值，向下取整。
GREATEST(x1,x2,...,xn)返回集合中最大的值
LEAST(x1,x2,...,xn)      返回集合中最小的值


3.存储函数
delimiter ||
create function 名称 （参数1 类型，参数2 类型）
	returns 返回值 类型
	begin
		函数体；
		return 语句；
	end
||
delimiter ;


2)全局变量：
在存储函数外部定义的变量。
set  @var =值；

3)局部变量
declare 变量名 类型 [default 值]；

1)分支结构
if 条件1 then
	语句1;
else if 条件2 then
	语句2；
else if 条件3 then
	语句3；

	语句n;
end if;


2)循环结构
[标签：]while 条件 do
	语句;
	//iterate 标签;
	//leave  标签;
end while;

3)循环中的继续及中止循环
iterate 标签; //continue
leave  标签; //break



1.创建存储过程
create procedure 名称 （in/out/inout 名称 类型）
begin
	语句；
end

3.调用存储过程
call 存储过程（[参数]）;