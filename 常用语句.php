<?php 

//	1、PDO
try{
	$dsn = "mysql:host=localhost;dbname=hm4;charset=utf8";
	$pdo = new PDO($dsn,'root','123456');
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT * FROM student WHERE name like '%张三%'";
	$stmt = $pdo->query($sql);
	$stmt->execute();
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
	echo $e->getCode().':'.$e->getMessage();
}


// 	2、不用第三个值交换 a,b
$a = 10 ; 
$b =20;

$a = $a + $b;   
$b = $a - $b;   //  相当于 $a + $b - $b
$a = $a - $b;   //相当于 $a + $b - $b   而此时$b=$a(第二步交换的),所以就相当于 $a+$b-$a


// array 和 list 不是php的函数，他们都是语言结构


//注意输出语句中的数组的输出,不加大括号就不要再加单引号会报错，要加单引号就要加上大括号
$arr = array('name'=>'sha');
echo "my name is $arr[name]" ;
echo "my name is {$arr['name']}";

