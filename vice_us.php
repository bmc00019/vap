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

/*
	ids, start date, system segments
*/
	//vice 
	$vice_id = 'ga:45303215';
	// mobo
	$motherboard_id = 'ga:19645623';
	// noisey
	$noisey_id = 'ga:41880236';
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
	// Sports
	$sports_id = 'ga:86656621';

	$start_date = '2014-06-01';
	$end_date = '2014-06-30';

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
	$us_general_params = array(
		'dimensions' => 'ga:country',
		'filters' => 'ga:country==United States',
		);

/*
	Begin Queries for Data
*/
	/* GENERAL */
	$general = $service->data_ga->get($vice_id,$start_date,$end_date,$general_metrics,$us_general_params);

	// general - time on site (tos)
	$general_tos = calcAvgSessionDuration($general->totalsForAllResults['ga:avgSessionDuration']);

	$us_general_bounce = roundToHundredth($general->totalsForAllResults['ga:bounceRate']);


	// User Type
		$us_user_type_params = array(
			'dimensions' => 'ga:userType',
			'filters' => 'ga:country==United States',
		); 
		$us_general_user_type = $service->data_ga->get($vice_id,$start_date,$end_date,$general_metrics,$us_user_type_params);

		$us_total_users = $us_general_user_type->totalsForAllResults['ga:users'];
		$user_types = $us_general_user_type->rows;

		$return_users = array_pop($user_types);
		$return_users = $return_users[1];

		$return_users_percent = round(($return_users / $us_total_users) * 100, 2);

		$new_users_percent = (100 - $return_users_percent);

	// Sessions by return users who have visited 26 times or more
		$user_session_count_params = array(
			'dimensions' => 'ga:userType',
			'segment' => 'dynamic::ga:sessionCount>=26',
			'filters' => 'ga:country==United States',
		);

		$us_sessions_over_25_data = $service->data_ga->get($vice_id,$start_date,$end_date,$general_metrics,$user_session_count_params);
		$us_sessions_over_25 = $us_sessions_over_25_data->totalsForAllResults['ga:sessions'];
		$us_sessions_over_25_percent = round(($us_sessions_over_25 / $general->totalsForAllResults['ga:sessions']) * 100, 2);

	// Sessions (visits) over/under 60 seconds
		










		/* MOBILE & TABLET */
		$mobile_tablet_params = array(
			'dimensions' => 'ga:country',
			'filters' => 'ga:country==United States',
			'segment' => 'gaid::-11'
			);

		$us_mobile_tablet = $service->data_ga->get($vice_id,$start_date,$end_date,$general_metrics,$mobile_tablet_params);
		$us_mobile_tablet_tos = calcAvgSessionDuration($us_mobile_tablet->totalsForAllResults['ga:avgSessionDuration']);

		/* DESKTOP */

		// total pageviews
		$general->totalsForAllResults['ga:pageviews'];
		// total sessions
		$general->totalsForAllResults['ga:sessions'];
		// total session duration
		$general->totalsForAllResults['ga:sessionDuration'];

		// mobile tablet pageviews
		$us_mobile_tablet->totalsForAllResults['ga:pageviews'];
		// mobile tablet sessions
		$us_mobile_tablet->totalsForAllResults['ga:sessions'];
		// mobile tablet session duration
		$us_mobile_tablet->totalsForAllResults['ga:sessionDuration'];


		// Desktop Pageviews
		$us_desktop_pageviews = ($general->totalsForAllResults['ga:pageviews']) - ($us_mobile_tablet->totalsForAllResults['ga:pageviews']);
		// Desktop Sessions
		$us_desktop_sessions = ($general->totalsForAllResults['ga:sessions']) - ($us_mobile_tablet->totalsForAllResults['ga:sessions']);
		// Desktop Session Duration
		$us_desktop_session_duration = ($general->totalsForAllResults['ga:sessionDuration']) - ($us_mobile_tablet->totalsForAllResults['ga:sessionDuration']);

		// Desktop Time on Site
		$us_desktop_tos = $us_desktop_session_duration / $us_desktop_sessions;
		$us_desktop_tos = calcAvgSessionDuration($us_desktop_tos);



	


	

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

?>