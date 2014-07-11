<?php 
	require_once 'vice_us.php';
	echo "<pre>";
	// print_r($us_traffic_source_twitter);
	echo "<br/>";
	echo $fb;
	echo "<br/>";
	echo $tw;
	echo "<br/>";
	echo $rd;
	echo "<br/>";
	echo $yt;
	echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Google Analytics - US only</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
 	<!-- data type -->
 	<p>Date Range: </p>
	<table class="data-type">
		<!-- GENERAL -->
		<tr>
			<td><strong>General</strong></td>
		</tr>
		<tr>
			<td>Unique Visitors</td>
		</tr>
		<tr>
			<td>Total Visits (Sessions)</td>
		</tr>
		<tr>
			<td>Total Pageviews</td>
		</tr>
		<tr>
			<td>Time on Site (decimal)</td>
		</tr>

		<!-- DESKTOP -->
		<tr>
			<td><strong>Desktop</strong></td>
		</tr>
		<tr>
			<td>Pageviews</td>
		</tr>
		<tr>
			<td>Visits (Sessions)</td>
		</tr>
		<tr>
			<td>Time on Site (Decimal)</td>
		</tr>

		<!-- MOBILE -->
		<tr>
			<td><strong>Mobile & Tablet</strong></td>
		</tr>
		<tr>
			<td>Pageviews</td>
		</tr>
		<tr>
			<td>Visits (Sessions)</td>
		</tr>
		<tr>
			<td>Time on Site (Decimal)</td>
		</tr>

		<!-- ENGAGEMENT -->
		<tr>
			<td><strong>Engagement</strong></td>
		</tr>
		<tr>
			<td>Bounce Rate %</td>
		</tr>
		<tr>
			<td>New Users %</td>
		</tr>
		<tr>
			<td>Returning Users %</td>
		</tr>
		<tr>
			<td>Sessions by a user who has visited 26 times or more (raw number)</td>
		</tr>
		<tr>
			<td>Sessions by a user who has visited 26 times or more %</td>
		</tr>
		<tr>
			<td>Visits under 60 seconds %</td>
		</tr>
		<tr>
			<td>Visits over 60 seconds %</td>
		</tr>

		<!-- Traffic Sources -->
		<tr>
			<td><strong>Traffic Sources</strong></td>
		</tr>
		<tr>
			<td>Direct %</td>
		</tr>
		<tr>
			<td>Paid %</td>
		</tr>
		<tr>
			<td>Search</td>
		</tr>
		<tr>
			<td>Social (raw number)</td>
		</tr>
		<tr>
			<td>Social %</td>
		</tr>
		<tr>
			<td>VICE.com</td>
		</tr>
		<tr>
			<td>Noisey %</td>
		</tr>
		<tr>
			<td>TCP %</td>
		</tr>
		<tr>
			<td>Motherboard %</td>
		</tr>
		<tr>
			<td>Fightland %</td>
		</tr>
		<tr>
			<td>Thump %</td>
		</tr>
		<tr>
			<td>i-D %</td>
		</tr>
		<tr>
			<td>Sports %</td>
		</tr>
		<tr>
			<td>News %</td>
		</tr>
		<tr>
			<td>Munchies %</td>
		</tr>
		<tr>
			<td>Other %</td>
		</tr>

	</table>
	<!-- DATA -->
	<table class="data">
		<!-- GENERAL -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- unique visitors/users -->
			<td><?php echo $general->totalsForAllResults['ga:users']; ?></td>
		</tr>
		<tr>
			<!-- total visits/sessions -->
			<td><?php echo $general->totalsForAllResults['ga:sessions']; ?></td>
		</tr>
		<tr>
			<!-- total pageviews -->
			<td><?php echo $general->totalsForAllResults['ga:pageviews']; ?></td>
		</tr>
		<tr>
			<!-- time on site -->
			<td><?php echo $general_tos; ?></td>
		</tr>

		<!-- DESKTOP -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- pageviews -->
			<td><?php echo $us_desktop_pageviews; ?></td>
		</tr>
		<tr>
			<!-- visits/sessions -->
			<td><?php echo $us_desktop_sessions; ?></td>
		</tr>
		<tr>
			<!-- time on site (avg session duration) -->
			<td><?php echo $us_desktop_tos; ?></td>
		</tr>

		<!-- MOBILE -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- pageviews -->
			<td><?php echo $us_mobile_tablet->totalsForAllResults['ga:pageviews']; ?></td>
		</tr>
		<tr>
			<!-- visits/sessions -->
			<td><?php echo $us_mobile_tablet->totalsForAllResults['ga:sessions']; ?></td>
		</tr>
		<tr>
			<!-- time on site -->
			<td><?php echo $us_mobile_tablet_tos; ?></td>
		</tr>

		<!-- ENGAGEMENT -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- bounce rate -->
			<td><?php echo $us_general_bounce; ?></td> 
		</tr>
		<tr>
			<!-- new users percent -->
			<td><?php echo $new_users_percent; ?></td>
		</tr>
		<tr>
			<!-- return users percent -->
			<td><?php echo $return_users_percent; ?></td>
		</tr>
		<tr>
			<!-- sessions by a user who has visited 26 times or more (raw number) -->
			<td><?php echo $us_sessions_over_25; ?></td>
		</tr>
		<tr>
			<!-- sessions by a user who has visited 26 times or more % -->
			<td><?php echo $us_sessions_over_25_percent; ?></td>
		</tr>
		<tr>
			<!-- visits/sessions under 60 seconds -->
			<td><?php echo $us_under_60s_percent; ?></td>
		</tr>
		<tr>
			<!-- visits/sessions greater than or equal to 60 seconds -->
			<td><?php echo $us_greater_equal_60s_percent; ?></td>
		</tr>

		<!-- Traffic Sources -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- direct -->
			<td><?php echo $us_traffic_source_direct_percent; ?></td>
		</tr>
		<tr>
			<!-- paid -->
			<td><?php echo $us_traffic_source_paid_percent; ?></td>
		</tr>
		<tr>
			<!-- search -->
			<td><?php echo $us_traffic_source_search_percent; ?></td>
		</tr>
		<tr>
			<!-- social raw number -->
			<td><?php echo $social_totals; ?></td>
		</tr>
		<tr>
			<!-- social -->
			<td><?php echo $social_percent; ?></td>
		</tr>
		<tr>
			<!-- VICE.com, m.vice.com -->
			<td>
				<?php 
					if(isset($us_traffic_source_vice_percent) && !empty($us_traffic_source_vice_percent)) {
						echo $us_traffic_source_vice_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- noisey -->
			<td>
				<?php 
					if(isset($us_traffic_source_noisey_percent) && !empty($us_traffic_source_noisey_percent)) {
						echo $us_traffic_source_noisey_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- TCP -->
			<td>
				<?php 
					if(isset($us_traffic_source_tcp_percent) && !empty($us_traffic_source_tcp_percent)) {
						echo $us_traffic_source_tcp_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- motherboard -->
			<td>
				<?php 
					if(isset($us_traffic_source_mobo_percent) && !empty($us_traffic_source_mobo_percent)) {
						echo $us_traffic_source_mobo_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- Fightland -->
			<td>
				<?php 
					if(isset($us_traffic_source_fightland_percent) && !empty($us_traffic_source_fightland_percent)) {
						echo $us_traffic_source_fightland_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- thump -->
			<td>
				<?php 
					if(isset($us_traffic_source_thump_percent) && !empty($us_traffic_source_thump_percent)) {
						echo $us_traffic_source_thump_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- i-D -->
			<td>
				<?php 
					if(isset($us_traffic_source_iD_percent) && !empty($us_traffic_source_iD_percent)) {
						echo $us_traffic_source_iD_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- sports -->
			<td>
				<?php 
					if(isset($us_traffic_source_sports_percent) && !empty($us_traffic_source_sports_percent)) {
						echo $us_traffic_source_sports_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- News -->
			<td>
				<?php 
					if(isset($us_traffic_source_news_percent) && !empty($us_traffic_source_news_percent)) {
						echo $us_traffic_source_news_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- munchies -->
			<td>
				<?php 
					if(isset($us_traffic_source_munchies_percent) && !empty($us_traffic_source_munchies_percent)) {
						echo $us_traffic_source_munchies_percent;
					} else {
						echo "nada";
					}
				?>
			</td>
		</tr>
		<tr>
			<td>Other %</td>
		</tr>

	</table> 	
 </body>
 </html>