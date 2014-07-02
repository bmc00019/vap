<?php
require_once 'vice_global.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>VICE Global Google Analytics</title>
</head>
<body>
	<h1>VICE.com</h1>
	<h2>June</h2>
	<h3>Range: <?php echo $start_date; ?> to <?php echo $end_date; ?></h2>
	<table>
		<tr>
			<td><strong>General</strong></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Unique Visitors</td>
			<td><?php echo $vg_general_data[0]; ?></td>
			<td></td>
		</tr>
		<tr>
			<td>Total Visits (Sessions)</td>
			<td><?php echo $vg_general_data[1]; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>Total Pageviews</td>
			<td><?php echo $vg_general_data[2]; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>Time on Site (decimal)</td>
			<td><?php echo $vice_global_avg_sess_dur ?></td>
			<td></td>
		</tr>	
		<tr>
			<td><br/></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td><strong>Desktop</strong></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td>Visits (Sessions)</td>
			<td><?php echo $vg_desktop_sessions; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>Pageviews</td>
			<td><?php echo $vg_desktop_pageviews; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>Time on Site (decimal)</td>
			<td><?php echo $vg_desktop_avg_sess_dur; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td><br/></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td><strong>Mobile</strong></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td>Visits (Sessions)</td>
			<td><?php echo $vice_global_mobile_tablet_data[0]; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>Pageviews</td>
			<td><?php echo $vice_global_mobile_tablet_data[1]; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>Time on site (decimal)</td>
			<td><?php echo $vice_mobile_tab_avg_sess_dur; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td><br/></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td><strong>Engagement</strong></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td>Bounce Rate %</td>
			<td><?php echo $vg_bounce_rate; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% New Users </td>
			<td><?php echo $vg_new_users; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Returning Users</td>
			<td><?php echo $vg_return_users; ?></td>
			<td></td>
		</tr>
		<tr>
			<td>Raw number of session by a user who has visited 26 times or more</td>
			<td><?php echo $vice_global_user_count->totalsForAllResults['ga:sessions']; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% of sessions by return users who have visited 26 times or more</td>
			<td><?php echo $sessions_users_over_26_percent; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Visits < 60 seconds</td>
			<td><?php echo $sessions_under_60_seconds; ?></td>
			<td></td>
		</tr>
		<tr>
			<td>% Visits > 60 seconds</td>
			<td><?php echo $sessions_over_60_seconds; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td><br/></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td><strong>Traffic Source</strong></td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Direct</td>
			<td><?php echo $direct_traffic_percentage; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Paid</td>
			<td><?php echo $paid_traffic_percentage; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Search</td>
			<td><?php echo $search_traffic_percentage; ?></td>
			<td></td>
		</tr>
		<tr>
			<td>% Facebook & Twitter raw sessions</td>
			<td><?php echo $social_traffic_source_raw; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Facebook & Twitter</td>
			<td><?php echo $social_traffic_percent; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Noisey</td>
			<td><?php echo $noisey_traffic_source_percentage; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Creators Project</td>
			<td><?php echo $thecreatorsproject_traffic_source_percentage; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Motherboard</td>
			<td><?php echo $motherboard_traffic_source_percentage; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Fightland</td>
			<td><?php echo $fightland_traffic_source_percentage; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Thump</td>
			<td><?php echo $thump_traffic_source_percentage; ?></td>
			<td></td>
		</tr>	
		<tr>
			<td>% Other</td>
			<td></td>
			<td></td>
		</tr>
		<!-- <tr>
			<td>News</td>
			<td></td>
			<td></td>
		</tr>	
		<tr>
			<td>Munchies</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Sports</td>
			<td></td>
			<td></td>
		</tr> -->
	</table>
</body>
</html>