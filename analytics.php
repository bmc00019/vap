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
$start_date = '2014-04-01';
$end_date = '2014-04-02';
$metrics = 'ga:sessions,ga:visits,ga:pageviews,ga:avgSessionDuration,ga:bounceRate,ga:sessionDuration,ga:exits';

$optParams = array(
	'dimensions' => 'ga:source',
	'sort' => '-ga:sessions',
	// 'segment' => $tablet,
	// 'dimensions' => 'ga:userType',
	// 'segment' => 'dynamic::ga:sessionCount==26',
	// 'segment' => 'dynamic::ga:sessionDuration<=60',
	);

// $dimensions = "ga:browser";
$mobo = $service->data_ga->get($ids,$start_date,$end_date,$metrics,$optParams);
$mobo_tablet_mobile = $service->data_ga->get($ids,$start_date,$end_date,$metrics,$optParams);

//Motherboard
$motherboard_id = 'ga:19645623';
$vice_id = 'ga:45303215';


/* 
	Begin VICE.com 
*/

// General Metrics
$general_metrics = 'ga:users,ga:sessions,ga:pageviews,ga:avgSessionDuration,ga:sessionDuration,ga:bounceRate';
	/*
		[0] Users (uniques)
		[1] Sessions (visits)
		[2] Pageviews
		[3] Average Session Duration
		[4] Session Duration (raw)
		[5] Bounce Rate
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
$vice_global_general = $service->data_ga->get($vice_id,$start_date,$end_date,$general_metrics);
$vg_general_data = $vice_global_general->rows;
$vg_general_data = array_pop($vg_general_data);
	
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
	get($vice_id,$start_date,$end_date,$mobile_metrics,$mobile_opt_params);

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
$vice_global_general = $service->data_ga->get($vice_id,$start_date,$end_date,$general_metrics,$new_vs_returning);

echo "<pre>";
echo "user type";
print_r($vice_global_general);
echo "</pre>";

// $vg_percent_new_visitors = ($new_visitor_raw / $vg_general_data[1]);
// $vg_percent_returning_visitors = ($returning_visitors_raw / $vg_general_data[1]);

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