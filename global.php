<?php 
	require_once 'vice_global_2.php';

	echo "<pre>";

	// echo ": ";
	// print_r();
	// echo "<br/><br/>";
	// echo ": ";
	// print_r();
	// echo "<br/><br/>";
	// echo ": ";
	// print_r();
	// echo "<br/><br/><br/>";
	// echo ": ";
	// print_r();
	echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Google Analytics - Global</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
 	<!-- data type -->
 	<p>Date Range: <?php echo $start_date . " - " . $end_date; ?> </p>
 	<p><?php echo $general->profileInfo['profileName']; ?></p>
 	<p><?php echo $general->profileInfo['tableId']; ?></p>
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

		<!-- TABLET -->
		<tr>
			<td><strong>Tablet</strong></td>
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
			<td><strong>Mobile</strong></td>
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
			<td>
				<?php 
					if(isset($general->totalsForAllResults['ga:users']) && !empty($general->totalsForAllResults['ga:users'])) {
						echo $general->totalsForAllResults['ga:users'];
					} else {
						echo "unique visitors/users";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- total visits/sessions -->
			<td>
				<?php 
					if(isset($general->totalsForAllResults['ga:sessions']) && !empty($general->totalsForAllResults['ga:sessions'])) {
						echo $general->totalsForAllResults['ga:sessions'];
					} else {
						echo "total sessions";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- total pageviews -->
			<td>
				<?php 
					if(isset($general->totalsForAllResults['ga:pageviews']) && !empty($general->totalsForAllResults['ga:pageviews'])) {
						echo $general->totalsForAllResults['ga:pageviews'];
					} else {
						echo "total pageviews";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- time on site -->
			<td>
				<?php
					if(isset($general_tos) && !empty($general_tos)) {
						echo $general_tos;
					} else {
						echo "time on site";
					}
		 		?>
			 </td>
		</tr>

		<!-- DESKTOP -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- pageviews -->
			<td>
				<?php
					if(isset($desktop_pageviews) && !empty($desktop_pageviews)) {
						echo $desktop_pageviews; 
					} else {
						echo "us desktop pageviews";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- visits/sessions -->
			<td>
				<?php
					if(isset($desktop_sessions) && !empty($desktop_sessions)) {
						echo $desktop_sessions; 
					} else {
						echo "us desktop sessions";
					}
				 ?>
			</td>
		</tr>
		<tr>
			<!-- time on site (avg session duration) -->
			<td>
				<?php
					if(isset($desktop_avg_session_dur) && !empty($desktop_avg_session_dur)) {
						echo $desktop_avg_session_dur; 
					} else {
						echo "us desktop time on site";
					}
				 ?>
			</td>
		</tr>

		<!-- Tablet -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- pageviews -->
			<td>
				<?php
					if(isset($tablet_pageviews) && !empty($tablet_pageviews)) {
						echo $tablet_pageviews; 
					} else {
						echo "tablet pageviews";
					}

				?>
			</td>
		</tr>
		<tr>
			<!-- visits/sessions -->
			<td>
				<?php
					if(isset($tablet_sessions) && !empty($tablet_sessions)) {
						echo $tablet_sessions; 
					} else {
						echo "tablet sessions";
					}

				?>
			</td>
		</tr>
		<tr>
			<!-- time on site -->

			<td>
				<?php
					if(isset($tablet_avg_session_dur) && !empty($tablet_avg_session_dur)) {
						echo $tablet_avg_session_dur;
					} else {
						echo "tablet time on site";
					}
				?>
			</td>
		</tr>
		<!-- MOBILE -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- pageviews -->
			<td>
				<?php
					if(isset($mobile_pageviews) && !empty($mobile_pageviews)) {
					echo $mobile_pageviews; 
					} else {
						echo "mobile pageviews";
					}

				?>
			</td>
		</tr>
		<tr>
			<!-- visits/sessions -->
			<td>
				<?php
					if(isset($mobile_sessions) && !empty($mobile_sessions)) {
					echo $mobile_sessions; 
					} else {
						echo "mobile sessions";
					}

				?>
			</td>
		</tr>
		<tr>
			<!-- time on site -->

			<td>
				<?php
					if(isset($mobile_avg_session_dur) && !empty($mobile_avg_session_dur)) {
						echo $mobile_avg_session_dur;
					} else {
						echo "mobile time on site";
					}
				?>
			</td>
		</tr>

		<!-- ENGAGEMENT -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- bounce rate -->
			<td>
				<?php
					if(isset($us_general_bounce) && !empty($us_general_bounce)) {
						echo $us_general_bounce; 
					} else {
						echo "bounce rate";
					}
				?>
			</td> 
		</tr>
		<tr>
			<!-- new users percent -->
			<td>
				<?php
					if(isset($new_visitor_session_percent) && !empty($new_visitor_session_percent)) {
						echo $new_visitor_session_percent; 
					} else {
						echo "percent new users";
					}
			
				?>
			</td>
		</tr>
		<tr>
			<!-- return users percent -->
			<td>
				<?php
					if(isset($returning_visitor_session_percent) && !empty($returning_visitor_session_percent)) {
						echo $returning_visitor_session_percent; 
					} else {
						echo "percent returning users";
					}
			
				?>
			</td>
		</tr>
		<tr>
			<!-- sessions by a user who has visited 26 times or more (raw number) -->
			<td>
				<?php
					if(isset($us_sessions_over_25) && !empty($us_sessions_over_25)) {
						echo $us_sessions_over_25; 
					} else {
						echo "sessions by user visiting 26 times or more";
					}
			
				?>
			</td>

		</tr>
		<tr>
			<!-- sessions by a user who has visited 26 times or more % -->
			<td>
				<?php
					if(isset($us_sessions_over_25_percent) && !empty($us_sessions_over_25_percent)) {
						echo $us_sessions_over_25_percent; 
					} else {
						echo "% sessions by user visiting 26 times or more";
					}
			
				?>
		</tr>
		<tr>
			<!-- visits/sessions under 60 seconds -->
			<td>
				<?php
					if(isset($us_under_60s_percent) && !empty($us_under_60s_percent)) {
						echo $us_under_60s_percent; 
					} else {
						echo "visits/sessions under 60 seconds";
					}
			
				?>
			</td>
		</tr>
		<tr>
			<!-- visits/sessions greater than or equal to 60 seconds -->
			<td>
				<?php
					if(isset($us_greater_equal_60s_percent) && !empty($us_greater_equal_60s_percent)) {
						echo $us_greater_equal_60s_percent; 
					} else {
						echo "visits/sessions over 60 seconds";
					}
				?>
			</td>
		</tr>

		<!-- Traffic Sources -->
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!-- direct -->
			<td>
				<?php
					if(isset($us_traffic_source_direct_percent) && !empty($us_traffic_source_direct_percent)) {
						echo $us_traffic_source_direct_percent; 
					} else {
						echo "direct traffic";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- paid -->
			<td>
				<?php
					if(isset($us_traffic_source_paid_percent) && !empty($us_traffic_source_paid_percent)) {
						echo $us_traffic_source_paid_percent; 
					} else {
						echo "paid traffic";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- search -->
			<td>
				<?php
					if(isset($us_traffic_source_search_percent) && !empty($us_traffic_source_search_percent)) {
						echo $us_traffic_source_search_percent; 
					} else {
						echo "search traffic";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- social raw number -->
			<td>
				<?php
					if(isset($social_totals) && !empty($social_totals)) {
						echo $social_totals; 
					} else {
						echo "social totals";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- social -->
			<td>
				<?php
					if(isset($social_percent) && !empty($social_percent)) {
						echo $social_percent; 
					} else {
						echo "social percent";
					}
				?>
			</td>
		</tr>
		<tr>
			<!-- VICE.com, m.vice.com -->
			<td>
				<?php 
					if(isset($us_traffic_source_vice_percent) && !empty($us_traffic_source_vice_percent)) {
						echo $us_traffic_source_vice_percent;
					} else {
						echo "vice";
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
						echo "noisey";
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
						echo "tcp";
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
						echo "motherboard";
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
						echo "fightland";
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
						echo "Thump";
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
						echo "i-D";
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
						echo "sports";
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
						echo "news";
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
						echo "munchies";
					}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php 
					if(isset($other_percent) && !empty($other_percent)) {
						echo $other_percent;
					} else {
						echo "percent other";
					}
				?>
			</td>
		</tr>

	</table> 	
 </body>
 </html>