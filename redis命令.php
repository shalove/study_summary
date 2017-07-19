<?php 
//连接本地的 Redis 服务  
$redis = new Redis();  
$redis->connect('127.0.0.1', 6379);  
$redis->auth('123456');  
  
/*********************Key(键)*********************/  
//DEL key [key ...]------删除给定的一个或多个key  
$a = $redis->del(array('xrj','ly','fjf'));  
//KEYS pattern------查找所有符合给定模式pattern的key  
$b = $redis->keys('*');  
//RANDOMKEY------从当前数据库中随机返回(不删除)一个key  
$c = $redis->randomkey();  
//TTL key------以秒为单位,返回给定key的剩余生存时间(TTL, time to live)  
//PTTL key------以毫秒为单位,返回给定key的剩余生存时间  
$d = $redis->ttl('bbs');  
//EXISTS key------检查给定key是否存在,存在,返回1,否则返回0  
$e = $redis->exists('email');  
//MOVE key db------将当前数据库的key移动到给定的数据库db当中  
$f = $redis->move('email',1);  
$redis->select(1);  
$f = $redis->keys('*');  
$f = $redis->move('email',0);  
//RENAME key newkey------将key改名为newkey  
$g = $redis->rename('email','e-eamil.com');  
$g = $redis->rename('e-eamil.com','eamil');  
//RENAMENX key newkey------当且仅当newkey不存在时,将key改名为newkey  
$h = $redis->renamenx('bbs','bbs1');  
//TYPE key------返回key所储存的值的类型  
$i = $redis->type('pageview');  
//EXPIRE key seconds------给key设置生存时间,当key过期时,它会被自动删除  
//PEXPIRE key milliseconds------以毫秒为单位设置key的生存时间  
//EXPIREAT key timestamp------命令接受的时间参数是UNIX时间戳,key存活到一个unix时间戳时间  
//PERSIST key------移除给定key的生存时间,转换成一个不带生存时间,永不过期的key  
//SORT key [BY pattern] [LIMIT offset count] [GET pattern [GET pattern ...]] [ASC | DESC] [ALPHA] [STORE destination]------返回或保存给定列表、集合、有序集合key中经过排序的元素   
  
/****************String(字符串)相关操作***************/  
//SET key value------将字符串值value关联到key,会覆盖  
$a = $redis->set('email','jiang@58haha.cn');  
//SETNX key value------将key的值设为value,当且仅当key不存在的时候,成功返回1,不成功返回0  
$b = $redis->setnx('email','jiang@58haha.cn');  
//SETEX key seconds value------将值value关联到key,并将key的生存时间设为seconds(以秒为单位)  
$c = $redis->setex('name',10086,'江');  
//PSETEX key milliseconds value------命令和SETEX命令相似,它以毫秒为单位设置key的生存时间  
$d = $redis->psetex('jiang',90000,'哈哈哈');  
//MSET key value [key value ...]------同时设置一个或多个key-value对  
$e = $redis->mset(array('ly' => 'liyang', 'fjf' => 'fengjingfeng'));  
//MSETNX key value [key value ...]------同时设置一个或多个key-value对,当且仅当所给定key都不存在  
$f = $redis->msetnx(array('ly' => 'liyang', 'xrj'=>'xingrongjiang', 'fjf' => 'fengjingfeng'));  
//APPEND key value------如果key已经存在并且是一个字符串,APPEND命令将value追加到key原来的值的末尾。如果key不存在,APPEND就像执行SET key value一样  
$g = $redis->append('ly','love');  
//GET key------返回key所关联的字符串值,如果key不存在那么返回特殊值nil,GET只能用于处理字符串值  
$h = $redis->get('ly');  
//MGET key [key ...]------返回所有(一个或多个)给定key的值,如果给定的key里面有某个key不存在,那么这个key返回特殊值nil,命令永不失败  
$i = $redis->mget(array('email','ly','fjf'));  
//GETRANGE key start end------返回key中字符串值的子字符串,字符串的截取范围由start和end两个偏移量决定(包括start和end在内),负数偏移量表示从字符串最后开始计数,-1表示最后一个字符,-2表示倒数第二个  
$j = $redis->getrange('email',0,-1); //从第一个到最后一个,相当于直接get  
//GETSET key value------将给定key的值设为value,并返回key的旧值(old value)  
$k = $redis->getset('email','jiangzunshao@163.com');  
//STRLEN key------返回key所储存的字符串值的长度  
$l = $redis->strlen('email');  
//DECR key------将key中储存的数字值减一,如果key不存在,那么key的值会先被初始化为0,然后再执行DECR操作  
$m = $redis->decr('pageview');  
//INCR key------将key中储存的数字值增一,如果key不存在,那么key的值会先被初始化为0,然后再执行INCR操作  
$n = $redis->incr('pageview1');  
//DECRBY key decrement------将key所储存的值减去减量decrement(可以为负值)  
$o = $redis->decrby('pageview',3);  
//INCRBY key increment------将key所储存的值加上增量increment(可以为负值)  
$p = $redis->incrby('pageview',6);  
  
/****************Hash(哈希表)相关操作***************/  
//HSET key field value------将哈希表key中的域field的值设为value,新建返回1,覆盖返回0  
$a = $redis->hset("user","jiang@58haha.cn","{'name':'jiangzunshao','age':25,'work':'php','city':'BeiJing'}");  
//HSETNX key field value------将哈希表key中的域field的值设置为value,当且仅当域field不存在的时候. 设置成功,返回1。如果已经存在且没有操作被执行,返回0  
$b = $redis->hsetnx("user","jiang@58haha.cn","{'name':'jiangzunshao','age':25,'work':'php','city':'BeiJing'}");  
//HGET key field------返回哈希表key中给定域field的值  
$c = $redis->hget("user","jiang@58haha.cn");  
//HMSET key field value [field value ...]------同时将多个field-value(域-值)对设置到哈希表 key中  
$d = $redis->hmset('user',array('name'=>'jiangzunshao', 'age' => 20));  
//HMGET key field [field ...]------返回哈希表 key中,一个或多个给定域的值  
$f = $redis->hmGet('user', array('name', 'age'));  
//HGETALL key------返回哈希表key中,所有的域和值  
$g = $redis->hgetall('user');  
//HDEL key field [field ...]------删除哈希表key中的一个或多个指定域,不存在的域将被忽略  
$h = $redis->hdel('user','age');  
//HLEN key------返回哈希表key中域的数量  
$i = $redis->hlen('user');  
//HEXISTS key field------查看哈希表key中,给定域field是否存在,存在返回1,不存在返回0  
$g = $redis->hexists('user','jiang@58haha.cn');  
//HINCRBY key field increment------为哈希表key中的域field的值加上增量increment,可以为负  
$k = $redis->hincrby('user','pv',5);  
//HKEYS key------返回哈希表key中的所有域  
$l = $redis->hkeys('user');  
//HVALS key------返回哈希表key中所有域的值  
$m = $redis->hvals('user');  
  
/****************List(列表)相关操作***************/  
//LPUSH key value [value ...]------将数据插入列表的头部  
$redis->lpush('dbs','mongodb-3.2');  
//RPUSH key value [value ...]------将数据插入列表的尾部  
$redis->rpush('dbs','redis');  
//LLEN key------获取列表的长度  
$c = $redis->llen('dbs');  
//LPOP key------移除并返回列表的头元素  
$d = $redis->lpop('dbs');  
//RPOP key------移除并返回列表的尾元素  
$e = $redis->rpop('dbs');  
//LRANGE key start stop------返回列表中指定区间内元素  
$f = $redis->lrange('dbs',0,$redis->llen('dbs'));  
$f = $redis->lrange('dbs',0,-1);  
//LSET key index value------将列表下标为index的元素的值设置为value  
$h = $redis->lset('dbs',1,'jiang');  
//LTRIM key start stop------列表只保留指定区间内的元素  
$i = $redis->ltrim('dbs',3,5);  
//LINDEX key index------返回列表中下标为index的元素  
$e = $redis->lindex('dbs',$redis->llen('dbs')-1);  
//LINSERT key BEFORE|AFTER pivot value------将值value插入到列表当中,位于值pivot之前或之后  
$g = $redis->linsert('dbs',Redis::BEFORE,'mysql','mysqlmysql');  
$g = $redis->linsert('dbs',Redis::AFTER,'redis','redisredis');  
//RPOPLPUSH source destination------命令RPOPLPUSH在一个原子时间内执行以下两个动作:1,将列表source中的最后一个元素(尾元素)弹出,并返回给客户端;2,将source弹出的元素插入到列表destination,作为destination列表的的头元素  
$k = $redis->rpoplpush('dbs1','dbs');  
  
/****************Set(集合)相关操作***************/  
//SADD key member [member ...]------将一个或多个member元素加入到集合key当中,已经存在于集合的member元素将被忽略  
$a = $redis->sadd('set', 'xingrongjiang');  
//SREM key member [member ...]------移除集合key中的一个或多个member元素,不存在的member元素会被忽略  
$b = $redis->srem('set','Array');  
//SMEMBERS key------返回集合key中的所有成员  
$c = $redis->smembers('set');  
//SISMEMBER key member------判断member元素是否集合key的成员  
$d = $redis->sismember('set','liyang');  
//SCARD key------返回集合key的基数(集合中元素的数量)  
$e = $redis->scard('set');  
//SMOVE source destination member------将member元素从source集合移动到destination集合  
$f = $redis->smove('set','set1','xingrongjiang');  
//SPOP key------移除并返回集合中的一个随机元素  
$g = $redis->spop('set');  
//SRANDMEMBER key [count]------如果只提供了key参数,那么返回集合中的一个随机元素;如果count为正数,且小于集合基数,返回一个包含count个元素的数组,数组中的元素各不相同;如果count大于等于集合基数,返回整个集合;如果count为负数,那么命令返回一个数组,数组中的元素可能会重复出现多次,而数组的长度为count的绝对值  
$h = $redis->srandmember('set',2);  
//SINTER key [key ...]------返回一个集合的全部成员,该集合是所有给定集合的交集  
$i = $redis->sinter('set');  
//SINTERSTORE destination key [key ...]------类似于SINTER命令,它将结果保存到destination集合,而不是简单地返回结果集  
$j = $redis->sinterstore('haha','set');  
//SUNION key [key ...]------返回一个集合的全部成员,该集合是所有给定集合的并集  
$k = $redis->sunion('set','set1');  
//SUNIONSTORE destination key [key ...]------类似于SUNION命令,它将结果保存到destination集合,而不是简单地返回结果集  
$l = $redis->sunionstore('haha1','haha','set1');  
//SDIFF key [key ...]------返回一个集合的全部成员,该集合是所有给定集合之间的差集  
$m = $redis->sdiff('set','set1');  

/****************有序集(Sorted set)相关操作***************/
//ZADD key score member------向名称为key的zset中添加元素member,score用于排序,如果该元素已经存在,则根据score更新该元素的顺序
$a = $redis->zadd('site', 10, 'google.com');
$a = $redis->zadd('site', 9, 'baidu.com');
$a = $redis->zadd('site', 8, 'sina.com.cn');
//ZREM key member------删除名称为key的zset中的元素member
// $b = $redis->zrem('site','sina.com.cn');
//ZCARD key------返回名称为key的zset的所有元素的个数
$c = $redis->zcard('site');
//ZCOUNT key min max------返回有序集key中,score值在min和max之间的成员的数量
$d = $redis->zcount('site',6,9); 
//ZSCORE key member------返回有序集key中,成员member的score值
$e = $redis->zscore('site','baidu.com');
//ZINCRBY key increment member------为有序集key的成员member的score值加上增量increment,返回值就是score加上increment的结果
$f = $redis->zincrby('site',10,'baidu.com');
//ZRANGE key start stop [WITHSCORES]------返回有序集key中,指定区间内的成员,其中成员的位置按score值递增(从小到大)来排序
$j = $redis->zrange('site',0,-1);
$j = $redis->zrange('site',0,-1,true);
//ZREVRANGE key start stop [WITHSCORES]------返回有序集key中,指定区间内的成员,其中成员的位置按score值递减(从大到小)来排列
$h = $redis->zrevrange('site',0,-1);
$h = $redis->zrevrange('site',0,-1,true);

/****************Connection(连接)***************/  
//AUTH password------密码认证  
$a = $redis->auth('123456');  
//PING------查看连接状态  
$b = $redis->ping();  
//SELECT index------切换到指定的数据库,数据库索引号index用数字值指定,以0作为起始索引值,默认使用0号数据库  
$c = $redis->select(1);  
  
/****************Server(服务器)***************/  
//TIME------返回当前服务器时间,一个包含两个字符串的列表:第一个字符串是当前时间(以UNIX时间戳格式表示),而第二个字符串是当前这一秒钟已经逝去的微秒数  
$a = $redis->time();  
//DBSIZE-----返回当前数据库的key的数量  
$b = $redis->dbsize();  
//BGREWRITEAOF------使用aof来进行数据库持久化  
$c = $redis->bgrewriteaof();  
//SAVE------将数据同步保存到磁盘  
$d = $redis->save();  
//BGSAVE------将数据异步保存到磁盘  
$e = $redis->bgsave();  
//LASTSAVE------返回上次成功将数据保存到磁盘的Unix时戳  
$f = $redis->lastsave();  
//SLAVEOF host port------选择从服务器  
$redis->slaveof('10.0.1.7', 6379);  
//FLUSHALL------清空整个Redis服务器的数据(删除所有数据库的所有key)  
$redis->flushall();  
//FLUSHDB------清空当前数据库中的所有key  
$redis->flushdb();  
//INFO [section]------返回关于 Redis 服务器的各种信息和统计数值  
$g = $redis->info(); 