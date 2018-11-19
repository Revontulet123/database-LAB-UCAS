<?php
session_start();
header("Content-Type: text/html;charset=utf-8"); 

 if(isset($_POST["hidden"]) && $_POST["hidden"] == "hidden") 
 { 
	 $admin = trim($_POST["adminname"]);
	 $psw = trim($_POST["adminpwd"]);
	 if($admin == "" || $psw == "") { 
	 	echo "<script>alert('请输入管理员名或者密码！'); history.go(-1);</script>"; 
	 }
	 else { 
	 $dbconn = pg_connect("dbname=test user=dbms password=dbms") or die('Could not connect: ' . pg_last_error());
	 $sql = "select adminname,password from admins where adminname = '$admin' and password = '$psw'"; 
	 $result = pg_query($sql); 
	 $num = pg_num_rows($result); 
	 if($num) { 
	 	$_SESSION['loginname'] = $admin;
	 	echo "<script>alert('管理员成功登录'); window.location.href='adminloginok.php';</script>"; 
	 } 
	 else { 
	 	echo "<script>alert('管理员名或密码不正确！');history.go(-1);</script>"; 
	 } 
	} 
} 
else { 
	echo "<script>alert('提交未成功！');</script>"; 
} 

?> 
