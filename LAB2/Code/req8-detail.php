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
    <h2 class="parallax">车次详细信息</h2>
    <div class="parallax_description">
      <?php 
      $user = $_SESSION['loginname'];
      $orderid = $_REQUEST['orderid'];
      $trainid = $_REQUEST['trainid'];
      $startdate = $_REQUEST['startdate'];
      $starttime = $_REQUEST['starttime'];
      $arrivetime = $_REQUEST['arrivetime'];
      $seattype = $_REQUEST['seattype'];
      $price = $_REQUEST['price'];
      $station1 = $_REQUEST['station1'];
      $station2 = $_REQUEST['station2'];

      $buf .= "";
      $buf .= "订单号:" . $orderid . "</br>";
      $buf .= "车次:" . $trainid . "</br>";
      $buf .= "出发站:" . $station1 . "</br>";
      $buf .= "到达站:" . $station2 . "</br>";
      $buf .= "出发日期:" . $startdate . "</br>";
      $buf .= "出发时间:" . $starttime . "</br>";
      $buf .= "到达时间:" . $arrivetime . "</br>";  
      $buf .= "座位类型:";
    switch($seattype)
    {
      case 0 : $buf .= "硬座</br>"; break;
      case 1 : $buf .= "软座</br>"; break;
      case 2 : $buf .= "硬卧（上）</br>"; break;
      case 3 : $buf .= "硬卧（中）</br>"; break;
      case 4 : $buf .= "硬卧（下）</br>"; break;
      case 5 : $buf .= "软卧（上）</br>"; break;
      case 6 : $buf .= "软卧（下）</br>"; break;            
    }
      $buf .= "价格:" .$price . "</br>";
      echo $buf;
      
      $date1 = $_REQUEST['date1'];
      $date2 = $_REQUEST['date2'];
      echo "<a href='javascript:history.go(-1);'><button type='button'>返回</button></a>";
      ?>
    </div>
  </section>
  <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
</div>
</body>
</html>
