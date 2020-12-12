<?php

function getPolygon($connection, $id){
	
	$query = "Select AsText(DiaGioi) as polygon From diagioihanhchinh Where IDQuanHuyen =".$id;
	/*$stmt = mysqli_prepare($connection, $query);
	$idPolygon = $id;
	mysqli_stmt_bind_param($stmt, 'i', $idPolygon);
	mysqli_stmt_execute($stmt);
	$rs = mysqli_stmt_get_result($stmt);*/
	$rs = mysqli_query($connection, $query);
	if (!$rs) { // add this check.
    	die('Invalid query: ' . mysqli_error($connection));
	}
	else{
		//echo "polygonPoints = [";
		// echo "[";
		$st1 = "";
		while($row=mysqli_fetch_array($rs)){
			$st = $row["polygon"];
			$st = str_replace("POLYGON((", "", $st);
			$st = str_replace("))","",$st);
			$array = explode(",", $st);
			$arrlength = count($array);
			
			for($x = 0; $x < $arrlength; $x++){
				$arr = explode(" ", $array[$x]);
				$length = count($arr);
				$st1 = $st1 . "[";
				$lat = "";
				$lng = "";
				for($y = 0; $y < $length; $y++){
					if($y == 0){
						$lng = $arr[$y];
						//$st1 = $st1 .$arr[$y]. ",";
					}
					else{
						//$st1 = $st1 .$arr[$y];
						$lat = $arr[$y];
					}
					
				}
				$st1 = $st1 . $lng . "," . $lat;	
				//echo $array[$x];
				//echo "<br>";
				$st1 = $st1 . "],";
			}
						
		}
		$st1 = substr ($st1, 0, strlen($st1) - 1);
		// echo $st1;
		// echo "]";
		//echo "];";
		mysqli_free_result($rs);
		return $st1;
	}
	
}	
?>