<!doctype html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Light Theme</title>
<link href="css/singlePageTemplate.css" rel="stylesheet" type="text/css">
<script>var __adobewebfontsappname__="dreamweaver"</script>
<script src="http://use.edgefonts.net/source-sans-pro:n2:default.js" type="text/javascript"></script>
</head>
<body>
<div class="container">
  <header> <a href="homepage.php">
    <h4 class="logo">12306主页</h4>
    </a>
    <nav>
      <ul>
        <?php
        $user = $_SESSION['loginname'];
        if($user==""){
        echo "<li><a href=userlogin.php>用户登录</a></li><li><a href=adminlogin.php>管理员登录</a></li>";
        }else{
        echo "<li>欢迎!".$_SESSION['loginname']."</li>"
        ."<li><a href=myorder.php>我的订单</a></li>"
        ."<li><a href=quit.php>退出</a></li>";
        }
        ?>
      </ul>
    </nav>
  </header>
  <section class="banner">
    <h2 class="parallax">注册</h2>
    <div class="parallax_description"> 用户名:<br>
      <form action="signupcheck.php" method="post" name="myform" >
        <input type="text" name="username" id="username" placeholder="请输入用户名">
        <br>
        真实姓名:<br>
        <input type="text" name="realname" id="realname" placeholder="请输入真实姓名">
        <br>
        身份证号:<br>
        <input type="text" name="userid" id="userid" placeholder="请输入身份证号">
        <br>
        手机号码:<br>
        <input type="text" name="phone" id="phone" placeholder="请输入手机号码11位">
        <br>
        信用卡号:<br>
        <input type="text" name="creditcard" id="creditcard" placeholder="请输入信用卡号16位">
        <br>
        密码:<br>
        <input type="password" name="userpwd" id="userpwd" placeholder="请输入密码">
        <br>
        确认密码:<br>
        <input type="password" name="confirm" id="confirm" placeholder="请再输入一次密码">
        <br><input  type = "hidden" name = "hidden"  value = "hidden">
        <br>
        <button type="submit">用户注册</button>
      </form>
      <br><button  onclick="window.location.href='userlogin.php'">已有用户账号？来登录！</button>
    </div>
  </section>
  <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
</div>
</body>
</html>
