<?
//standard header for each included file
checksession(); //check if we are called by a valid session
/*if (!checkrights("Administrator,ViewTimesheet")) { //"Administrator,ViewOverview"
	global $LANG;
	echo("<h2>${LANG['NORIGHTS']}</h2>");
	return;
}*/
$MENUITEM=5; //Panel ID in menu. Used in hyperlinks
$PANELNAME='Settings'; //Panel name (optional)
//standard header ends here

include('../config/config.php'); //wczytaj nastawy konfiguracji
?>

<div class="tytul">
Settings
</div>

<h2>Server Statistics</h2>
<b>Server time:</b>
<?
	echo('<a href="#" title="');
	echo(date('d.m.Y G:i:s'));
	echo('">');
	echo(date('d.m.Y G:i'));
	echo('</a>');

?>
<br>
<b>Server uptime: </b>
<?
	$uptime=exec('uptime');
	$up2=explode('up',$uptime);
	$tmp=explode(',',$up2[1]);
	echo('<a href="#" title="');
	echo($uptime);
	echo('">');
	echo($tmp[0]);
	echo('</a><br>');

	echo('<b>Maximum session idle time:</b> ');
	if ($LM_SESSION < 60) {
		printf("%d seconds<br>",$LM_SESSION);
	} else {
		printf("%d minutes<br>",$LM_SESSION/60);
	}

if (checkrights("Administrator,ViewAPIStats")) { 
	
	?>
	<br>
	<h2>EVE API Statistics</h2>
	<?php
		include("checkpoller.php");
                
                
	?>
<script type="text/javascript" src="chart.js/Chart.min.js"></script>
                <script type="text/javascript">
                            pie_options = {
                                    //Boolean - Whether we should show a stroke on each segment
                                    segmentShowStroke : true,
                                    //String - The colour of each segment stroke
                                    segmentStrokeColor : "#fff",
                                    //Number - The width of each segment stroke
                                    segmentStrokeWidth : 2,
                                    //Boolean - Whether we should animate the chart	
                                    animation : true,
                                    //Number - Amount of animation steps
                                    animationSteps : 100,
                                    //String - Animation easing effect
                                    animationEasing : "easeOutBounce",
                                    //Boolean - Whether we animate the rotation of the Pie
                                    animateRotate : true,
                                    //Boolean - Whether we animate scaling the Pie from the centre
                                    animateScale : false,
                                    //Function - Will fire on animation completion.
                                    onAnimationComplete : null
                            }
                           bar_options = {
                                    //Boolean - If we show the scale above the chart data			
                                    scaleOverlay : false,
                                    //Boolean - If we want to override with a hard coded scale
                                    scaleOverride : false,
                                    //** Required if scaleOverride is true **
                                    //Number - The number of steps in a hard coded scale
                                    scaleSteps : null,
                                    //Number - The value jump in the hard coded scale
                                    scaleStepWidth : null,
                                    //Number - The scale starting value
                                    scaleStartValue : null,
                                    //String - Colour of the scale line	
                                    scaleLineColor : "rgba(255,255,255,.1)",
                                    //Number - Pixel width of the scale line	
                                    scaleLineWidth : 1,
                                    //Boolean - Whether to show labels on the scale	
                                    scaleShowLabels : true,
                                    //Interpolated JS string - can access value
                                    scaleLabel : "<%=value%>",
                                    //String - Scale label font declaration for the scale label
                                    scaleFontFamily : "'Arial'",
                                    //Number - Scale label font size in pixels	
                                    scaleFontSize : 12,
                                    //String - Scale label font weight style	
                                    scaleFontStyle : "normal",
                                    //String - Scale label font colour	
                                    scaleFontColor : "#aaa",	
                                    ///Boolean - Whether grid lines are shown across the chart
                                    scaleShowGridLines : true,
                                    //String - Colour of the grid lines
                                    scaleGridLineColor : "rgba(255,255,255,.1)",
                                    //Number - Width of the grid lines
                                    scaleGridLineWidth : 1,	
                                    //Boolean - If there is a stroke on each bar	
                                    barShowStroke : true,
                                    //Number - Pixel width of the bar stroke	
                                    barStrokeWidth : 2,
                                    //Number - Spacing between each of the X value sets
                                    barValueSpacing : 5,
                                    //Number - Spacing between data sets within X values
                                    barDatasetSpacing : 1,
                                    //Boolean - Whether to animate the chart
                                    animation : true,
                                    //Number - Number of animation steps
                                    animationSteps : 60,
                                    //String - Animation easing effect
                                    animationEasing : "easeOutQuart",
                                    //Function - Fires when the animation is complete
                                    onAnimationComplete : null
                            }
                    </script>
                    <?php
//GRAPHING		
                    //getting data
                    $samples=30;
                    $period=15;
                    $cycles=''; $times='';
                    for($i=$samples-1; $i>=0; $i--) {
                        $cycles.=''.$i*$period.',';
                    }    
                    $stats=db_asocquery("SELECT * FROM (SELECT * 
                    FROM `apipollerstats` 
                    ORDER BY `statDateTime` DESC 
                    LIMIT 0 , $samples) AS a ORDER BY `statDateTime`;");
                    //ready to display
                        foreach($stats as $row) {
                            $times.=$row['pollerSeconds'].',';
                        }
                    //cut trailing commas
                        $cycles=rtrim($cycles,',');
                        $times=rtrim($times,',');
                    //display
                        ?>
<h3>Poller statistics [seconds]</h3>
    <div>
                        <canvas id="statsCanvas" width="600" height="200"></canvas>
                        <script type="text/javascript">
                            var data = {
                                labels : [ <?php echo($cycles); ?> ],
                                datasets : [
                                       {
                                           fillColor : "rgba(151,187,205,0.5)",
                                           strokeColor : "rgba(151,187,205,1.0)",
                                           data : [ <?php echo($times); ?> ]
                                       }
                                ]
                            }
                            
                            var ctx= document.getElementById("statsCanvas").getContext("2d");
                            var apiChart = new Chart(ctx).Line(data,bar_options);
                            
                        </script>
    </div>

	<table class="lmframework">
	<tr><th width="64">
		<b>keyID</b>
	  </th><th width="128">
		<b>Feed</b>
	   </th><th width="128">
		<b>Date</b>
	   </th><th width="64">
		<b>errorCode</b>
	   </td><th width="64">
		<b>errorCount</b>
	   </th><th width="350">
		<b>errorMessage</b>
	   </th>
	   </tr>
	<?php
	
	function hrefedit($nr) {
				echo('<a href="index.php?id=5&id2=8&nr=');
				echo($nr);
				echo('" title="Click to reset this API feed status (it will be polled in the next cycle)">');
	}
	
	$data=db_asocquery("SELECT * FROM apistatus ORDER BY keyID,fileName;");
	
	foreach($data as $row) {
		echo('<tr><td class="tab">');
				hrefedit($row['errorID']);
				echo($row['keyID']);
				echo('</a></td><td>');
				hrefedit($row['errorID']);
				echo($row['fileName']);
				echo('</a></td><td>');
				hrefedit($row['errorID']);
				echo($row['date']);
				if ($row['errorCode'] == 0) $color="#00a000";
				if ($row['errorCode'] >= 100 && $row['errorCode'] < 200 ) $color="#a04000";
				if ($row['errorCode'] >= 200 && $row['errorCode'] < 500 ) $color="#a00000";
				if ($row['errorCode'] >= 500) $color="#a0a000";
				echo('</a></td><td style="text-align: center; background: '.$color.'">');
				hrefedit($row['errorID']);
				echo($row['errorCode']);
                                echo('</a></td><td style="text-align: center;">');
				hrefedit($row['errorID']);
				echo($row['errorCount']);
				echo('</a></td><td>');
				hrefedit($row['errorID']);
				echo($row['errorMessage']);
				echo('</a></td>');
			echo('</tr>');
	}
	echo("</table>");
}
?>
</div>