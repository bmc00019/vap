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


$vice_global_acct = 'ga:599058';

// Segments
$mobile_and_tablet = 'gaid::-11';
$tablet = 'gaid::-13';
$mobile = 'gaid:-14';


$ids = 'ga:19645623';
$start_date = '2014-06-01';
$end_date = '2014-06-30';

// mobo
$motherboard_id = 'ga:19645623'; // done
//vice 
$vice_id = 'ga:45303215'; // done
// noisey
$noisey_id = 'ga:41880236'; // done
// news
$news_id = 'ga:80445276';
// munchies
$munchies_id = 'ga:84227980';
// thump
$thump_id = 'ga:71103059';
// fightland
$fightland_id = 'ga:64493123';
// I-D
$id_id = 'ga:78356421';
// tcp
$tcp_id = 'ga:31015241';


/* 
	Begin VICE.com 
*/

// General Metrics
$general_metrics = 'ga:users,ga:sessions,ga:pageviews,ga:avgSessionDuration,ga:sessionDuration,ga:bounceRate,ga:exits';
	/*
		[0] Users (uniques)
		[1] Sessions (visits)
		[2] Pageviews
		[3] Average Session Duration
		[4] Session Duration (raw)
		[5] Bounce Rate
		[6] Exits
	*/

// Mobile Metrics (Mobile & Tablet)
$mobile_metrics = 'ga:sessions,ga:pageviews,ga:avgSessionDuration,ga:sessionDuration';
	/*
		[0] Sessions (visits)
		[1] Pageviews
		[2] Average Session Duration
		[3] Session Duration (raw)
	*/

/* 
	+ + + Destkop calcuated via total minus mobile metrics + + +
																*/

// General
$vice_global_general = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics);
$vg_general_data = $vice_global_general->rows;
$vg_general_data = array_pop($vg_general_data);

$total_sessions = $vice_global_general->totalsForAllResults['ga:sessions'];
	
	// VICE Global Avg Session Duration
	$vice_global_avg_sess_dur = calcAvgSessionDuration($vg_general_data[3]);

	// Bounce Rate
	$vg_bounce_rate = round($vg_general_data[5], 2);

// Mobile & Tablet
$mobile_segment = 'gaid::-11';
$mobile_opt_params = array(
	'segment' => $mobile_segment,
	);
$vice_global_mobile_tablet = $service->data_ga->
	get($motherboard_id,$start_date,$end_date,$mobile_metrics,$mobile_opt_params);

// Mobile & Tablet Data
$vice_global_mobile_tablet_data = $vice_global_mobile_tablet->rows;
$vice_global_mobile_tablet_data = array_pop($vice_global_mobile_tablet_data);

	// VICE Mobile & Tablet Average Session Duration
	$vice_mobile_tab_avg_sess_dur = calcAvgSessionDuration($vice_global_mobile_tablet_data[2]);
	// VICE Mobile & Tablet Session Duration
	$vice_mobile_tab_session_dur = $vice_global_mobile_tablet_data[3];


// Desktop
	// Desktop Sessions (raw)
	$vg_desktop_sessions = ($vg_general_data[1] - $vice_global_mobile_tablet_data[0]);
	// Desktop Pageviews
	$vg_desktop_pageviews = ($vg_general_data[2] - $vice_global_mobile_tablet_data[1]);

	// Total Desktop Session Duration
	$vg_desktop_session_dur = ($vg_general_data[4] - $vice_global_mobile_tablet_data[3]);
	// Desktop Average Session Duration (seconds)
	$vg_desktop_avg_sess_dur = ($vg_desktop_session_dur / $vg_desktop_sessions);
		// Desktop Avg Session Duration (minutes)
		$vg_desktop_avg_sess_dur = calcAvgSessionDuration($vg_desktop_avg_sess_dur);

// VICE Global User Types
$new_vs_returning = array(
		'dimensions' => 'ga:userType'
	);
$vice_global_general_users = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$new_vs_returning);

// get the User Totals array
$vg_user_totals = $vice_global_general_users->totalsForAllResults;
// New Visitors (raw)
$vg_new_user_raw = $vice_global_general_users->rows[0][1];

// Returning Visitors (raw)
$vg_returning_users_raw = $vice_global_general_users->rows[1][1];
	// New Visitors (%)
	$vg_new_users = calcPercentage($vg_new_user_raw, $vg_user_totals['ga:users']);
	// Return Visitors (%)
	$vg_return_users = calcPercentage($vg_returning_users_raw, $vg_user_totals['ga:users']);

// Sessions/Visits for Return Users who have visited the site 26 times or more

	$user_session_count_params = array(
		'dimensions' => 'ga:userType',
		'segment' => 'dynamic::ga:sessionCount>=26'
		);

	$vice_global_user_count = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$user_session_count_params);

	$sessions_users_over_26_percent = calcPercentage($vice_global_user_count->totalsForAllResults['ga:sessions'], $vg_general_data[1]);

// Sessions/Visits over and under 60 seconds

	// OVER
	$user_session_duration_params = array(
		'segment' => 'dynamic::ga:sessionDuration>60',
		);
	$vice_global_session_duration = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$user_session_duration_params);

	$sessions_over_60_seconds_raw = $vice_global_session_duration->totalsForAllResults['ga:sessions'];

	$sessions_over_60_seconds = calcPercentage($sessions_over_60_seconds_raw, $vg_general_data[1]);

	// UNDER
	$user_session_duration_params_2 = array(
		'segment' => 'dynamic::ga:sessionDuration<=60',
		);
	$vice_global_session_duration = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$user_session_duration_params_2);
	
	$sessions_under_60_seconds_raw = $vice_global_session_duration->totalsForAllResults['ga:sessions'];

	$sessions_under_60_seconds = calcPercentage($sessions_under_60_seconds_raw, $vg_general_data[1]);

// Traffic Sources

// Traffic based segments
$paid = 'gaid::-4';
$organic = 'gaid::-5';
$search = 'gaid::-6';
$direct = 'gaid::-7';
$referral = 'gaid::-8';

	// Direct Traffic
	$direct_params = array(
		'dimensions' => 'ga:source',
		'segment' => $direct,
		);

	$vg_direct_traffic_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$direct_params);
	$direct_traffic = $vg_direct_traffic_data->totalsForAllResults['ga:sessions'];
	$direct_traffic_percentage = calcPercentage($direct_traffic, $vg_general_data[1]);

	// Paid Traffic
	$paid_params = array(
		'dimensions' => 'ga:source',
		'segment' => $paid,
		);

	$vg_paid_traffic_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$paid_params);
	$paid_traffic = $vg_paid_traffic_data->totalsForAllResults['ga:sessions'];
	$paid_traffic_percentage = calcPercentage($paid_traffic, $vg_general_data[1]);

	// Search Traffic
	$search_params = array(
		'dimensions' => 'ga:source',
		'segment' => $search,
		);

	$vg_search_traffic_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$search_params);
	$search_traffic = $vg_search_traffic_data->totalsForAllResults['ga:sessions'];
	$search_traffic_percentage = calcPercentage($search_traffic, $vg_general_data[1]);

	// Facebook
	$facebook_params = array(
		'dimensions' => 'ga:source',
		'segment' => 'dynamic::ga:source=~motherboard',
		);
	$vg_facebook_traffic_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$facebook_params);
	$facebook_traffic = $vg_facebook_traffic_data->totalsForAllResults['ga:sessions'];


	// Noisey.vice.com / m.noisey.vice.com
	$noisey_sources_params = array(
		'dimensions' => 'ga:source',
		'sort' => '-ga:sessions',
		'segment' => 'dynamic::ga:source=~noisey.vice.com',
		);
	$noisey_traffic_source_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$noisey_sources_params);
	$noisey_traffic_source_raw = $noisey_traffic_source_data->totalsForAllResults['ga:sessions'];
	$noisey_traffic_source_percentage = calcPercentage($noisey_traffic_source_raw, $vg_general_data[1]);

	// Motherboard
	$motherboard_sources_params = array(
		'dimensions' => 'ga:source',
		'sort' => '-ga:sessions',
		'segment' => 'dynamic::ga:source=~motherboard.vice.com',
		);
	$motherboard_traffic_source_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$motherboard_sources_params);
	$motherboard_traffic_source_raw = $motherboard_traffic_source_data->totalsForAllResults['ga:sessions'];
	$motherboard_traffic_source_percentage = calcPercentage($motherboard_traffic_source_raw, $vg_general_data[1]);

	// Fightland
	$fightland_sources_params = array(
		'dimensions' => 'ga:source',
		'sort' => '-ga:sessions',
		'segment' => 'dynamic::ga:source=~fightland.vice.com',
		);
	$fightland_traffic_source_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$fightland_sources_params);
	$fightland_traffic_source_raw = $fightland_traffic_source_data->totalsForAllResults['ga:sessions'];
	$fightland_traffic_source_percentage = calcPercentage($fightland_traffic_source_raw, $vg_general_data[1]);

	// TCP
	$thecreatorsproject_sources_params = array(
		'dimensions' => 'ga:source',
		'sort' => '-ga:sessions',
		'segment' => 'dynamic::ga:source=~thecreatorsproject.vice.com',
		);
	$thecreatorsproject_traffic_source_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$thecreatorsproject_sources_params);
	$thecreatorsproject_traffic_source_raw = $thecreatorsproject_traffic_source_data->totalsForAllResults['ga:sessions'];
	$thecreatorsproject_traffic_source_percentage = calcPercentage($thecreatorsproject_traffic_source_raw, $vg_general_data[1]);

	// Thump
	$thump_sources_params = array(
		'dimensions' => 'ga:source',
		'sort' => '-ga:sessions',
		'segment' => 'dynamic::ga:source=~thump.vice.com',
		);
	$thump_traffic_source_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$thump_sources_params);
	$thump_traffic_source_raw = $thump_traffic_source_data->totalsForAllResults['ga:sessions'];
	$thump_traffic_source_percentage = calcPercentage($thump_traffic_source_raw, $vg_general_data[1]);

	// Facebook & Twitter
	$ft_sources_params = array(
		'dimensions' => 'ga:source',
		'sort' => '-ga:sessions',
		'segment' => 'sessions::condition::ga:source=@twitter.com,ga:source=@facebook.com,ga:source=@facebookpageit,ga:source=@facepageit,ga:source=@facebookpageita,ga:source=@facebookpageital,ga:source=@facebookpage,ga:source=@t.co,ga:source=@tumblr.com,ga:source=@twitterpageit,ga:source=@twitterpageita,ga:source=@vicefb,ga:source=@vicetwitter',
		);
	$social_traffic_source_data = $service->data_ga->get($motherboard_id,$start_date,$end_date,$general_metrics,$ft_sources_params);
	$social_traffic_source_raw = $social_traffic_source_data->totalsForAllResults['ga:sessions'];


$social_traffic_percent = calcPercentage($social_traffic_source_raw, $total_sessions);

$other_percentage = 100 - ($direct_traffic_percentage + $paid_traffic_percentage + $search_traffic_percentage + $social_traffic_percent + $noisey_traffic_source_percentage + $thecreatorsproject_traffic_source_percentage + $motherboard_traffic_source_percentage + $fightland_traffic_source_percentage + $thump_traffic_source_percentage);

// Calculate New/Return User %
function calcPercentage($users, $users_total) {
	// divide visitor range by total visits
	$val = ($users / $users_total) * 100;
	// round off to nearest .00
	$val = round($val, 2);
	return $val;
}

// Calcuate the Avg Session Duration
function calcAvgSessionDuration($val) {
	// Take raw seconds and divide by 60
	$val = $val / 60;
	// Round off to nearest .00
	$val = round ($val, 2);
	// return the value
	return $val;
}

?>