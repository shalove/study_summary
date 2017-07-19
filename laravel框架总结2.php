<?php 
1、compact函数用法
	compact() 函数
	创建一个包含变量名和它们的值的数组，
	本函数返回的数组是一个关联数组，键名为函数的参数，键值为参数中变量的值。


2、模板继承

	第一步：定义一个公共模板（被继承的模板）是网页的共同部分
		使用@yield('main')标签，指定被替换的位置（可变的区域）；

	第二步：继承模板
		语法：@extends('公共模板路径')
		@section('main')
		@endsection


	模板包含
		@include('admin.sub') 



3、模板中引入资源的路径
	{{asset('资源路径')}} 

	引入其他页面的路由,路由要写完整
	{{url('路由')}}



4、CSRF攻击
	跨站请求伪造（Cross-site request forgery）

	Laravel框架中避免CSRF攻击很简单：Laravel自动为每个用户Session生成了一个CSRF Token，
	该Token可用于验证登录用户和发起请求者是否是同一人，如果不是则请求失败。

	Laravel提供了一个全局帮助函数csrf_token来获取该Token值，因此只需在视图提交表单中添加如下HTML代码即可在请求中带上Token：

	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">


	或在表单中直接使用 {{csrf_field()}}


	要注意：{{csrf_field()}}和{{csrf_token()}}的区别:

	{{csrf_field()}}直接生成
	<input type="hidden" name="_token" value="DdN0PTagKRfqssEWHmIOcASOnURZACOyoCOcpIPR">

	{{csrf_token()}}直接生成令牌字符串。


	也就是{{csrf_field()}}将表单和token值一起生成了， {{csrf_token()}}只生成token值，不生成表单


	从CSRF验证中排除指定URL
		可以通过在VerifyCsrfToken（app/Http/Middleware/VerifyCsrfToken.php）中间件中将要排除的请求URL添加到$except属性数组中：



二、模型
	ORM模型
	一个模型类对应一张数据表
	一个模型类的实例对应表中的一行数据

	模型定义的默认位置在 app目录下

	命名规则
		表名(首字母大写).php   Member.php       不同于其他框架的  MemberModel.php


	2、创建模型
		在artisan命令所在目录使用cmd，使用命令
		php artisan make:model  admin/Member 			生成 app/admin/Member.php

		不写 admin路径，默认生成 app/Member.php



	3、模型中属性的设定
		生成模型后需要对模型中的属性进行配置

		<?php
		namespace App\Models;
		use Illuminate\Database\Eloquent\Model;
		class Member extends Model
		{
		    //$table用于表名此模型关联的数据表是member表，不写$table，默认关联模型名加s的表，也就是members 表
		    protected $table = 'member';	

		    //设定主键字段，不写默认 id 
		    protected $primaryKey = 'id'; 

		   // 定义$timestamps属性，值是false,如果不设置为false，则默认会操作表中的created_at和updated_at字段,我们表中一般没有这两个字段，所以设置为false,表示不要操作这两个字段。
		    public $timestamps = false;

		    //$fillable表示表单提交时允许插入到数据库的字段信息。
		    protected $fillable = ['name','age'];

		    //排除入库的字段,不允许入库的字段
		    protected $guarded = ['_token'];
		}




三 模型基本操作 （增删改查）

	1、 添加数据

		首先，在控制器文件引入Request这个类
		use Illuminate\Http\Request;

	public function addok(Request $request)			//依赖注入 获取当前请求的request的实例 $request
    {
    	$data = $request->all();		//获取所有提交的数据

    	$res = Member::create($data);	//将数据添加到数据库
    }


    $request->all()								获取所有提交的数据，
	$request->input()  							获取所有提交的数据
	$request->input('字段名称'); 				可以获取单个字段的值
	$request->only(['字段名称'，'字段名称'])	获取指定的多个值
	$request->except(['字段名称'，'字段名称'])	获取除排除的值之外的所有值
	$request->has('字段名称')					判断提交的数据是否有某个字段的值	
	$request->get('字段名称')					获取指定的值			





	2、查询数据

	获取一条数据 
		Member::find(4);  //find里填的是主键值	

	按条件查询，获取一行数据
		Member::where('id','>',4)->first();

	查询多行，指定字段数据
		Member::all([字段1,字段2]);

	按条件查询指定多个字段
		Member::where('id','>',2)->get(['列1','列2']);
		Mmeber::where('id','>',2)->select(['列1'，'列2'])->get();
		Mmeber::where('id','>',2)->select('列1'，'列2')->get();

		不写条件
		Member::get();  查询所有字段的值


		排序查询：
		Member::where('id','>',2)->select([‘id’,’name’])->orderBy('id','desc')->get();

		限制条目查询：
		Member::where('id','>',2)->orderBy('id','desc')->skip(2)->take(1)->get();
		Member::where('id','>',2)->orderBy('id','desc')->offset(2)->limit(1)->get();






	3、修改数据
	  注意：在laravel里面如果需要更新数据，需要先调用模型的find方法获取对应的记录，返回一个对象，然后为该对象设置要更新的数据，最后调用save方法即可。

	    public function updateok(Request $request)
    {
    	$id = $request->input('id');
    	$member = Member::find($id);	//查出要修改的单条信息
    	$member->name = $request->input('name');	//修改name
    	$member->age = $request->input('age');	//修改age
    	$member->email = $request->input('email');	//修改email
    	$res = $member->save();		//进行修改数据库
    	if($res){
    		return redirect('admin/mem/index');
    	}
    	return redirect('admin/mem/update/'.$id);
    }








    4、删除数据
   		注意：在laravel里面如果要删除数据，必须先根据主键id查询对应的记录，返回一个对象，然后调用对象的delete方法即可。

   		 public function del($id)	
    {
    	//这里的参数$id是地址栏传递的，可以直接在方法中作为参数获取
    	$member = Member::find($id);	//查出要删除的数据记录
    	$member->delete();				//删除记录
    	return redirect('admin/mem/index');
    }





 三、laravel中 分页
 	（1）先在控制器index方法中获取数据
		public function index()
    {
    	$data = Member::paginate(2);	//每页显示2条
    	//将数据分配显示到模板
    	return view('admin/member/index',compact('data'));
    }

    （2）在index.blade.php视图显示数据和分页
	在要显示分页字符串的地方使用 {{ $data->render()}} 即可


	分页字符串 	{{$data->render()}}
	总记录数	{{$data->total()}}
	当前页 		{{$data->currentPage()}}
	这三个都是在模板中显示使用的





	四、文件上传
		获取上传的文件
		$file = $request->file('filename');

		验证文件是否存在,是否有文件上传
		$request->hasFile('filename');

		验证文件是否上传成功
		$request->file('filename')->isValid()

		//获取上传文件扩展名
		$ext = $file->getClientOriginalextension();
		//获取上传文件名称
		$truename = $file->getClientOriginalName();
		//获取上传文件大小
		$filesize = $file->getClientSize();
		//获取上传文件的临时存储路径
		$tempath = $file->getRealPath();
		//移动上传文件到指定目录
		$file->move('指定路径','文件名');



	五、自动验证

		验证方式一（控制器方式验证）
		使用控制器中的validate方法来完成，$this->validate($request,[验证规则]);

		基本验证规则：
		required: 不能为空
		max:255最长255个字符，
		min:1最少1个字符
		email:验证邮箱是否合法
		confirmed:验证两个字段是否相同，如果验证的字段是password,则必须输入一个与之匹配的password_confirmation字段
		integer:验证字段必须是整型
		ip:验证字段必须是IP地址
		numeric 验证字段必须是数值
		max:value 验证字段必须小于等于最大值，和字符串，数值，文件字段的size规则一起使用。
		min:value 验证字段的最小值，对字符串、数值、文件字段而言，和size规则使用方式一致。
		size:value 验证字段必须有和给定值value想匹配的尺寸，对字符串而言，value是相应的字符数目，对数值而言，value是给定整型值；对文件而言，value是相应的文件字节数。
		string 验证字段必须是字符串
		unique:表名，字段，需要排除的ID


		注意：多个验证规则可以通过 "|" 字符进行隔开



	验证方式二（Request表单验证）

		第一步：使用php artisan建立一个验证的类文件，文件名称任意。
		注意：这个命令生成的文件位于app/Http/Requests/这个文件夹当中。

		第二步：在生成的类文件中，定义验证规则