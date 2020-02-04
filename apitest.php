<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
<!-- 		<?php
			echo "<h2>Using curl for Geocode API (XML) </h2><br>";
			// Add Key below
			$geocode_api_key="AIzaSyDmhDrp0PG4nWvAOfPmiKN24KmGrSsV-gA";
			$street=urlencode("1600 Amphitheatre Parkway");
			$city=urlencode("Mountain View");
			$state=urlencode("CA");
			$basic_url_geocode="https://maps.googleapis.com/maps/api/geocode/xml?address=";
			$geocode_url=$basic_url_geocode.$street.",+".$city.",+".$state."&key=".$geocode_api_key; 


			echo "<br>";
			echo "<br>";
			
			
    		$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$geocode_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result_geocode = curl_exec($ch);
			curl_close($ch);

			$geocode_xml_data=simplexml_load_string($result_geocode);
			echo "<br>";
			echo "<br>";
			$latitude= $geocode_xml_data->result->geometry->location->lat;
    		$longitude= $geocode_xml_data->result->geometry->location->lng;
			
		?>
		
		<hr>
		<?php
			echo "<h2>Using simplexml_load_file for Geocode API (XML) </h2><br>";
			// Add Key below
			$geocode_api_key="AIzaSyDmhDrp0PG4nWvAOfPmiKN24KmGrSsV-gA";
			$street=urlencode("1600 Amphitheatre Parkway");
			$city=urlencode("Mountain View");
			$state=urlencode("CA");
			$basic_url_geocode="https://maps.googleapis.com/maps/api/geocode/xml?address=";
			$geocode_url=$basic_url_geocode.$street.",+".$city.",+".$state."&key=".$geocode_api_key; 


			echo "<br>";
			echo "<br>";

			$geocode_xml_data = simplexml_load_file($geocode_url) or die("Failed to load");
    		$latitude= $geocode_xml_data->result->geometry->location->lat;
    		$longitude= $geocode_xml_data->result->geometry->location->lng;


		?> -->
		<hr>
		<?php
			echo "<h2>Using file_get_contents for IP API </h2><br>";
			$ipapi_url="http://ip-api.com/json/";

			echo $ipapi_url;

			echo "<br>";
			echo "<br>";

			$ipapi_json = file_get_contents($ipapi_url);
			$ipapi_obj = json_decode($ipapi_json,true);

			var_dump($ipapi_obj); 
			echo "<br><br>";
			$ip_latitude=$ipapi_obj["lat"];
			$ip_longitude=$ipapi_obj["lon"];
			echo $ip_latitude ."\n";
    		echo $ip_longitude;

		?>
		
		<?php
			echo "<h2>Curl for DarkSky API </h2><br>";
			//Add key below
			$darksky_api_key="297ea87061d9a1983ffc384495799fc4";
			$basic_url_darksky="https://api.forecast.io/forecast/";
			$basic_url_darksky_end="?exclude=minutely,hourly,alerts,flags";
			$darksky_url=$basic_url_darksky.$darksky_api_key."/".$latitude.",".$longitude.$basic_url_darksky_end;

			echo $darksky_url;

			echo "<br>";
			echo "<br>";

			$ch2 = curl_init();
			curl_setopt($ch2, CURLOPT_URL,$darksky_url);
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
			$result_darksky = curl_exec($ch2);
			curl_close($ch2);

			$darksky_obj2 = json_decode($result_darksky,true);
			//var_dump($darksky_obj2);
			
			
			$currently = $darksky_obj2['currently'];

			$data = $darksky_obj2['daily'];
			$daily = $data['data'];

			foreach ($daily as $row) {

			$time = $row['time'];
			//var_dump($time);
			$darksky_api_key="297ea87061d9a1983ffc384495799fc4";
			$basic_url_darksky="https://api.darksky.net/forecast/";
			$basic_url_darksky_end="?exclude=minutely";
			$darksky_url=$basic_url_darksky.$darksky_api_key."/".$latitude.",".$longitude.",".$time.$basic_url_darksky_end;

			$ch3 = curl_init();
			curl_setopt($ch3, CURLOPT_URL,$darksky_url);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
			$result_darksky1 = curl_exec($ch3);
			curl_close($ch3);

			$darksky_obj3 = json_decode($result_darksky1,true);
			var_dump($darksky_obj3);

			echo "<br><br>";
		}



		?>
		<hr>
		<?php
			echo "<h2>Using file_get_contents for DarkSky API </h2><br>";
			//Add key below
			$darksky_api_key="297ea87061d9a1983ffc384495799fc4";
			$basic_url_darksky="https://api.forecast.io/forecast/";
			$basic_url_darksky_end="?exclude=minutely,hourly,alerts,flags";
			$darksky_url=$basic_url_darksky.$darksky_api_key."/".$latitude.",".$longitude.$basic_url_darksky_end;

			echo $darksky_url;

			echo "<br>";
			echo "<br>";

			$darksky_json = file_get_contents($darksky_url);
			$darksky_obj = json_decode($darksky_json,true);
			

		?>

	<script type="text/javascript">
		

		
	</script>

	</body>
</html>