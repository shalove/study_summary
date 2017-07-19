<?php 

1、静态化网站与动态化网站
	静态化网站指的是纯html页面的网站
	动态化网站指访问的比如index.php这样的脚本页面

	静态化网站又分为 真静态和伪静态

	真静态：访问的就是真的纯html页面（从url格式到访问的内容）
	伪静态：访问的url格式是html（访问的内容是其他动态语言脚本比如.php文件）


2、并发
	指同时处理多个活动
	并发请求数：每秒的请求数量

	QRS	: Query Per Second	
	RPS	：Request Per Second
	
	压力测试： 通常是测试网站能够承受处理多大的并发请求
	测试工具： apache中有个 ab.exe 就可以用于测试

	命令格式：  ab -n 1000 -c 100 http://www.hm4.com/
				
				ab  测试命令
				-n 总的请求数	（总共发送1000次请求）
				-c 并发请求数	（每秒发送100个请求）
				http://www.hm4.com/ 	请求的网站地址		


3、三大缓存技术
	（1）浏览器缓存
	（2）程序缓存
	（3）ob缓存

	（1）浏览器缓存：
		一次请求得到的返回数据，返回有一个过程。数据返回到浏览器，先进入浏览器缓存空间，只有当缓存达到一定的大小，才会显示到浏览器页面。
		如果所有的返回数据都返回完成，还没有达到浏览器缓存大小限制，就一次性显示到页面。

	（2）程序缓存
		Php中的自带的缓存。当我们在php中分多次输出数据时，一次一次把数据先放到程序缓存空间，当整个请求处理完毕（结束），再把程序缓存输出到浏览器。
		浏览器缓存和程序缓存一般情况下开发人员是无法控制的。

		这里的输出数据的理解可以看成是 最终在页面显示能看到的数据都是算输出（就是在查看页面源码中能看到的），注意head标签里的算是输出，但是header头信息并不是输出
		在程序缓存中，每次请求只会设置一次header头信息，而且一旦有输出缓存到程序缓存中，程序缓存中就会自动设置一个头信息。所以如果在设置header头信息之前有输出，程序缓存中就已经有了头信息，这时候再设置就会报错，说已经设置了头信息；如果开启ob缓存，输出会先放到ob缓冲区，这时候不会自动设置header，再设置header就不会报错


	（3）如果开启了ob缓存，程序的输出内容会先放到 ob缓存中，最后再输出到程序缓存。
		要使用ob缓存需要开启php中的配置项 output_buffering = ON|OFF

		如果想不开启配置也可以使用ob缓存，可以使用 ob_start（）函数来开启ob缓存，但是只对当前请求有效，相当于临时设置


	3.1 ob缓存常用函数

		ob_start()  			打开输出缓冲（只对当前请求生效）
		ob_get_contents（）  	获取输出缓冲区中的内容
		ob_clean（）			删除当前缓冲区中的内容
		ob_get_clean（）		获取输出缓冲区中的内容并删除当前缓冲区
		ob_end_clean() 			关闭输出缓冲区并且删除输出缓冲区的内容
		ob_flush（） 			送出输出缓冲区内容（送到程序缓冲）

		当输出缓冲区的数据达到一定的内容大小，或者请求处理结束，也会自动把输出缓冲区内容送出到程序缓冲


		真静态缓存生命周期：
			有效期：上次修改时间  +  10s     表示有10秒的有效期

		获取文件的上次修改时间可以使用php自带函数  filemtime（）


4、伪静态
	伪静态网站的内容实际上还是动态的，只是他的url地址看起来是静态的，这主要是使用了url重写机制，把原来的动态网站的url改写成静态的形式

	示例：
		  原始地址：http://www.hm4.com/index.php/Home/Index/detail/id/29

		伪静态地址：http://www.hm4.com/goods_detail_29.html

		这里需要把原来的index.php/Home/Index/detail/id/29 改写成 goods_detail_29.html的形式，还要让改写后的地址能够正确访问


	要使用重写机制
	（1）首先的开启apache中的 rewrite_module模块，
	（2）修改apache主配置文件和虚拟主机配置文件 ，确保所有的主机配置中，有AllowOverride All这个配置（保证能进行分布式配置）
	（3）在网站根目录 新建一个.htaccess 文件（分布式配置文件）
		在.htaccess中定义url的重写规则



5、真静态、伪静态、动态网站优劣势
	真静态
		优点 :  （1）浏览者访问网站时，不用读取数据库，直接访问网站空间对应的文件（直接读取文件）
				（2）纯静态的网页对搜索引擎友好，是最容易被搜索引擎所收录的。（易收录）
				（3）由于访问网页的时候，不需要服务器做过多的处理，对服务器的压力最小，所以，更容易应对高访问量（节省服务器压力）
				（4）一些面对数据库的攻击比如SQL注入攻击，在面对静态网页的时候常常难以从地址入手（安全性高）

		缺点： （1）由于静态网页需要生成文件，所以当网站内容更新频率高，更新数据量大的时候，对服务器磁盘的写入也会很频繁；（更新频繁时服务器的负担大）
				（2）在不采用其他技术的时候，如果更改了模板，所有相关的html网页都要重新生成，这在面对大数据量的时候，也不是一件很好玩的事。（模板修改、对应变化）

	动态页
		优势：
			1.动态页由于不用生成html文件，所以可以节省服务器空间，这样我们可以把更多的资金放在数据库上，节省出来的服务器空间可以用来放更多的图片附件等文件；（节省服务器空间）
			动态页劣势：
			1.不如静态的网页容易被收录（收录难）
			2.一些面对数据库的攻击比如SQL注入攻击，在面对动态网页的时候常常容易从地址入手（安全性低）
			

	伪静态
			伪静态页优势：
			1.在网址的形式上看，伪静态的地址和静态的可以一摸一样，普通的访问者不容易分出是真静态还是伪静态，同时蜘蛛一般也会把这种形式的网页当做是静态的来处理。（易收录）
			伪静态页劣势：
			1.伪静态相对动态来说，更加消耗服务器资源，因为网页地址需要按事先设定好的伪静态规则来进行正则匹配，这一个过程是消耗资源的。（消耗服务器资源）