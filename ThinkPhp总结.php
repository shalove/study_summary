<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>



注意ThinkPhp 是仿xml的，所以在tp中的标签必须关闭！！！！ 即使是单标签也要关闭！



1/ 控制器类和模型类的定义
 一般 第一步设定命名空间	namespace Admin\Controller        注意是反斜线
 		第二步引入父类的命名空间	use Think\Controller
 			第三步 建立类	class UserController extends Controller（）{}

 2、模型实例化
 
 普通的可以引入模型类的命名空间后，用new 模型类来实例化

 ThinkPhp中一般使用 D（）函数和	M（）函数

 D（）函数与M（）函数区别：
 	（1）都不传参数时，没有什么区别，都是实例化基础的父类 Model ，且实例化后无数据表关联
 	（2）D（）函数的参数是 实例化模型类的类名（不包含class.php部分的），M（）的参数是数据表名，
 		D（‘modelname’）实例化的是自定义模型类，关联的表是对应模型类名 modelname的数据表，
 		M（‘tablenanme’）实例化的是基础的父类（Model），关联的是数据表 tablename 表；
 		当D（‘name’）传递的name不存在自定义类时，就会实例化基础父类,再将name作为数据表名去关联（这时候类似于M（）函数）			

对于特殊表名的操作:
D()函数需要在对应的模型类中设置一个实例属性 $trueTableName = "真实表名(包含前缀)"
M（"tablename"，null）函数可以直接传递第二个参数为"tablename"，如果设置为null，表示不需要系统自动添加的前缀表名





tp 中的查询方法有两种：
    （1）$model.select（1）   $model.select（“1,2,3”） 	

    查询结果是二维数组  可以传参，参数为一个或多个主键的值（表示查询一个或是多个），注意即使查的是一个也还是返回的二维数组（索引型）

(2)$model.find(1) 

	查询结果是一维数组，参数可以是 一个主键值（因为他只能查询一条信息）



	3、辅助查询方法（就是原来的五子句的限定条件） $model是实例化的模型对象

	$model -> where('id=1') -> select();
	$model -> where(array('id'=>array("GT",4))) -> select();	where的参数可以是字符串，也可以是数组

	$model -> field('id,name') -> select(); 指定要查询的字段 默认是查询所有字段 *
	$model -> order('id desc') -> select(); 按照条件进行排序 desc倒叙 asc升序
	$model -> limit(3) -> select(); 
	$model -> limit(0,3) -> select(); 
	$model -> limit('0,3') -> select(); 限制查询条数
	$model -> group('cate_id') -> select();  分组 ，对应与原生sql的group by
	$model -> having('id>3') -> select();  查询条件
	Having和where的区别：
	Where 后面的字段必须是数据表中的字段，
	Having 必须是结果中的字段 是原生sql中select 后指定的字段名

	这里的辅助方法的参数和 sql语句中的写法都一样的，比如字段查询，sql中也是 select id，name
	逗号是要加的，所以在参数括号中也加上逗号



	4、连贯操作
	原理：中间的辅助方法返回值 是当前这个对象本身，所以可以继续调用这个对象的其他方法。
	$model->where("id > 1")->select();


	5/统计查询(就是原来的聚合函数)

	sum（） max（）  min（） avg（） count（）
	注意这几个聚合函数的地位和select等类似，是独立的方法，它的执行也是放在最后的（其他辅助方法写前面）

	$model ->where("id > 3")->count();



	6、连表查询			join（）

	alias（） 方法是起别名的方法，类似mysql中的 as 的作用

	$model->alias("a")->join("user as u on a.id = u.id")->select();
	默认 inner join 内连接查询



	7、 变量输出，显示到模板
	类似smarty

	$this->assign("data",$data);
	$this->display();



	8/模板中遍历数组
	{volist name="data" key="key" id="value" offset="1" length="3"}{/volist}
	注意 volist中的key从 1开始计数


	{foreach name="data" key="key" item="value"}{/foreach}
	foreach 中的key 从 0 开始计数


	9、 模板中的if判断

	<if condition=""> <elseif condition="" /> <else /> </if>
		condition 字符串里接条件，类似 if（）中的内容，注意 elseif算是单边标签，也得关闭



	10/ 使用函数

	{$变量名称|fn1|fn2=arg1,arg2,###}
	{:函数名称(参数)}

	{$demo.value| date="Y-m-d H-i-s",###}   ###表示当前的变量 demo.value
	{:date("Y-m-d H-i-s",$demo.value)}    类似于PHP中的函数写法



	11、系统变量
	$Think.server ：相当于$_SERVER[‘’]
	$Think.get	：相当于$_GET[‘’]
	$Think.post ：相当于$_POST[‘’]
	$Think.request ：相当于$_REQUEST[‘’]
	$Think.cookie ：$_COOKIE[‘’]
	$Think.session ：$_SESSION[‘’]
	$Think.config ：$Think.config.参数形式调用config.php中的内容

	这些是在模板中使用，类似于 smarty中的保留变量  $Smarty.get.id


	12/使用运算符
	运算符可以直接在模板文件中输出变量时使用。
		+		{$a+$b}
		-		{$a-$b}
		*		{$a*$b}
		/		{$a/$b}
		%		{$a%$b}  取余或取模
		++		{$a++} 或  {++$a}
		--		{$a--}  或 {--$a}


     13   数据添加
    $model -> add(一维数组);
        例如：  $model->add(array(
            "goods_name"=>"sha",
            "goods_price"=>"12",
    ))
        添加成功返回 最新插入的ID，失败返回false

    $model->addAll(二维数组);  批量的添加操作
    $arr = array(
        array("name"=>"sha"),
        array("name"=>"wang"),
    )
        $model->addAll($arr);
        添加成功返回 插入数组ID的第一条的id，失败返回false

    数据添加操作类比mysql数据库的操作，可以添加一条数据，也可以同时添加多条数据
    所以采用数组形式进行传参

    AR（active record）添加
    将表映射到类
    字段作为 $model对象的属性，用设置对象属性值的方式来给表字段添加数据，
        设置好对象属性之后使用 $model->add()进行添加






    14、查找：  $model->select(1)    $model->select("1,2,3")
            $model->find(1);

        查询有两种方法，select（）查询多条数据，返回的结果是一个二维数组；
        find（）查询的是一条语句，返回的结果是一个一位数组
    类比mysql中的查询，因为查询的时候限制条件往往比如查询id=1，或多条查询 id=1，id=2；
        所以传递的参数是单个数字（find的参数只能是一个数字，因为他只能查一条数据），或一个字符串中包含多个数字


    15、系统常量
    IS_GET      判断是否是get提交 （返回值是bool）
    IS_POST     判断是否是post提交 （返回值是bool）
    IS_AJAX      判断是否是ajax提交 （返回值是bool）
    IS_PUT
    IS_DELETE
    REQUEST_METHOD   当前提交的类型



    16、数据的接收函数 I（）

        I('变量类型.变量名',['默认值'],['过滤方法或正则'])

            eg：    I("get.id");   获取到get提交的id值   类似于  I = $_GET("id");
                    I("get.")   获取到get提交的所有值， 类似于  I = $_GET();

17、页面跳转与重定向
    $this->success(‘提示信息’,’跳转地址’,’等待时间’);   ------成功跳转

    $this->error(‘提示信息’,’跳转地址’,’等待时间’);     ----失败跳转
                跳转地址：默认返回上一个页面

    $this->redirect(‘跳转地址’,’url参数’,’等待时间’,’提示信息’);    ===重定向
    跳转地址：直接传递 分组名/控制器名/方法名
            Url参数可以省略
            等待事件默认为0即立即跳转
            提示信息默认为空
            示例：立即跳转没有任何提示

对于success/error/redirect
        success/error的跳转地址都可以用 U（）函数来设置
    error如果不传 跳转地址会默认跳转回上个页面，所以大多时候都不会填跳转地址
    redirect的url地址不要用U（）函数去设置，因为redirect（）函数里已经将url地址进行了 U（）函数的
    使用，所以传递的时候只要直接传递字符串地址就行  “Admin/Goods/goods_list”


        18/数据的修改
        $model -> save(数组);   //数组里面必须要包含主键id  TP框架中不允许没有条件的修改！！
        $model -> where(条件) -> save(数组)	//使用连贯操作在where中声明条件

        对于修改操作，要么在传递的参数数组里填上主键，要么在使用save（）前添加where条件，
        tp中不允许没有条件的修改！！

        AR方式进行修改
        $model -> save();	//如果上面的属性对里面包含了主键字段，则这里不需要写条件
        $model -> where() -> save()	//如果上面的属性对里没有包含主键字段，则需要使用where辅助方法

        如果使用数据对象形式（用create创建对象，或AR方式创建数据对象），save（）里可以不传参数，
        他会自动识别数据对象去保存


19、数据的删除操作
    真假删除：
        假删除： 逻辑删除：假删除（本质是修改操作）
                并不是直接从数据表删除数据，给数据表添加一个状态字段is_show,
                 0表示不显示 1表示显示
                在列表查询数据时可以添加一个where条件，只获取is_show=1的数据
                这时删除操作要做的，就是修改is_show这个字段的值为0

    真删除：物理删除：真删除
            直接从数据表删除数据

    删除 delete（）；
            $model -> delete(id)		//表示删除指定主键的id记录
            $model -> delete(‘id1,id2,id3’)	//表示删除多个主键id的记录
            也可以使用where方法指定删除条件
            $model -> where(‘id=16’) -> delete();
            返回值 成功时是受影响的记录条数，失败false


    总结：对于增删改查

  添加和修改：
            他们的参数都是数组，因为他们基本都是操作一条或多条数据
            只是修改必须要有条件的修改，要么在参数中传递主键，要么加where条件限制
            他们都有AR方式来进行操作
            添加有两种方法   add（）添加单条数据   addAll（）添加多条数据

查找和删除：  参数都是 单个数字或多个数字的字符串   select（"1,2,3"）
            因为他们操作的都是相当于把一条数据看成一个整体去 查找和删除，所以不用数组形式

    注意查找有两种方法   select（）查询多条数据，返回二维数组
                    find（） 查询一条语句，返回一位数组

            删除有  真假删除   真删除直接物理删除
                            假删除是将字段信息改变，使其不显示出来


    20/ 关于create
        create 适用于创建数据对象的，如果不传参数，它默认创建的是 post提交的数据构成的数据对象，
如果传参，创建的就是根据传递的参数（数组或对象）形成的数据对象。
    create 方法创建的数据对象是保存在内存中的，并没有写到数据库，在没有调用add或save方法之前都是可以修改的

    create 有两个参数，第一个是要创建的数据对象的数据来源，第二个是指定创建数据的操作状态，默认情况
    根据是否有主键自动判断是插入还是更新（有主键）

    create 的工作流程：
        （1）获取数据源 ， 默认是post数组
        （2）验证数据源合法性（必须是数组或对象）
        （3）检查字段映射
        （4）判断数据状态（新增或编辑，或自动判断）
        （5）数据自动验证
        （6）表单令牌验证
        （7）表单数据赋值（过滤非法字段和字符串处理）
        （8）数据自动完成
        （9）生成数据对象（保存在内存中）





</body>
</html>