<?php 
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$geocode_api_key="AIzaSyDmhDrp0PG4nWvAOfPmiKN24KmGrSsV-gA";

		if($_POST["lat1"]=="")
		{
			$street=urlencode($_POST["t1"]);
			$city=urlencode($_POST["t2"]);
			$state=urlencode($_POST["t3"]);
			$basic_url_geocode="https://maps.googleapis.com/maps/api/geocode/xml?address=";
			$geocode_url=$basic_url_geocode.$street.",+".$city.",+".$state."&key=".$geocode_api_key;
	    		
	    	$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$geocode_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result_geocode = curl_exec($ch);
			curl_close($ch);

			$geocode_xml_data=simplexml_load_string($result_geocode);
			

			$latitude= $geocode_xml_data->result->geometry->location->lat;
	    	$longitude= $geocode_xml_data->result->geometry->location->lng;
	    	$city = $_POST["t2"];
		}
		else
		{
			$latitude= $_POST["lat1"];
	    	$longitude= $_POST["long1"];
	    	$city = $_POST["city1"];
		}

    	$darksky_api_key="297ea87061d9a1983ffc384495799fc4";
		$basic_url_darksky="https://api.forecast.io/forecast/";
		$basic_url_darksky_end="?exclude=minutely,hourly,alerts,flags";
		$darksky_url=$basic_url_darksky.$darksky_api_key."/".$latitude.",".$longitude.$basic_url_darksky_end;

		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL,$darksky_url);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		$result_darksky = curl_exec($ch2);
		curl_close($ch2);

		$darksky_obj2 = json_decode($result_darksky,true);
		$currently = $darksky_obj2['currently'];
		$data = $darksky_obj2['daily'];
		$daily = $data['data'];
		$html = 
		"<br><br><div id='first'><div class='d1'><span class='line1'><b>{$city}</b></span><br><span>{$darksky_obj2['timezone']}</span><br><span class='line3_1'><b>{$currently['temperature']}</b><sup  class = 'degree' style='color: black; border: none;'>&#730;</sup></span><b><span class='line3_2'>F</span><br><span class='line4'>{$currently['summary']}</span></b><br><img class='img1' src='w1.png' width='25px' height='25px' title='Humidity'><img class='img2' src='w2.png' width='25px' height='25px' title='Pressure'><img class='img3' src='w3.png' width='25px' height='25px' title='Wind Speed'><img class='img4' src='w4.png' width='25px' height='25px' title='Visibility'><img class='img5' src='w5.png' width='25px' height='20px' title='CloudCover'><img class='img6' src='w6.png' width='25px' height='25px' title='Ozone'><br><b> <span class='data1'>{$currently['humidity']}</span><span class='data2'>{$currently['pressure']}</span><span class='data3'>{$currently['windSpeed']}</span><span class='data4'>{$currently['visibility']}</span><span class='data5'>{$currently['cloudCover']}</span><span class='data6'>{$currently['ozone']}</span></b></div><br><br><table class='table1'><tr class='table2'><td class='table3 table2'>Date</td><td class='table3 table2'>Status</td><td class='table3 table2'>Summary</td><td class='table3 table2'>TemperatureHigh</td><td class='table3 table2'>TemperatureLow</td><td class='table3 table2'>Wind Speed</td></tr>";
		$i=1;
		$myArray = array();
		foreach ($daily as $row) {
		$html.="
		<tr class='table2'><td class='table3 table2'>".date('Y-m-d', $row['time'])."</td><td class='table3 table2'> <img src='climate/{$row['icon']}.png' width='30px' height='30px'></td><td class='table3 table2'><div id='{$i}' onclick = 'display123(this)'>{$row['summary']}</div></td><td class='table3 table2'>{$row['temperatureHigh']}</td><td class='table3 table2'>{$row['temperatureLow']}</td><td class='table3 table2'>{$row['windSpeed']}</td></tr>";
		$i+=1;
		$time = $row['time'];
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
		$mytemp= array();
		$hourly = $darksky_obj3['hourly'];
		$htemp = $hourly['data'];
		for($htemp as $row1)
		{
			array_push($mytemp, $row1['temperature']);
		}

		$currently = $darksky_obj3['currently'];
		$p=$currently['precipIntensity'];
		if($p<=0.01){
			$precip = "None";
		}
		else if($p>0.001 && $p<=0.015){
			$precip = "Very Light";
		}
		else if($p>0.015 && $p<=0.05){
			$precip = "Light";
		}
		else if($p>0.05 && $p<=0.1){
			$precip = "Moderate";
		}
		else{
			$precip = "Heavy";
		}

		$cor= $currently['precipProbability']*100;
		$h = $currently['humidity']*100;

		$detail= "<center class='c11'><b>Daily Weather Detail</b></center><br><div class='d11'><b><br><br><br><span class='line11'>".$currently['summary']."</span><br><span class='line2_1'>".intval($currently['temperature']) ."</span><span class='line2_2'>F</span><img class='i1' src='temp.png' height='15px' width='15px'><img class='i2' src='Detail_icons/".$currently['icon'].".png' height='200px' width='200px'><br><div class='data11'><span class='precip'>Precipitation: ".$precip."</span><br><span class='cor'>Chance of Rain: ".$cor."</span><br><span class='ws'>Wind Speed: ".$currently['windSpeed']." mph</span><br><span class='hum'>Humidity:".$h."</span><br><span class='vis'>Visibility:".$currently['visibility']." mi</span><br><span class='ss'>Sunset/Sunrise:</span></div><div id='second'></div><center><img src='down.png' height="30px" width="30px"></center></b></div>";
	array_push($myArray, $detail);
}
	$html.="

	</table>

</div>";
	}

?>

<html>
<head>
	<title>
		297ea87061d9a1983ffc384495799fc4
	</title>
	<link rel="stylesheet" href="temp1css.css">
	<link rel="stylesheet" href="temp2css.css">
	<link rel="stylesheet" href="temp3css.css">
	<link rel="stylesheet" href="temp4css.css">
</head>

<body>

<script>

function validateForm() {
  var x = document.forms["form1"]["t1"].value;
  var y = document.forms["form1"]["t2"].value;
  var z = document.forms["form1"]["t3"].value;
  var check1 = document.getElementById("checkbox1").checked;
  var count = -1;

  if(check1 == true)
  {
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","http://ip-api.com/json",false);
	xmlhttp.send();
	response = xmlhttp.responseText;
	jsonObj= JSON.parse(response);
	document.getElementById("lat1").setAttribute("value",jsonObj.lat) ;
	document.getElementById("long1").setAttribute("value", jsonObj.lon);
	document.getElementById("city1").setAttribute("value", jsonObj.city);
	document.getElementById("form1").submit();
  }
  else{
	  if (x == "") {
	    alert("Street must be filled out");
	    count =0;
	  }
	  if (y == "") {
	    alert("City must be filled out");
	    count =0;
	  }
	  if (z == "State") {
	    alert("State must be filled out");
	    count = 0;
	  }
	  if(count<0)
	  {
	  	document.getElementById("form1").submit();
	  }
	}
}

function disable(bEnable){
	document.getElementById("t1").value = "";
	document.getElementById("t2").value = "";
	document.getElementById("t3").value = "State";	
	document.getElementById("t1").disabled = bEnable;
	document.getElementById("t2").disabled = bEnable;
	document.getElementById("t3").disabled = bEnable;

}

function clear123(){
	document.getElementById("t1").disabled = false;
	document.getElementById("t2").disabled = false;
	document.getElementById("t3").disabled = false;
	document.getElementById("checkbox1").checked = false;
	document.getElementById("first").innerHTML = "";
	document.getElementById("t1").value = "";
	document.getElementById("t2").value = "";
	document.getElementById("t3").value = "State";
}

function display123(active){
	var t = active.getAttribute("id");
	if(t==1){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[0]:'';?>";
	}
	else if(t==2){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[1]:'';?>";
	}
	else if(t==3){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[2]:'';?>";
	}
	else if(t==4){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[3]:'';?>";
	}
	else if(t==5){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[4]:'';?>";
	}
	else if(t==6){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[5]:'';?>";
	}
	else if(t==7){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[6]:'';?>";
	}
	else if(t==8){
		document.getElementById('first').innerHTML = "<?php 
		echo isset($myArray)?$myArray[7]:'';?>";
	}
}


</script>

	<br>
	<div class = "p1">
		<form name="form1" action="" id="form1" method="POST">
			<center class="c1 w1"><i>Weather Search</i></center>
			<input type="hidden" id="lat1" name="lat1" value="">
			<input type="hidden" id="long1" name="long1">
			<input type="hidden" id="city1" name="city1">
			<table class="w1" border="0">
				<tr>
					<td>Street </td>
					<td><input class="t1" type="text" id="t1" name="t1" width="200px" 
						value="<?php if (isset($_POST['t1'])) echo $_POST['t1']; ?>" /></td>
				</tr>
				<tr></tr> <tr></tr>
				<tr>
					<td>City</td>
					<td><input class="t1" id="t2" type="text" name="t2" 
						value="<?php if (isset($_POST['t2'])) echo $_POST['t2']; ?>" /></td>			
				</tr>
				<tr></tr> <tr></tr> <tr></tr> <tr></tr>
				<tr>
					<td>State</td>
					<td><select name="t3" id = "t3">
						<option default>State</option>
						<option value="AL">Alabama</option>
			  			<option value="AK">Alaska</option>
			  			<option value="AZ">Arizona</option>
			  			<option value="AR">Arkansas</option>
			  			<option value="CA">California</option>
						<option value="CO">Colorado</option>
			  			<option value="CT">Connecticut</option>
			  			<option value="DE">Delaware</option>
			  			<option value="DC">District Of Columbia</option>
			  			<option value="FL">Florida</option>
						<option value="GA">Georgia</option>
			  			<option value="HI">Hawaii</option>
			  			<option value="ID">Idaho</option>
			  			<option value="IL">Illinois</option>
			  			<option value="IN">Indiana</option>
			  			<option value="IA">Iowa</option>
			  			<option value="KS">Kansas</option>
			  			<option value="KY">Kentucky</option>
			  			<option value="LA">Louisiana</option>
			  			<option value="ME">Maine</option>
			  			<option value="MD">Maryland</option>
			  			<option value="MA">Massachusetts</option>
			  			<option value="MI">Michigan</option>
			  			<option value="MN">Minnesota</option>
			  			<option value="MS">Mississippi</option>
			  			<option value="MO">Missouri</option>
			  			<option value="MT">Montana</option>
			  			<option value="NE">Nebraska</option>
			  			<option value="NV">Nevada</option>
			  			<option value="NH">New Hampshire</option>
			  			<option value="NJ">New Jersey</option>
			  			<option value="NM">New Mexico</option>
			  			<option value="NY">New York</option>
			  			<option value="NC">North Carolina</option>
			  			<option value="ND">North Dakota</option>
			  			<option value="OH">Ohio</option>
			  			<option value="OK">Oklahoma</option>
			  			<option value="OR">Oregon</option>
			  			<option value="PA">Pennsylvania</option>
			  			<option value="RI">Rhode Island</option>
			  			<option value="SC">South Carolina</option>
			  			<option value="SD">South Dakota</option>
			  			<option value="TN">Tennessee</option>
			  			<option value="TX">Texas</option>
			  			<option value="UT">Utah</option>
			  			<option value="VT">Vermont</option>
			  			<option value="VA">Virginia</option>
			  			<option value="WA">Washington</option>
			  			<option value="WV">West Virginia</option>
			  			<option value="WI">Wisconsin</option>
			  			<option value="WY">Wyoming</option>
					</select></td>
			</table>
			<div class="verticalline"></div>

			<div class="w1 checkbox">
				<input class="check1" type="checkbox" name="current" id="checkbox1" value="current" onclick="disable(this.checked)">Current Location
			</div>
			<input class="btn1" type="button" onclick="validateForm()" value="search">
  			<input class="btn2" type="button" onclick="clear123()" value="clear">
		</form>
	</div>

	<div>
		<?php 
			if(isset($html))
			{
				echo $html;
			}
		?>
	</div>

</body>
</html>