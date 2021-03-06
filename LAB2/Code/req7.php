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
    <h2 class="parallax">我的订单查询结果</h2>
    <table class="gridtable">
        <tr>
          <th>订单号</th>
          <th>日期</th>
          <th>出发站</th>
          <th>到达站</th>
          <th>总票价</th>
          <th>列车号</th>
          <th>订单状态</th>
          <th>订单操作</th>
        </tr>
        <?php 
        $user =  $_SESSION['loginname'];
        $date1 = $_POST["date1"];
        $date2 = $_POST["date2"];

        $dbconn = pg_connect("dbname=test user=dbms password=dbms")
        or die('Could not connect: '.pg_last_error());
        
        $query ="select OrderID,
          Orders.StartDate, 
          s1.StationName, 
          s2.StationName, 
          Sum(Orders.Price), 
          Orders.Status, 
          Orders.TrainID,
          s1.StartTime, 
          s2.ArriveTime, 
          Orders.SeatType, 
          Orders.price,
          Orders.StartStationNum,
          Orders.EndStationNum,
          Orders.TrainStartDate
        from Orders, Stations as s1, Stations as s2
        where s1.StationID = Orders.StartStationID and
        s2.StationID = Orders.EndStationID and
        s1.TrainID = Orders.TrainID and
        s2.TrainID = Orders.TrainID and
        Orders.Username = '$user' and
        Orders.StartDate >= '$date1' and
        Orders.StartDate <= '$date2'
        group by OrderID, 
          Orders.StartDate, 
          s1.StationName, 
          s2.StationName, 
          Orders.Status, 
          Orders.TrainID,
          s1.StartTime, 
          s2.ArriveTime, 
          Orders.SeatType, 
          Orders.price,
          Orders.StartStationNum,
          Orders.EndStationNum,
          Orders.TrainStartDate       
        order by OrderID;";
        $result = pg_query($query) or die('Query failed: '.pg_last_error());
        
        $last_orderid = "0";//循环中上一条订单号
        $last_price = 0;
        $buf = "";
        while ($line = pg_fetch_array($result, null, PGSQL_BOTH)) {
          if ($last_orderid!=$line[0] and $last_orderid!='0'){
            $buf .= "<td colspan='8' height= 1px bgcolor='#87CEFA'></td>";
          }
          $buf .= "<tr>";
          $buf .= "<td><a href='req7-detail.php?orderid=".$line[0]
          ."&startdate=" .$line[1]
          ."&starttime=".$line[7]
          ."&arrivetime=".$line[8]
          ."&seattype=" .$line[9]
          ."&trainid=".$line[6]
          ."&price=".$line[10]
          ."&date1=".$date1
          ."&date2=". $date2
          ."&station1=". $line[2]
          ."&station2=". $line[3]
          ."'>".$line[0]
          ."</a></td>";
          for ($x=1; $x < 5; $x++) {
            $buf .= "<td>$line[$x]</td>";
          }
          $buf .="<td>$line[6]</td>";
          switch ($line[5]) 
          {
            case "0":$buf .= "<td>已经取消</td><td>无</td>";break;
            case "1":$buf .= "<td>付款成功</td><td><a href='req7-cancel.php"
                            ."?orderid=".$line[0]
                            ."&trainid=".$line[6]
                            ."&startdate=".$line[1]
                            ."&beginnum=".$line[11]
                            ."&endnum=".$line[12]
                            ."&seattype=".$line[9]
                            ."&realstartdate=".$line[13]
                            ."'><button type='button'>取消订单</button></a></td>";break;
          }

          if ($line[0]!=$last_orderid) {
            $buf .= "</tr>";
          }
          else {
            $buf .= "</tr>";
            $buf .= "<tr><td colspan='8'>换乘合计: $line[4] + $last_price</td></tr>\n";
          }
          $last_orderid = $line[0];
          $last_price = $line[4];
        }
        pg_free_result($result);
        pg_close($dbconn);
        echo $buf;
        ?>
    </table><br>
  </section>
  <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
</div>
</body>
</html>
