<?php session_start();?>
<!doctype html>
<html lang="en-US">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Light Theme</title>
	<link href="css/singlePageTemplate.css" rel="stylesheet" type="text/css">
	<script>
		var __adobewebfontsappname__ = "dreamweaver"
	</script>
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
				<form action="req4.php" method="post">
					按车次搜索<br>
					<input type="text" name="trainid" placeholder="请在此输入车次，例如G1"><br>
					<input type="date" name="date" value="2018-11-23"><br>
					<button type="submit">确认</button>
				</form>
				<form action="req5.php" method="post">
					按城市搜索<br>
					<input type="text" name="startcity" placeholder="请在此输入出发城市"><br>
					<input type="date" value="2018/11/23" name="date" placeholder="请在此输入出发日期"><br>
					<input type="text" name="endcity" placeholder="请在此输入到达城市"><br>
					<input type="time" name="time" value="06:00:00"><br>
					<button type="submit">确认</button>
				</form>
			</div>

			<h2 class="parallax">查询4结果<?php
              echo ": ".$_POST[ "trainid" ]." & ";
              echo$_POST[ "date" ];
            ?></h2>
			<table class="gridtable">
				<tr>
					<th>车名</th>
					<th>站名</th>
					<th>到达时间</th>
					<th>发车时间</th>
					<th>硬座</th>
					<th>软座</th>
					<th>硬卧上</th>
					<th>硬卧中</th>
					<th>硬卧下</th>
					<th>软卧上</th>
					<th>软卧下</th>
				</tr>
				<?php
				$dbconn = pg_connect( "dbname=test user=dbms password=dbms" )
				or die( 'Connect failed: ' . pg_last_error() );
				$trainid = $_POST[ "trainid" ];
				$date = $_POST[ "date" ];
				$query = "
(
select  ts.StationNum, ts.StationName, ts.ArriveTime, ts.StartTime, 
    ts.YZPrice, (ast.YZCount) as YZCount, 
    ts.RZPrice, (ast.RZCount) as RZCount, 
    ts.YWSPrice, (ast.YWSCount) as YWSCount, 
    ts.YWZPrice, (ast.YWZCount) as YWZCount, 
    ts.YWXPrice, (ast.YWXCount) as YWXCount,
    ts.RWSPrice, (ast.RWSCount) as RWSCount, 
    ts.RWXPrice, (ast.RWXCount) as RWXCount, 
    tl.YZflag, tl.RZflag, tl.YWSflag, tl.YWZflag, tl.YWXflag, tl.RWSflag, tl.RWXflag, 
    ts.Forbid, ts.StationID, ts.TrainID
from Stations as ts, SeatCount as ast , Trains as tl
where ( ts.TrainID = '$trainid' and
    ast.TrainID = '$trainid' and
    tl.TrainID = '$trainid' and 
    ast.StartDate = '$date' and
    (ast.StationNum = 1 and 
    ts.StationNum = 1)
    )
order by ts.StationNum
)
union
(
select  ts.StationNum, ts.StationName, ts.ArriveTime, ts.StartTime, 
    ts.YZPrice, min(ast.YZCount) as YZCount, 
    ts.RZPrice, min(ast.RZCount) as RZCount, 
    ts.YWSPrice, min(ast.YWSCount) as YWSCount, 
    ts.YWZPrice, min(ast.YWZCount) as YWZCount, 
    ts.YWXPrice, min(ast.YWXCount) as YWXCount,
    ts.RWSPrice, min(ast.RWSCount) as RWSCount, 
    ts.RWXPrice, min(ast.RWXCount) as RWXCount, 
    tl.YZflag, tl.RZflag, tl.YWSflag, tl.YWZflag, tl.YWXflag, tl.RWSflag, tl.RWXflag, 
    ts.Forbid, ts.StationID, ts.TrainID
from Stations as ts, SeatCount as ast , Trains as tl
where ( ts.TrainID = '$trainid' and
    ast.TrainID = '$trainid' and
    tl.TrainID = '$trainid' and 
    ast.StartDate = '$date' and
    ((ast.StationNum <= ts.StationNum and
    ast.StationNum > 1)
    ))
group by ts.StationNum, ts.StationName, ts.ArriveTime, ts.StartTime,
     ts.YZPrice, ts.RZPrice, ts.YWSPrice, ts.YWZPrice, ts.YWXPrice,
     ts.RWSPrice, ts.RWXPrice, 
     tl.YZflag, tl.RZflag, tl.YWSflag, tl.YWZflag, tl.YWXflag, tl.RWSflag, tl.RWXflag,
     ts.Forbid,ts.StationID, ts.TrainID
order by ts.StationNum
);
";
				$result = pg_query( $query )or die( 'Query failed: ' . pg_last_error() );
				$startstation = pg_result( $result, 0, 1 );//从第一站开始订的
				$starttime = pg_result( $result, 0, 3 );
				$beginoutid = pg_result( $result, 0, 26 );

				$buf = ""; //要echo的html一行
				$count = 1;

				while ( $line = pg_fetch_array( $result, null, PGSQL_BOTH ) ) {
					$buf .=  "<tr>";
					$buf .=  "<td>" . $line[ 27 ] . "</td>";
					$buf .=  "<td>" . $line[ 1 ] . "</td>"; //站名
					if ( $count == 1 ) { //第一站
						$buf .=  "<td>" . "-" . "</td>";
						$buf .=  "<td>" . $line[ 3 ] . "</td>";
					} else if ( $line[ 2 ] != $line[ 3 ] ) {
						$buf .=  "<td>" . $line[ 2 ] . "</td>";
						$buf .=  "<td>" . $line[ 3 ] . "</td>";
					} else {
						$buf .=  "<td>" . $line[ 2 ] . "</td>";
						$buf .=  "<td>" . "-" . "</td>";
					}
					for ( $x = 0; $x < 7; $x++ ) {
						if ( $line[ $x *2 + 4 ] != "0.0" ) {
							if ( $line[ $x * 2 + 5 ] == "0" ) //余座为0没有连接
								$buf .=  "<td>" . $line[ $x * 2 + 5 ] . "</td>";
							else {
								$buf .=  "<td>" . "<a href=req6.php"
                                    . "?trainid=" . $trainid
                                    . "&ifchange=". "0"
                                    . "&startdate=" . $date
									. "&seattype=" . $x

									. "&startstation=" . $startstation
									. "&arrivestation=" . $line[ 1 ]
									. "&starttime=" . $starttime
									. "&arrivetime=" . $line[ 3 ]

									. "&price=" . $line[ $x * 2 + 4 ]
									. "&beginnum=" . "1"
                                    . "&endnum=" . $count
									. "&beginoutid=" . $beginoutid
									. "&endoutid=" . $line[ 26 ]
									. "&realstartdate=" . $date
									. ">" . $line[ $x * 2 + 5 ]
									. "</a>";
								$buf .= "/￥".$line[$x*2 + 4]."</td>";
							}
						} else {
							$buf .=  "<td>" . "-" . "</td>";
						}
					}
					$buf .=  "</tr>";
					$count++;
				}
				pg_free_result( $result );
				pg_close( $dbconn );
				echo $buf;
				?>
			</table>
      <br>
		</section>
		<div class="copyright">&copy;2018 - <strong>JYR-JZY-CYY</strong>
		</div>
	</div>
</body>

</html>