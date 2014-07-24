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
	$noisey_id = 'ga:60455995';
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
	$general = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$us_general_params);

	// general - time on site (tos)
	$general_tos = calcAvgSessionDuration($general->totalsForAllResults['ga:avgSessionDuration']);

	$us_general_bounce = roundToHundredth($general->totalsForAllResults['ga:bounceRate']);


	// User Type
		$us_user_type_params = array(
			'dimensions' => 'ga:userType',
			'filters' => 'ga:country==United States',
		); 
		$us_general_user_type = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$us_user_type_params);

		$total_users = $us_general_user_type->totalsForAllResults['ga:users'];
		$user_types = $us_general_user_type->rows;

		$new_visitors_sessions = $user_types[0][2];
		$returning_visitors_sessions = $user_types[1][2];

		$total_user_sessions = $new_visitors_sessions + $returning_visitors_sessions;
		
		$new_visitor_session_percent = round(($new_visitors_sessions / $total_user_sessions) * 100, 2);
		$returning_visitor_session_percent = 100 - $new_visitor_session_percent;

	// Sessions by return users who have visited 26 times or more
		$user_session_count_params = array(
			'dimensions' => 'ga:userType,ga:country',
			'segment' => 'dynamic::ga:sessionCount>=26',
			'filters' => 'ga:country==United States',
		);

		$us_sessions_over_25_data = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$user_session_count_params);
		$us_sessions_over_25 = $us_sessions_over_25_data->totalsForAllResults['ga:sessions'];
		$us_sessions_over_25_percent = round(($us_sessions_over_25 / $general->totalsForAllResults['ga:sessions']) * 100, 2);

	// Sessions (visits) over/under 60 seconds
		$us_sessions_greater_equal_60_seconds_params = array(
			'segment' => 'dynamic::ga:sessionDuration>=60',
			'filters' => 'ga:country==United States',
		);
		$us_sessions_under_60_seconds_params = array(
			'dimensions' => 'ga:country',
			'segment' => 'dynamic::ga:sessionDuration<60',
			'filters' => 'ga:country==United States',
		);

		// raw numbers
		$us_sessions_under_60_seconds = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$us_sessions_under_60_seconds_params);
		$us_sessions_greater_equal_60_seconds = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$us_sessions_greater_equal_60_seconds_params);
			// percentage
			$us_under_60s_percent = round(($us_sessions_under_60_seconds->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);
			$us_greater_equal_60s_percent = 100 - $us_under_60s_percent;

	/* 

		= = = = = = = = = = = = = = = = =
				Traffic Sources
		= = = = = = = = = = = = = = = = = 

	*/ 

		$source_facebook_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==vicefb,ga:source==vicefbus,ga:source==vicefbuk,ga:source==l.facebook.com,ga:source==facebook.com,ga:source==m.facebook.com,ga:source==vicefacebr,ga:source==vicefbanz,ga:source==lm.facebook.com,ga:source==facepageit,ga:source==pacebookpageit,ga:source==idfb,ga:source==viceusfb,ga:source==noiseyfbuk,ga:source==noiseyfbus,ga:source==vicenewsfb,ga:source==noiseyfb,ga:source==noiseyfacebr,ga:source==vicenewsfbanz,ga:source==noiseyfbanz,ga:source==thumpfbanz,ga:source==thumpfbuk,ga:source==thumpfb,ga:source==thumpfacebr,ga:source==thumpfbus,ga:source==motherboardfb,ga:source==idfbus,ga:source==idfbanz,ga:source==idfacebook,ga:source==ifb,ga:source==tcpfb,ga:source==tcpfbanz,ga:source==munchiesfb,ga:source==15600',
			'filters' => 'ga:country==United States',
		);

		$source_twitter_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==vicetwitterus,ga:source==vicetwitteruk,ga:source==t.co,ga:source==vicetwitter,ga:source==idtwitter,ga:source==idtwitteranz,ga:source==vicenewstwitter,ga:source==noiseytwitter,ga:source==noiseytwitteranz,ga:source==twitter,ga:source==tcptwitteranz,ga:source==tcptwitter,ga:source==thumptwitter,ga:source==thumptwitteruk,ga:source==thumptwitteranz,ga:source==thumptwitterbr,ga:source==thumptw,ga:source==munchiestwitter',
			'filters' => 'ga:country==United States',
		);
		
		$source_reddit_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@reddit.com,ga:source==np.reddit.com,ga:source==reddit',
			'filters' => 'ga:country==United States',
		);

		$source_youtube_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==youtube.com,ga:source==m.youtube.com',
			'filters' => 'ga:country==United States',
		);


		// Start Vertical Sources

		$souce_vice_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==vice.com,ga:source==m.vice.com,ga:source==m.vice.cn',
			'filters' => 'ga:country==United States',
		);

		$source_mobo_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source==motherboard.vice.com,ga:source==motherboard',
			'filters' => 'ga:country==United States',
		);

		$source_noisey_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@noisey.vice.com,ga:source=@m.noisey.vice.com',
			'filters' => 'ga:country==United States',
		);

		$source_news_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@news.vice.com',
			'filters' => 'ga:country==United States',
		);

		$source_thump_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@thump.vice.com',
			'filters' => 'ga:country==United States',
		);

		$source_fightland_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@fightland.vice.com',
			'filters' => 'ga:country==United States',
		);

		$source_tcp_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@thecreatorsproject.vice.com,ga:source=@thecreatorsproject.com',
			'filters' => 'ga:country==United States',
		);

		$source_iD_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@i-d.vice.com',
			'filters' => 'ga:country==United States',
		);

		$source_sports_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@sports.vice.com',
			'filters' => 'ga:country==United States',
		);

		$source_munchies_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => 'sessions::condition::ga:source=@munchies.tv,ga:source=@munchies.vice.com',
			'filters' => 'ga:country==United States',
		);

		// End Vertical Sources

		$us_traffic_source_vice = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$souce_vice_params);
		$us_traffic_source_vice_percent = round(($us_traffic_source_vice->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_mobo = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_mobo_params);
		$us_traffic_source_mobo_percent = round(($us_traffic_source_mobo->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);
		
		$us_traffic_source_noisey = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_noisey_params);
		$us_traffic_source_noisey_percent = round(($us_traffic_source_noisey->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_news = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_news_params);
		$us_traffic_source_news_percent = round(($us_traffic_source_news->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_thump = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_thump_params);
		$us_traffic_source_thump_percent = round(($us_traffic_source_thump->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_fightland = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_fightland_params);
		$us_traffic_source_fightland_percent = round(($us_traffic_source_fightland->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_tcp = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_tcp_params);
		$us_traffic_source_tcp_percent = round(($us_traffic_source_tcp->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_iD = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_iD_params);
		$us_traffic_source_iD_percent = round(($us_traffic_source_iD->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_sports = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_sports_params);
		$us_traffic_source_sports_percent = round(($us_traffic_source_sports->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_munchies = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_munchies_params);
		$us_traffic_source_munchies_percent = round(($us_traffic_source_munchies->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		/*
			SOCIAL
		*/
			
			// Facebook
			$us_traffic_source_facebook = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_facebook_params);
			// twitter
			$us_traffic_source_twitter = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_twitter_params);
			// reddit 
			$us_traffic_source_reddit = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_reddit_params);
			// youtube
			$us_traffic_source_youtube = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_youtube_params);

			$fb = $us_traffic_source_facebook->totalsForAllResults['ga:sessions'];
			$tw = $us_traffic_source_twitter->totalsForAllResults['ga:sessions'];
			$rd = $us_traffic_source_reddit->totalsForAllResults['ga:sessions'];
			$yt = $us_traffic_source_youtube->totalsForAllResults['ga:sessions'];

			$social_totals = $fb + $tw + $rd + $yt;
			$social_percent = round(($social_totals / $general->totalsForAllResults['ga:sessions']) * 100, 2);




		// Start system segments available w/ gaid 

		$source_paid_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => $paid,
			'filters' => 'ga:country==United States',
		);

		$source_search_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => $search,
			'filters' => 'ga:country==United States',
		);

		$source_direct_params = array(
			'dimensions' => 'ga:source',
			'sort' => '-ga:sessions',
			'segment' => $direct,
			'filters' => 'ga:country==United States',
		);

		$us_traffic_source_paid = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_paid_params);
		$us_traffic_source_paid_percent = round(($us_traffic_source_paid->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_search = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_search_params);
		$us_traffic_source_search_percent = round(($us_traffic_source_search->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);

		$us_traffic_source_direct = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$source_direct_params);
		$us_traffic_source_direct_percent = round(($us_traffic_source_direct->totalsForAllResults['ga:sessions'] / $general->totalsForAllResults['ga:sessions']) * 100, 2);


		// Calculate the OTHER %
		$other_raw_number = $us_traffic_source_direct_percent + $us_traffic_source_paid_percent + 
			$us_traffic_source_search_percent + $social_percent + $us_traffic_source_vice_percent + 
			$us_traffic_source_noisey_percent + $us_traffic_source_tcp_percent + $us_traffic_source_mobo_percent + 
			$us_traffic_source_fightland_percent + $us_traffic_source_thump_percent + $us_traffic_source_iD_percent + 
			$us_traffic_source_sports_percent + $us_traffic_source_news_percent + $us_traffic_source_munchies_percent;

		$other_percent = round((100 - $other_raw_number), 2);

		/* MOBILE ONLY */
		$mobile_params = array(
			'segment' => 'gaid::-14',
			'dimensions' => 'ga:country',
			'filters' => 'ga:country==United States',
		);

		$mobile_data = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$mobile_params);
		$mobile_sessions = $mobile_data->totalsForAllResults['ga:sessions'];
		$mobile_pageviews = $mobile_data->totalsForAllResults['ga:pageviews'];
		$mobile_session_dur = $mobile_data->totalsForAllResults['ga:sessionDuration'];
		$mobile_avg_session_dur = round(($mobile_session_dur / $mobile_sessions) / 60, 2);


		/* TABLET ONLY */
		$tablet_params = array(
			'segment' => 'gaid::-13',
			'dimensions' => 'ga:country',
			'filters' => 'ga:country==United States',
		);

		$tablet_data = $service->data_ga->get($sports_id,$start_date,$end_date,$general_metrics,$tablet_params);
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

?>