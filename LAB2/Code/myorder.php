<?php session_start();?>
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
    <h2 class="parallax">订单查询</h2>
    <div class="parallax_description">
    <form action= "req7.php" method="post">
      <input type="date" value="2018-11-23" name="date1" placeholder="请在此输入开始日期">
      <input type="date" value="2018-11-25" name="date2" placeholder="请在此输入截止日期">
      <button type="submit">确认</button>
    </form>
  </div>
  </section>
  <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
</div>
</body>
</html>
