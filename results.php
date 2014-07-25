<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'Google/Client.php';
require_once 'Google/Auth/AssertionCredentials.php';
require_once 'Google/Service/Analytics.php';

$key = 'd41b61d6cced78f78ffe059cfde5b6bc5d71f78e-privatekey.p12';

$creds = new Google_Auth_AssertionCredentials(
	'852504663338-b7ilp8vml66b1cebajtr7u53c9tibpve@developer.gserviceaccount.com',

	array('https://www.googleapis.com/auth/analytics.readonly'),

	file_get_contents($key)
);

$client = new Google_Client();
$client->setApplicationName('Vice Analytics Test');
$client->setDeveloperKey('AIzaSyAz2I_ihnZS-UFtTKzr3EMHMzyR59jXXi4');
$client->setAssertionCredentials($creds);

$service = new Google_Service_Analytics($client);

$props = $service->management_webproperties->listManagementWebproperties('~all');
$accounts = $service->management_accounts->listManagementAccounts();
$segments = $service->management_segments->listManagementSegments();
$goals = $service->management_goals->listManagementGoals("~all", "~all", "~all");

// we got the ID off the management profile ID
$profiles = $service->management_profiles->listManagementProfiles("~all", "~all"); 


	// Start, End, Vertical
	$start_date = (isset($_POST['start_date']) && !empty($_POST['start_date'])) ? $_POST['start_date'] : '2014-06-01';
	$end_date = (isset($_POST['end_date']) && !empty($_POST['end_date'])) ? $_POST['end_date'] : '2014-06-30';
	$vertical = (isset($_POST['vertical_choice']) && !empty($_POST['vertical_choice'])) ? $_POST['vertical_choice'] : 'ga:45303215';
	$country = (isset($_POST['country']) && !empty($_POST['country'])) ? $_POST['country'] : '1';

	// Segments
	$paid = 'gaid::-4';
	$organic = 'gaid::-5';
	$search = 'gaid::-6';
	$direct = 'gaid::-7';
	$referral = 'gaid::-8';
	$mobile_and_tablet = 'gaid::-11';
	$tablet = 'gaid::-13';
	$mobile = 'gaid:-14';

/*
	All our metrics
*/
	$general_metrics = 'ga:users,ga:sessions,ga:pageviews,ga:avgSessionDuration,ga:sessionDuration,ga:bounceRate,ga:exits';

/*
	Optional Params
*/
	$general_params = array(
		'dimensions' 	=> (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country' : null,
		'filters'		=> (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

/*
	Begin Queries for Data
*/
	/* GENERAL */
	$general = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$general_params);

	// general - time on site (tos)
	$general_tos = calcAvgSessionDuration($general->totalsForAllResults['ga:avgSessionDuration']);

	$general_bounce = roundToHundredth($general->totalsForAllResults['ga:bounceRate']);


	// User Type
		$user_type_params = array(
			'dimensions' => 'ga:userType',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		); 
		$general_user_type = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$user_type_params);

		$total_users = $general_user_type->totalsForAllResults['ga:users'];
		$user_types = $general_user_type->rows;

		$new_visitors_sessions = $user_types[0][2];
		$returning_visitors_sessions = $user_types[1][2];

		$total_user_sessions = $new_visitors_sessions + $returning_visitors_sessions;
		
		$new_visitor_session_percent = round(($new_visitors_sessions / $total_user_sessions) * 100, 2);
		$returning_visitor_session_percent = 100 - $new_visitor_session_percent;


	// Sessions by return users who have visited 26 times or more
		$user_session_count_params = array(
			'dimensions' => 'ga:userType',
			'segment' => 'dynamic::ga:sessionCount>=26',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$sessions_over_25_data = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$user_session_count_params);
		$sessions_over_25 = $sessions_over_25_data->totalsForAllResults['ga:sessions'];
		$sessions_over_25_percent = round(($sessions_over_25 / $general->totalsForAllResults['ga:sessions']) * 100, 2);

	// Sessions (visits) over/under 60 seconds
		$sessions_greater_equal_60_seconds_params = array(
			'segment' => 'dynamic::ga:sessionDuration>=60',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);
		$sessions_under_60_seconds_params = array(
			'dimensions' 	=> (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country' : null,
			'segment' => 'dynamic::ga:sessionDuration<60',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		// raw numbers
		$sessions_under_60_seconds = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$sessions_under_60_seconds_params);
		$sessions_greater_equal_60_seconds = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$sessions_greater_equal_60_seconds_params);
			// percentage
			$under_60s_percent = round(($sessions_under_60_seconds->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);
			$greater_equal_60s_percent = 100 - $under_60s_percent;

	/* 

		= = = = = = = = = = = = = = = = =
				Traffic Sources
		= = = = = = = = = = = = = = = = = 

	*/ 

		$source_facebook_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==vicefb,ga:source==vicefbus,ga:source==vicefbuk,ga:source==l.facebook.com,ga:source==facebook.com,ga:source==m.facebook.com,ga:source==vicefacebr,ga:source==vicefbanz,ga:source==lm.facebook.com,ga:source==facepageit,ga:source==pacebookpageit,ga:source==idfb,ga:source==viceusfb,ga:source==noiseyfbuk,ga:source==noiseyfbus,ga:source==vicenewsfb,ga:source==noiseyfb,ga:source==noiseyfacebr,ga:source==vicenewsfbanz,ga:source==noiseyfbanz,ga:source==thumpfbanz,ga:source==thumpfbuk,ga:source==thumpfb,ga:source==thumpfacebr,ga:source==thumpfbus,ga:source==motherboardfb,ga:source==idfbus,ga:source==idfbanz,ga:source==idfacebook,ga:source==ifb,ga:source==tcpfb,ga:source==tcpfbanz,ga:source==munchiesfb,ga:source==15600',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_twitter_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==vicetwitterus,ga:source==vicetwitteruk,ga:source==t.co,ga:source==vicetwitter,ga:source==idtwitter,ga:source==idtwitteranz,ga:source==vicenewstwitter,ga:source==noiseytwitter,ga:source==noiseytwitteranz,ga:source==twitter,ga:source==tcptwitteranz,ga:source==tcptwitter,ga:source==thumptwitter,ga:source==thumptwitteruk,ga:source==thumptwitteranz,ga:source==thumptwitterbr,ga:source==thumptw,ga:source==munchiestwitter',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);
		
		$source_reddit_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@reddit.com,ga:source==np.reddit.com,ga:source==reddit',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_youtube_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==youtube.com,ga:source==m.youtube.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);


		// Start Vertical Sources

		$souce_vice_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==vice.com,ga:source==m.vice.com,ga:source==m.vice.cn',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_mobo_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==motherboard.vice.com,ga:source==motherboard',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_noisey_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@noisey.vice.com,ga:source=@m.noisey.vice.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_news_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@news.vice.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_thump_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@thump.vice.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_fightland_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@fightland.vice.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_tcp_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@thecreatorsproject.vice.com,ga:source=@thecreatorsproject.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_iD_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@i-d.vice.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_sports_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@sports.vice.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_munchies_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@munchies.tv,ga:source=@munchies.vice.com',
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		// End Vertical Sources

		$traffic_source_vice = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$souce_vice_params);
		$traffic_source_vice_percent = round(($traffic_source_vice->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_mobo = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_mobo_params);
		$traffic_source_mobo_percent = round(($traffic_source_mobo->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);
		
		$traffic_source_noisey = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_noisey_params);
		$traffic_source_noisey_percent = round(($traffic_source_noisey->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_news = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_news_params);
		$traffic_source_news_percent = round(($traffic_source_news->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_thump = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_thump_params);
		$traffic_source_thump_percent = round(($traffic_source_thump->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_fightland = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_fightland_params);
		$traffic_source_fightland_percent = round(($traffic_source_fightland->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_tcp = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_tcp_params);
		$traffic_source_tcp_percent = round(($traffic_source_tcp->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_iD = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_iD_params);
		$traffic_source_iD_percent = round(($traffic_source_iD->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_sports = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_sports_params);
		$traffic_source_sports_percent = round(($traffic_source_sports->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_munchies = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_munchies_params);
		$traffic_source_munchies_percent = round(($traffic_source_munchies->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		/*
			SOCIAL
		*/
			
			// Facebook
			$traffic_source_facebook = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_facebook_params);
			// twitter
			$traffic_source_twitter = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_twitter_params);
			// reddit 
			$traffic_source_reddit = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_reddit_params);
			// youtube
			$traffic_source_youtube = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_youtube_params);

			$fb = $traffic_source_facebook->totalsForAllResults['ga:sessions'];
			$tw = $traffic_source_twitter->totalsForAllResults['ga:sessions'];
			$rd = $traffic_source_reddit->totalsForAllResults['ga:sessions'];
			$yt = $traffic_source_youtube->totalsForAllResults['ga:sessions'];

			$social_totals = $fb + $tw + $rd + $yt;
			$social_percent = round(($social_totals / $general->totalsForAllResults['ga:sessions']) * 100, 2);




		// Start system segments available w/ gaid 

		$source_paid_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => $paid,
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_search_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => $search,
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$source_direct_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => $direct,
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,
		);

		$traffic_source_paid = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_paid_params);
		$traffic_source_paid_percent = round(($traffic_source_paid->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_search = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_search_params);
		$traffic_source_search_percent = round(($traffic_source_search->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$traffic_source_direct = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$source_direct_params);
		$traffic_source_direct_percent = round(($traffic_source_direct->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);


		// Calculate the OTHER %
		$other_raw_number = $traffic_source_direct_percent + $traffic_source_paid_percent + 
			$traffic_source_search_percent + $social_percent + $traffic_source_vice_percent + 
			$traffic_source_noisey_percent + $traffic_source_tcp_percent + $traffic_source_mobo_percent + 
			$traffic_source_fightland_percent + $traffic_source_thump_percent + $traffic_source_iD_percent + 
			$traffic_source_sports_percent + $traffic_source_news_percent + $traffic_source_munchies_percent;

		$other_percent = round((100 - $other_raw_number), 2);

		/* MOBILE ONLY */
		$mobile_params = array(
			'segment' => 'gaid::-14',
			'dimensions' 	=> (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country' : null,
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,

		);

		$mobile_data = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$mobile_params);
		$mobile_sessions = $mobile_data->totalsForAllResults['ga:sessions'];
		$mobile_pageviews = $mobile_data->totalsForAllResults['ga:pageviews'];
		$mobile_session_dur = $mobile_data->totalsForAllResults['ga:sessionDuration'];
		$mobile_avg_session_dur = round(($mobile_session_dur / $mobile_sessions) / 60, 2);


		/* TABLET ONLY */
		$tablet_params = array(
			'segment' => 'gaid::-13',
			'dimensions' 	=> (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country' : null,
			'filters' => (isset($_POST['country']) && $_POST['country'] == 2) ? 'ga:country==United States' : null,

		);

		$tablet_data = $service->data_ga->get($vertical,$start_date,$end_date,$general_metrics,$tablet_params);
		$tablet_sessions = $tablet_data->totalsForAllResults['ga:sessions'];
		$tablet_pageviews = $tablet_data->totalsForAllResults['ga:pageviews'];
		$tablet_session_dur = $tablet_data->totalsForAllResults['ga:sessionDuration'];
		$tablet_avg_session_dur = round(($tablet_session_dur / $tablet_sessions) / 60, 2);

		/* DESKTOP */

		// // total pageviews
		$general->totalsForAllResults['ga:pageviews'];
		// // total sessions
		$general->totalsForAllResults['ga:sessions'];
		// // total session duration
		$general->totalsForAllResults['ga:sessionDuration'];

		// desktop pageviews
		$desktop_pageviews = $general->totalsForAllResults['ga:pageviews'] - ($mobile_pageviews + $tablet_pageviews);
		// desktop sessions
		$desktop_sessions = $general->totalsForAllResults['ga:sessions'] - ($mobile_sessions + $tablet_sessions);
		// total session duration
		$total_session_duration = $general->totalsForAllResults['ga:sessionDuration'];
		// desktop session duration
		$desktop_session_dur = $total_session_duration - ($mobile_session_dur + $tablet_session_dur);

			$desktop_avg_session_dur = round(($desktop_session_dur / $desktop_sessions) / 60, 2);


// Round to hundredth 
function roundToHundredth($val) {
	$val = round($val, 2);
	return $val;
}

// Calcuate the Avg Session Duration
function calcAvgSessionDuration($val) {
	// Take raw seconds and divide by 60
	$val = $val / 60;
	// Round off to nearest .00
	$val = round($val, 2);
	// return the value
	return $val;
}

$general_data_arr = array(
	'total users' 			=> $general->totalsForAllResults['ga:users'],
	'total sessions' 		=> $general->totalsForAllResults['ga:sessions'],
	'total pageviews' 		=> $general->totalsForAllResults['ga:pageviews'],
	'time on site' 			=> $general_tos,
);

$desktop_data_arr = array(
	'desktop pageviews' 	=> $desktop_pageviews,
	'desktop sessions' 		=> $desktop_sessions,
	'desktop time on site'	=> $desktop_avg_session_dur,
);

$tablet_data_arr = array(
	'tablet pageviews' 		=> $tablet_pageviews,
	'tablet sessions'		=> $tablet_sessions,
	'tablet time on site'	=> $tablet_avg_session_dur,
);

$mobile_data_arr = array(
	'tablet pageviews' 		=> $mobile_pageviews,
	'tablet sessions'		=> $mobile_sessions,
	'tablet time on site'	=> $mobile_avg_session_dur,
);

$engagement_data_arr = array(
	'bounce rate'						=> $general_bounce,
	'new visitor session percent'		=> $new_visitor_session_percent,
	'return visitor session percent'	=> $returning_visitor_session_percent,
	'sessions over 25'					=> $sessions_over_25,
	'sessions over 25 percent'			=> $sessions_over_25_percent,
	'sessions under 60 seconds'			=> $under_60s_percent,
	'sessions 60 seconds or longer'		=> $greater_equal_60s_percent
);

$traffic_source_data_arr = array(
	'direct traffic'				=> $traffic_source_direct_percent,
	'paid traffic'					=> $traffic_source_paid_percent,
	'search traffic'				=> $traffic_source_search_percent,
	'social traffic'				=> $social_totals,
	'social percent'				=> $social_percent,
	'vice.com'						=> $traffic_source_vice_percent,
	'noisey'						=> $traffic_source_noisey_percent,
	'tcp'							=> $traffic_source_tcp_percent,
	'motherboard'					=> $traffic_source_mobo_percent,
	'fightland'						=> $traffic_source_fightland_percent,
	'thump'							=> $traffic_source_thump_percent,
	'i-D'							=> $traffic_source_iD_percent,
	'sports'						=> $traffic_source_sports_percent,
	'news'							=> $traffic_source_news_percent,
	'munchies'						=> $traffic_source_munchies_percent,
	'percent other'					=> $other_percent,
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Results</title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<?php
		$vertical_name = '';
		switch($vertical) {
			case "ga:45303215" : 
				$vertical_name = 'VICE.com';
				break;
			case "ga:60455995" :
				$vertical_name = 'Noisey';
				break;
			case "ga:19645623" :
				$vertical_name = 'Motherboard';
				break;
			case "ga:80445276" :
				$vertical_name = 'News';
				break;
			case "ga:71103059" :
				$vertical_name = 'Thump';
				break;
			case "ga:64493123" :
				$vertical_name = 'Fightland';
				break;
			case "ga:78356421";
				$vertical_name = 'i-D';
				break;
			case "ga:31015241";
				$vertical_name = 'Creators Project';
				break;
			case "ga:84227980";
				$vertical_name = 'Munchies';
				break;
			case "ga:86656621";
				$vertical_name = 'Sports';
				break;
		}

		$country_selection = '';

		if ($country == 1) {
			$country_selection = 'Global';
		} else {
			$country_selection = 'US Only';
		}

	?>
<div class="results-wrapper">
	<h2><?php echo $vertical_name; ?></h2>
	<h3><?php echo "Start/end dates: " . $start_date . " - " . $end_date; ?></h3>
	<h4><?php echo $country_selection; ?></h4>
	<br/><br/>
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
	<table class="data">
		<tr><td><strong>&nbsp;</strong></td></tr>
		<?php 
			foreach ($general_data_arr as $key => $value) {
				echo "<tr><td>";
				echo $value;
				echo "</td></tr>";
			}
		?>
	 	<tr><td><strong>&nbsp;</strong></td></tr>
	 	<?php 
		 	foreach ($desktop_data_arr as $key => $value) {
		 		echo "<tr><td>";
		 		echo $value;
		 		echo "</td></tr>";
		 	}
	  	?>
	 	<tr><td><strong>&nbsp;</strong></td></tr>
	 	<?php 
		 	foreach ($tablet_data_arr as $key => $value) {
		 		echo "<tr><td>";
		 		echo $value;
		 		echo "</td></tr>";
		 	}
		?>
		<tr><td><strong>&nbsp;</strong></td></tr>
		<?php
			foreach ($mobile_data_arr as $key => $value) {
				echo "<tr><td>";
		 		echo $value;
		 		echo "</td></tr>";
			}
		?>
		<tr><td><strong>&nbsp;</strong></td></tr>
		<?php
			foreach ($engagement_data_arr as $key => $value) {
				echo "<tr><td>";
		 		echo $value;
		 		echo "</td></tr>";
			}
		?>
		<tr><td><strong>&nbsp;</strong></td></tr>
		<?php
			foreach ($traffic_source_data_arr as $key => $value) {
				echo "<tr><td>";
		 		echo $value;
		 		echo "</td></tr>";
			}
		?>
	</table>
</div>
</body>
</html>