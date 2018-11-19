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
    <h4 class="logo">12306</h4>
    </a>
    <nav>
      <ul>
        <li><a href="homepage.php">主页</a></li>
        <li><a href="userlogin.php">用户登录</a></li>
      </ul>
    </nav>
  </header>
  <section class="banner">
    <h2 class="parallax">管理员登录</h2>
    <div class="parallax_description"> 管理员名:<br>
      <form action="adminlogincheck.php" method="post">
        <input type="text" name="adminname" placeholder="请输入管理员名">
        <br>
        密码: <br>
        <input type="password" name="adminpwd" placeholder="请输入密码">
        <br><input  type = "hidden" name = "hidden"  value = "hidden">
        <br>
        <button type="submit">登录</button>
      </form>
    </div>
  </section>
  <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
</div>
</body>
</html>
