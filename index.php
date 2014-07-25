<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	

<div class="form-container">
	<h1>Janky, but functional GA Tool</h1>	
	<form method="POST" action="results.php" id="">
		<label for="start_date">Start Date</label>
		<input type="text" name="start_date" id="start_date" placeholder="YYYY-MM-DD">

		<label for="end_date">End Date</label>
		<input type="text" name="end_date" id="end_date" placeholder="YYYY-MM-DD">

		<label for="vertical_choice">Choose a Vertical</label>
		<select name="vertical_choice" id="vertical_choice">
			<option value="ga:45303215">VICE.com</option>
			<option value="ga:60455995">Noisey</option>
			<option value="ga:19645623">Motherboard</option>
			<option value="ga:80445276">News</option>
			<option value="ga:71103059">Thump</option>
			<option value="ga:64493123">Fightland</option>
			<option value="ga:78356421">i-D</option>
			<option value="ga:31015241">Creators Project</option>
			<option value="ga:84227980">Munchies</option>
			<option value="ga:86656621">Sports</option>
		</select>

		<label for="country">Global or US Only</label>
		<select name="country" id="country">
			<option value="1">Global</option>
			<option value="2">US Only</option>
		</select>

		<input type="submit" class="submit">
	</form>
	<div class="loader">
		<img src="assets/images/loader.gif" alt="loading">
		<p>Please wait...</p>
	</div>
</div>



<script type="text/javascript" src="assets/js/jquery-1.11.1.js"></script>
<script type="text/javascript">
	
	$('.submit').on('click', function() {
		$('.loader').css({'display' : 'block'});
	});



</script>
</body>
</html>