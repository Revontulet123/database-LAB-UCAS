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
    <header> 
      <h4 class="logo">管理员界面</h4>
      <nav>
        <ul>
          <?php
          echo "<li>欢迎!".$_SESSION['loginname']."</li>"
          ."<li><a href=quit.php>退出</a></li>";
          ?>
        </ul>
      </nav>
    </header>
    <section class="bannerfun">
      <footer>
        <div class="middlediv">
          <h2 class="parallax">总数</h2>
          <table class="gridtable">
            <tr>
              <th>总订单数</th>
              <th>总票价</th>
            </tr>
            <?php
            $dbconn = pg_connect("dbname=test user=dbms password=dbms")or die('Connect failed: ' . pg_last_error());
            $query = "select count(distinct OrderID) from orders where Status = 1;";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            $line = pg_fetch_array($result, null, PGSQL_BOTH);
            $buf = "";
            $buf .="<tr><td>".$line[0]."</td>";
            pg_free_result($result);
            $query = "select sum(Price) from orders where Status = 1;";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            $line = pg_fetch_array($result, null, PGSQL_BOTH);
            $buf .="<td>".$line[0]."</td></tr>";
            echo $buf;
            pg_free_result($result);
            ?>
          </table>
          <h2 class="parallax">最热点车次</h2>
          <table class="gridtable">
            <tr>
              <th>车号</th>
              <th>订单数</th>
            </tr>
            <?php
            $query = "select TrainID, count(*) from orders where Status = 1
            group by TrainID
            order by TrainID, count(*) desc limit 10;";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            $buf = "";
            while ($line = pg_fetch_array($result, null, PGSQL_BOTH)) {
              $buf .= "<tr>";
              $buf .= "<td>".$line[0]."</td>";
              $buf .= "<td>".$line[1]."</td>";
              $buf .= "</tr>";
            }
            echo $buf;
            pg_free_result($result);
            ?>
          </table>
        </div>
        <div class="middlediv">
          <h2 class="parallax">用户列表</h2>
          <table class="gridtable">
            <tr>
              <th class="text-left">真名</th>
              <th class="text-left">身份证</th>
              <th class="text-left">电话</th>
              <th class="text-left">信用卡</th>
              <th class="text-left">用户名</th>
            </tr>
            <?php
            $query = "select * from users;";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            $buf = "";
            while ($line = pg_fetch_array($result, null, PGSQL_BOTH)) {
              $buf .= "<tr>";
              $buf .= "<td><a href=req8-userorder.php?username=".$line[4].">".$line[0]."</a></td>";
              for ($x=1; $x<5; $x++)
                $buf .= "<td>".$line[$x]."</td>";
              $buf .= "</tr>";
            }
            echo $buf;
            pg_free_result($result);
            pg_close($dbconn);
            ?>
          </table>
        </div>
      </footer>
    </section>
    <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
  </div>
</body>
</html>
