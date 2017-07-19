<?php 

1、目前的框架的特点
	（1）单入口，所有请求都从单一入口进入，便于管理（统一参数过滤）
	（2）都是基于MVC思想（分层思想，便于协同开发，后期维护方便）
	（3）ORM（Object Relations Model） 操作数据库

	laravel框架的URL访问，都是事先必须定义好的路由

	要使用laravel对运行环境有要求
		PHP版本 >= 5.6.4
		PHP扩展：OpenSSL、PDO、Mbstring、Tokenizer
		如果使用wamp环境，还要开启Apache的Rewrite模块


2、composer
	composer是PHP中用来管理依赖（dependency）关系的工具

3、使用composer方式部署项目
	第一步：切换镜像为国内镜像
composer  config -g repo.packagist composer https://packagist.phpcomposer.com
	第二步：composer部署laravel项目。
比如创建一个名为shop的laravel项目
composer  create-project  laravel/laravel  shop --prefer-dist


4、laravel启动方式
	方式一：使用 wamp或lamp环境，配置虚拟主机
	项目的入口文件在 public\index.php，所以配置的路径配到public下


	方式二：
Laravel框架提供了更简单的方式启动项目（相比配置apche）
以cmd方式，进入到artisan文件所在的目录，执行php artisan serve
这样的话就可以在浏览器中直接访问 localhost:8000就可以了（默认访问到public/index.php）


5、目录结构分析
	（1）app目录：
	项目的核心目录，主要用于存放核心代码，也包括控制器、模型以及路由。
控制器存放位置：
	控制器在 app/Http/Controllers
	模型在 app/ 下
	这两个是默认路径，也可以自己创建的时候定义路径

（2）bootstrap目录，laravel启动目录

autoload.php文件用于自动载入需要的类文件。

（3）config目录，项目的配置目录，主要存放配置文件，比如数据库的配置

（4）database目录，数据迁移目录

（5）public目录，项目的入口文件和系统的静态资源目录（css,img,js,uploads）

（6）resources目录，存放视图文件
	视图默认目录  resources/views/

（7）storage目录，主要是存放缓存文件和日志文件，注意，如果在linux环境下，该目录需要有可写权限。

（8）vendor目录，主要是存放第三方的类库文件（例如，里面可能存在验证码类，上传类，邮件类），该目录还存放laravel框架的源码。composer下载的类库都是存放在该目录下面的。

（9）.env文件：主要是设置一些系统相关的环境配置文件信息。config目录里面的文件配置内容一般都是读取该文件里面的配置信息（config里面的配置项的值基本都是来自.env文件）。

（10）artisan脚手架文件，主要用于生成的代码的，比如生成控制器，模型文件等。

（11）composer.json依赖包配置文件

（12）routes 存放路由文件



6、laravel路由配置
	路由文件的位置:laravel/routes/web.php文件

	Route::请求方式（'请求的URL', 闭包函数或控制响应的方法）

	例如：Route::get('/',function(){return 'hello Laravel'})


	请求方式： get、post、put、patch、delete、options


	对于多种请求的路由定义可以通过match和any方法实现

6.1 路由参数
	主要针对GET请求
	Route::get('URL/{参数名称}','闭包函数或控制器响应方法标识')->where('参数名称','正则表达式')


	Route::get('admin/{name}',function($name){

		return '你的名字：'.$name;
	})

后面的where表达式可填可不填，用于限定输入的参数。


参数也可以选填，加个?号表示可选
Route::get('admin/{name？}',function($name='sha'){

		return '你的名字：'.$name;
	})

此时，如果不传递参数的话默认的name就是sha

参数可以多个
Route::get('admin/{name？}/{age}/{sex}',function($name='sha',$age,$sex){

		return '你的名字：'.$name.$age.$sex;
	})


6.2 路由别名
	Route::get('user/name',['as'=>'name',function(){
	return route('name');
}])

这里把 user/name路径起了个别名 name，之后就可以使用函数 route（‘别名’）来代替 user/name 这个完整路径了


6.3 路由前缀
	当多个路由有共同的前缀时，可以指定分组前缀统一管理
	admin/index
	admin/login 
	admin/add 
	admin/del

	Route::group(['prefix'=>'admin'],function(){
		Route::get('index',function(){
			return 'admin/index';
		});
		Route::get('login',function(){
			return 'admin/login';
		});
		...
	});



7、控制器

	命名方式
	控制器名称(首字母大写)+Controller.php 
	IndexController.php 
	LoginController.php 

创建方式：cmd方式 进入到artisan文件所在的目录，
使用命令  php artisan make:controller admin/IndexController

这里创建的是 app/Http/Controllers/admin/IndexController.php
前面不加admin的话，默认是在Controllers下面


控制器路由的定义：
	在web.php里定义
	Route::get('admin/index','admin\IndexController@index');

	第一个参数 admin/index 表示在浏览器地址栏输入的url
	比如 localhost：8000/admin/index
	输入了这个地址后，就会对应到第二个参数的路径去找控制器和方法

	第二个参数表示在 admin下的 IndexController控制器中的 index方法，注意控制器路径分隔得用反斜杠！！！！！


	如果一个控制器下有多个方法，可以采用分组定义

	Route::group(['prefix'=>'admin','namespace'=>'admin'],function(){

		Route::get('index','IndexController@index');
		Route::get('add','IndexController@add');
		Route::get('del','IndexController@del');

	});

	这里的 prefix指定了浏览器中输入的地址的前缀
			namespace指定了控制器命名空间的前缀
			@后的是方法名



8、接收用户的输入   Input类

	要使用Input类需要在控制器中先引入，
	Illuminate\Support\Facades\Input 

	Input::get(‘参数的名字’, ‘默认值’) 
	用于获取get请求传递的参数，如果填了默认值，当用户未传参时，使用默认值

	Input::all()	 获取所有的用户的输入

	Input::only(['名1'，'名2'..])	 获取指定的几个用户输入的值

	Input::except(['名1'，'名2'..]): 获取除指定的几个用户输入以外的所有的参数



9、laravel    DB类操作数据库     (不使用模型层直接操作数据库)

	首先要使用DB类，也要先引入才行
	use DB;

	操作方式：
	DB::table('tableName') 



	以下假设数据表为 member表

	（1）增：insertGetId    insert

	insertGetId---插入单条数据，返回最新ID
	DB::table('member')->insertGetId(
		['name'='sha','age'=>19,'sex'=>'男']
		);

	insert---插入多条数据，返回bool
	DB::table('member')->insert(
		array(
			['name'='sha1','age'=>19,'sex'=>'男'],
			['name'='sha2','age'=>19,'sex'=>'男'],
			['name'='sha3','age'=>19,'sex'=>'男']
			)
		);




	（2）修改  update   increment   decrement

		修改id=1的数据
		DB::table('member')->where('id','=','1')->update(
			['name'=>'yu','age'=>'18'，'sex'=>'女']
			);

		修改id>2的年龄加5
		DB::table('member')->where('id','>','2')->increment(
			'age',5);


	(3)查找   get 
	1、获取member表中所有的数据

	DB::table('member')->get();   相当于select * from member;

	2、获取id<5的数据
	DB::table('member')->where('id','<','5')->get();


	3、取出单行数据
	DB::table('member')->where('id','1')->first();	//	条件为等于时，可以不写
	
	4、获取某个具体的值  （id为1的 name字段的值）
	DB::table('member')->where('id','1')->value('name')

	5、获取指定字段数据
	DB::table('users')->select('name', 'email')->get();
 	DB::table('users')->distinct()->get();
	DB::table('users')->select('name as user_name')->get();



	6、排序操作
	DB::table('member')->orderBy('age','desc')->get();

	7、分页操作
	DB::table('member')->limit(3)->offset(2)->get();




	（4）删除数据  delete()

	数据删除可以通过delete函数和truncate函数实现，

	DB::table('table_name')->where('id','>','1')->delete();




	(5)执行任意sql语句

	   1）执行任意的insert update delete语句
		DB::statement(“insert into member values(null,’’)”);

		2）执行任意的select语句
		$res = DB::select("select * from member");






三、视图

	1、视图文件的命名

	（1）文件名习惯小写（建议小写）
	（2）文件名的后缀是 .blade.php（因为laravel里面有一套模板引擎就是使用blade，可以直接使用标签语法{{ $title }}， 也可以使用原生的php语法显示数据。）
	（3）需要注意的是也可以使用.php结尾，但是这样的话就不能使用laravel提供的标签{{ $title }}语法显示数据，只能使用原生语法 <?php echo $title;?> 显示数据
	（4）两个视图文件同时存在，则.blade.php后缀的优先显示。


	2、加载视图

		view（‘视图名称’，'传递的参数'）

		//直接传参
		view('admin/index',['name'=>'sha','age'=>19]);

		//使用compact函数
		$data = ['name'=>'sha','age'=>19];
		view('home/add',compact('data'));


	
	3、模板中直接使用函数
	
	用双大括号括起变量是 laravel中的变量输出方式，类似TP中的 {$name}

	{{md5($name)}}

	{{date('Y-m-d',$time)}}

	{{substr($name,3,2)}}


	4、模板中使用过程控制语法
	
	每个语法前加 @ 符即可
	
	{{$name}}等价于<?php echo $name?>

	{{$name  or  'default'}}等价于<?php echo isset( $name)?$name:'default'?>

	@{{$name}}禁止解析   类似于literal标签的作用，原样输出，不解析


	@foreach($arr as $key => $value)
		中间写循环内容
	@endforeach


	@if(条件)

	@else
	
	@endif