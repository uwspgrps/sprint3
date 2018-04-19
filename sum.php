<?php

	if (isset($_POST["data"])) {
	
			$input = json_decode($_POST["data"]);
			$sum = 0;
			 foreach ($input as $num){
				 $sum+=$num;
			 }
			//$sum = $input->num1 + $input->num2;
			
			
			print json_encode(array("result"=>$sum));
			
		} else {
			
			print json_encode(array("result"=>"no data found"));
	}



?>

