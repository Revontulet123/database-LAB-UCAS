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
    <h2 class="parallax">查询</h2>
    <div class="parallax_description">
      <form action= "req4.php" method="post">
        按车次搜索
        <input type="text" name="trainid" placeholder="请在此输入车次，例如G1">
        <input type="date" value="2018-11-23" name="date">
        <button type="submit">确认</button>
      </form>
      <form action= "req5.php" method="post">
        按城市搜索
        <input type="text" name="startcity" placeholder="请在此输入出发城市">
        <input type="date" value="2018-11-23" name="date" placeholder="请在此输入出发日期">
        <input type="text" name="endcity" placeholder="请在此输入到达城市">
        <input type="time" value="06:00:00" name="time" placeholder="请在此输入给定的出发时间">
        <button type="submit">确认</button>
      </form>
    </div>
    <h2 class="parallax">查询5直达结果<?php
      echo ": ".$_POST[ "startcity" ]." & ";
      echo $_POST[ "date" ]." & ";
      echo $_POST[ "endcity" ]." & ";
      echo $_POST[ "time" ];
    ?></h2>
    <table class="gridtable">
      <tr>
        <th>车名</th>
        <th>出发站</th>
        <th>到达站</th>
        <th>出发时间</th>
        <th>到达时间</th>
        <th>到达日期</th>
        <th>硬座</th>
        <th>软座</th>
        <th>硬卧上</th>
        <th>硬卧中</th>
        <th>硬卧下</th>
        <th>软卧上</th>
        <th>软卧下</th>
      </tr>
      <?php
      $dbconn = pg_connect( "dbname=test user=dbms password=dbms" )or die( 'Connect failed: '.pg_last_error() );
      $begincity = $_POST[ "startcity" ];
      $date = $_POST[ "date" ];
      $destcity = $_POST[ "endcity" ];
      $time = $_POST[ "time" ];
      $query1 ="select  ns.MinPrice, ns.Tripmin, 
      ns.BeginCityName,ns.DestCityName, 
      ns.TrainID, 
      (sc.StartDate + ns.BeginGap) :: date as StartDate, 
      (sc.StartDate + ns.EndGap ):: date as EndDate,
      ns.BeginStationName,ns.DestStationName,
      ns.BeginTime, ns.EndTime, 
      min(sc.YZCount) as YZCount,   min(sc.RZCount) as RZCount, min(sc.YWSCount) as YWSCount,
      min(sc.YWZCount) as YWZCount, min(sc.YWXCount) as YWXCount, min(sc.RWSCount) as RWSCount,
      min(sc.RWXCount) as RWXCount, 
      ns.YZprice,ns.RZprice, ns.YWSprice, ns.YWZprice, ns.YWXprice, 
      ns.RWSprice, ns.RWXprice,
      ns.YZflag, ns.RZflag , ns.YWSflag , ns.YWZflag,
      ns.YWXflag, ns.RWSflag , ns.RWXflag,
      ns.BeginStationID, ns.DestStationID, ns.BeginStationNum, ns.DestStationNum

      into NonstopTicket
      from    Nonstop as ns, SeatCount as sc
      where   (ns.BeginTime - time '$time') > '0 min' and
      (sc.StartDate + ns.BeginGap) = '$date'and
      ns.BeginCityName = '$begincity'and ns.DestCityName = '$destcity' and
      ns.TrainID = sc.TrainID and sc.StationNum > ns.BeginStationNum and 
      sc.StationNum <= ns.DestStationNum                 
      group by 
      ns.Minprice,ns.Tripmin,
      ns.BeginCityName,ns.DestCityName,     
      ns.TrainID, ns.BeginGap,ns.EndGap,
      sc.StartDate, 
      ns.BeginStationName,ns.DestStationName,
      ns.BeginTime, ns.EndTime,
      ns.YZprice,ns.RZprice, ns.YWSprice, ns.YWZprice, ns.YWXprice, ns.RWSprice, ns.RWXprice,
      ns.YZflag, ns.RZflag , ns.YWSflag , ns.YWZflag,ns.YWXflag, ns.RWSflag , ns.RWXflag,
      ns.BeginStationID, ns.DestStationID, ns.BeginStationNum, ns.DestStationNum
      order by MinPrice, Tripmin, BeginTime
      asc limit  10 offset  0;";
      pg_query($query1)or die('Query1 failed: '.pg_last_error());
      $query2 = "select * from NonstopTicket;";
      $result = pg_query($query2) or die('Query2 failed: '.pg_last_error());
      $buf = "";
      while ( $line = pg_fetch_array( $result, null, PGSQL_BOTH ) ){
        $buf .="<tr>";
        $buf .=  "<td>".$line[4]."</td>";
        $buf .=  "<td>".$line[7]."</td>";
        $buf .=  "<td>".$line[8]."</td>";
        $buf .=  "<td>".$line[9]."</td>";
        $buf .=  "<td>".$line[10]."</td>";
        $buf .=  "<td>".$line[6]."</td>";
        for( $x = 0; $x < 7; $x++ ){
          if($line[18+$x]!="0.0"){
            if($line[11+$x]<="0"){
              $buf .=  "<td>0</td>";
            }else{
              $buf .=  "<td>"."<a href=req6.php"
             ."?trainid=".$line[4]
             ."&ifchange=". "0"
             ."&startdate=".$line[5]
             ."&seattype=".$x

             ."&startstation=".$line[7]
             ."&arrivestation=".$line[8]
             ."&starttime=".$line[9]
             ."&arrivetime=".$line[10]

             ."&price=".$line[18+$x]
             ."&beginnum=".$line[34]
             ."&endnum=".$line[35]
             ."&beginoutid=".$line[32]
             ."&endoutid=".$line[33]
             ."&realstartdate=".$line[5]
             .">".$line[11+$x]
             ."</a>";
              $buf .= "/￥".$line[18+$x]."</td>";
            }
          }else{
            $buf .= "<td>"."-"."</td>"; 
          }
        }
        $buf .=  "</tr>";
      }
      echo $buf;
      pg_query("delete from NonstopTicket;")or die('Query delete failed: '.pg_last_error());
      pg_query("drop table NonstopTicket;")or die('Query drop failed: '.pg_last_error());
      pg_free_result($result);
      pg_close($dbconn);
      ?>
    </table>
    <h2 class="parallax">查询5换乘1次结果:</h2>
    <table class="gridtable">
      <tr>
        <th>车名</th>
        <th>出发站</th>
        <th>到达站</th>
        <th>出发时间</th>
        <th>到达时间</th>
        <th>到达日期</th>
        <th>硬座</th>
        <th>软座</th>
        <th>硬卧上</th>
        <th>硬卧中</th>
        <th>硬卧下</th>
        <th>软卧上</th>
        <th>软卧下</th>
        <th>预定</th>
      </tr>
      <?php
      $dbconn = pg_connect( "dbname=test user=dbms password=dbms" )or die( 'Connect failed: '.pg_last_error() );
      $begincity = $_POST[ "startcity" ];
      $date = $_POST[ "date" ];
      $destcity = $_POST[ "endcity" ];
      $time = $_POST[ "time" ];
      $query3= "(select     
        (ns1.MinPrice + ns2.MinPrice )as FinalMinPrice,
        (ns1.Tripmin + ns2.Tripmin) as FinalTripMin,
        ns1.TrainID as Train1ID, 
        (sc1.StartDate + ns1.BeginGap) :: date as Train1StartDate, (sc1.StartDate + ns1.EndGap) :: date as Train1EndDate, 
        ns1.BeginStationName,ns1.BeginCityName, ns1.MinPrice as Train1MinPrice, ns1.Tripmin as Train1TripMin,
        ns1.BeginTime as Train1BeginTime, ns1.EndTime as Train1EndTime,

        ns1.DestStationName as Train1DestStationName, 
        ns1.DestCityName as TransferCityName,

        ns2.TrainID as Train2ID, 
        (sc2.StartDate + ns2.BeginGap):: date as Train2StartDate,(sc2.StartDate + ns2.EndGap):: date as Train2EndDate,
        ns2.BeginStationName as Train2BeginStationName,ns2.MinPrice as Train2MinPrice, ns2.Tripmin as Train2TripMin,
        ns2.DestStationName,  ns2.DestCityName,
        ns2.BeginTime as Train2BeginTime, ns2.EndTime as Train2EndTime,
        
        ns1.YZprice  as Train1YZprice, ns2.YZprice as Train2YZprice, ns1.RZprice as Train1RZprice, ns2.RZprice as Train2RZprice, 
        ns1.YWSprice as Train1YWSprice, ns2.YWSprice as Train2YWSprice, ns1.YWZprice as Train1YWZprice, ns2.YWZprice as  Train2YWZprice, 
        ns1.YWXprice as Train1YWXprice,ns2.YWXprice as Train2YWXprice, ns1.RWSprice as Train1RWSprice,ns2.RWSprice as Train2RWSprice, 
        ns1.RWXprice as Train1RWXprice, ns2.RWXprice as Train2RWXprice,

        min(sc1.YZCount)  as Train1YZCount,  min(sc1.RZCount) as Train1RZCount,
        min(sc1.YWSCount) as Train1YWSCount, min(sc1.YWZCount)  as Train1YWZCount, min(sc1.YWXCount) as Train1YWXCount, 
        min(sc1.RWSCount) as Train1RWSCount, min(sc1.RWXCount) as Train1RWXCount,   

        min(sc2.YZCount)  as  Train2YZCount,  min(sc2.RZCount) as Train2RZCount,
        min(sc2.YWSCount) as Train2YWSCount, min(sc2.YWZCount)  as Train2YWZCount, min(sc2.YWXCount) as Train2YWXCount, 
        min(sc2.RWSCount) as Train2RWSCount, min(sc2.RWXCount) as Train2RWXCount,
        ns1.YZflag as Train1YZflag, ns1.RZflag as Train1RZflag, ns1.YWSflag as Train1YWSflag, ns1.YWZflag as Train1YWZflag,
        ns1.YWXflag as Train1YWXflag ,ns1.RWSflag as Train1RWSflag, ns1.RWXflag as Train1RWXflag,
        ns2.YZflag as Train2YZflag, ns2.RZflag as Train2RZflag, ns2.YWSflag as Train2YWSflag, ns2.YWZflag as Train2YWZflag,
        ns2.YWXflag as Train2YWXflag, ns2.RWSflag as Train2RWSflag, ns2.RWXflag as Train2RWXflag,
        ns1.BeginstationNum as beginnum1,ns1.BeginStationID as beginid1, 
        ns1.DestStationNum as endnum1, ns1.DestStationID as endid1,
        ns2.BeginstationNum as beginnum2, ns2.BeginStationID as beginid2, 
        ns2.DestStationNum as endnum2, ns2.DestStationID as endid2,
        sc1.StartDate  as startdate1, sc2.StartDate as startdate2,
        (ns2.BeginTime - ns1.EndTime) :: time as WaitTime
into TransferTickets
from      Nonstop as ns1, Nonstop as ns2 , SeatCount as sc1, SeatCount sc2
where   
        ns1.BeginCityName = '$begincity'and ns2.DestCityName = '$destcity' and 
        ns1.DestCityName = ns2.BeginCityName and 

        ((  ( (ns1.DestStationID = ns2.BeginStationID  
        and (  ((ns2.BeginTime - ns1.EndTime)> '0 min' and ((ns2.BeginTime - ns1.EndTime) between interval'1 hour' and  interval'4 hour')) 
            ))  or
            (ns1.DestStationID <> ns2.BeginStationID  
        and (  ((ns2.BeginTime - ns1.EndTime)> '0 min' and ((ns2.BeginTime - ns1.EndTime) between interval'2 hour' and  interval'4 hour')) 
        )))and  ( sc2.StartDate + ns2.BeginGap) =(sc1.StartDate + ns1.EndGap) ) 
        or
        (   ((ns1.DestStationID = ns2.BeginStationID  
        and (  ((ns2.BeginTime - ns1.EndTime)< '0 min'and ((ns2.BeginTime - ns1.EndTime+ interval'1 day') between interval'1 hour' and  interval'4 hour'))
            ))  or
            (ns1.DestStationID <>ns2.BeginStationID  
        and (   ((ns2.BeginTime - ns1.EndTime)< '0 min'and ((ns2.BeginTime - ns1.EndTime+ interval'1 day') between interval'2 hour' and  interval'4 hour'))
            ) ))and  ( sc2.StartDate + ns2.BeginGap) =(sc1.StartDate + ns1.EndGap + '1 day')))
        and 
        (ns1.BeginTime - time'$time') >= '0 min' and
        (sc1.StartDate + ns1.BeginGap) = '$date'  and
        ns1.TrainID = sc1.TrainID and sc1.StationNum > ns1.BeginStationNum and sc1.StationNum <= ns1.DestStationNum and
        ns2.TrainID = sc2.TrainID and 
        (sc2.StationNum > ns2.BeginStationNum and sc2.StationNum <= ns2.DestStationNum  ) 

group by
        ns1.BeginCityName,ns1.DestCityName ,ns2.DestCityName,
        ns1.TrainID ,sc1.StartDate, ns1.BeginGap,ns2.BeginGap,ns2.BeginGap,ns1.EndGap,ns2.EndGap,
        ns1.BeginStationName, ns1.DestStationName , Train1MinPrice, Train1TripMin,
        ns1.BeginTime, ns1.EndTime ,

        ns2.TrainID , sc2.StartDate,
        ns2.BeginStationName ,ns2.DestStationName, Train2MinPrice, Train2TripMin,
        ns2.BeginTime , ns2.EndTime,    

        ns1.YZprice ,  ns1.RZprice ,ns1.YWSprice ,ns1.YWZprice ,ns1.YWXprice, ns1.RWSprice,ns1.RWXprice,
        ns2.YZprice,ns2.RZprice ,  ns2.YWSprice ,  ns2.YWZprice, ns2.YWXprice,ns2.RWSprice,  ns2.RWXprice  ,
        Train1YZflag, Train1RZflag, Train1YWSflag, Train1YWZflag,
        Train1YWXflag , Train1RWSflag, Train1RWXflag,
        Train2YZflag, Train2RZflag,  Train2YWSflag, Train2YWZflag,
        Train2YWXflag , Train2RWSflag,  Train2RWXflag,
        beginnum1,beginid1,endnum1,endid1,beginnum2, beginid2, endnum2, endid2,
        startdate1, startdate2,WaitTime
 )
order by FinalMinPrice, FinalTripMin, Train1BeginTime, Train2BeginTime asc;";
      pg_query($query3)or die('Query3 failed: '.pg_last_error());
      $query4 = "select * from TransferTickets limit 10 offset 0;";
      $result = pg_query($query4) or die('Query4 failed: '.pg_last_error());

      $query5 = "select count(WaitTime) from TransferTickets;";
      $sumresult= pg_query($query5) or die('Query5 failed: '.pg_last_error());
      $sum = pg_result($sumresult,0,0);

      $buf = "";
      $buf .="<tr><td colspan='14'>共".$sum."个结果</td></tr>";
      $count = 0;
      while ( $line = pg_fetch_array( $result, null, PGSQL_BOTH ) ){
        $count++;
        $buf .="<tr>";
        $buf .="<td colspan='14'>$count: ".$line[6]." ---> ".$line[12]." ---> ".$line[20]."          换乘经停时间=".$line[75]."</td>";
        //train 1
        $buf .="</tr>";
        $buf .="<tr><form action='req6.php"
             ."?ifchange=". "1"
             ."&city1=".$line[6]
             ."&city2=".$line[12]
             ."&city3=".$line[20]

             ."&trainid1=".$line[2]
             ."&startdate1=".$line[73]
             ."&realstartdate1=".$line[3]
             ."&starttime1=".$line[9]
             ."&arrivetime1=".$line[10]

             ."&yzprice1=".$line[23]
             ."&rzprice1=".$line[25]
             ."&ywsprice1=".$line[27]
             ."&ywzprice1=".$line[29]
             ."&ywxprice1=".$line[31]
             ."&rwsprice1=".$line[33]
             ."&rwxprice1=".$line[35]

             ."&startstation1=".$line[5]
             ."&arrivestation1=".$line[11]

             ."&beginnum1=".$line[65]
             ."&endnum1=".$line[67]
             ."&beginoutid1=".$line[66]
             ."&endoutid1=".$line[68]
             
             ."&trainid2=".$line[13]
             ."&startdate2=".$line[74]
             ."&realstartdate2=".$line[14]
             ."&starttime2=".$line[21]
             ."&arrivetime2=".$line[22]

             ."&yzprice2=".$line[24]
             ."&rzprice2=".$line[26]
             ."&ywsprice2=".$line[28]
             ."&ywzprice2=".$line[30]
             ."&ywxprice2=".$line[32]
             ."&rwsprice2=".$line[34]
             ."&rwxprice2=".$line[36]

             ."&startstation2=".$line[16]
             ."&arrivestation2=".$line[19]

             ."&beginnum2=".$line[69]
             ."&endnum2=".$line[71]
             ."&beginoutid2=".$line[70]
             ."&endoutid2=".$line[72]

             ."' method='post' id='form$count'>";
        //print
        $buf .="<td>".$line[2]."</td>";
        $buf .="<td>".$line[5]."</td>";
        $buf .="<td>".$line[11]."</td>";
        $buf .="<td>".$line[9]."</td>";
        $buf .="<td>".$line[10]."</td>";
        $buf .="<td>".$line[4]."</td>";
        for( $x = 0; $x < 7; $x++ ){
          if($line[23+$x*2]!="0.0"){
            if($line[37+$x]<="0"){
              $buf .=  "<td>".$line[37+$x]."</td>";
            }else{
              $buf .=  "<td><input type='radio' name='seattype1' value='$x' form='form$count'>"
              .$line[37+$x]."/￥".$line[23+$x*2]."</td>";
            }
          }else{
            $buf .= "<td>"."-"."</td>"; 
          }
        }
        $buf .="<td rowspan= '2'><button type='submit'>订票</button></td>";
        $buf .="</tr>";
        //train 2
        $buf .="<tr>";
        $buf .="<td>".$line[13]."</td>";
        $buf .="<td>".$line[11]."</td>";
        $buf .="<td>".$line[19]."</td>";
        $buf .="<td>".$line[21]."</td>";
        $buf .="<td>".$line[22]."</td>";
        $buf .="<td>".$line[15]."</td>";
        for( $x = 0; $x < 7; $x++ ){
          if($line[24+$x*2]!="0.0"){
            if($line[44+$x]<="0"){
              $buf .=  "<td>".$line[44+$x]."</td>";
            }else{
              $buf .=  "<td><input type='radio' name='seattype2' value='$x' form='form$count'>"
              .$line[44+$x]."/￥".$line[24+$x*2]."</td>";
            }
          }else{
            $buf .= "<td>"."-"."</td>"; 
          }
        }
        $buf .="</form></tr>";
      }
      echo $buf;
      pg_query("delete from transfertickets;")or die('Query delete failed: '.pg_last_error());
      pg_query("drop table transfertickets;")or die('Query drop failed: '.pg_last_error());
      pg_free_result($result);
      pg_close($dbconn);
      ?>
    </table>
  </section>
  <div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong></div>
</div>
</body>
</html>
