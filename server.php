<?php


$link = mysqli_connect("localhost", "root", "");
$sql = 'USE river_task';
$result = mysqli_query($link, $sql);

$sql = 'DELETE FROM crossings';
$result = mysqli_query($link, $sql);

$sql = 'ALTER TABLE crossings AUTO_INCREMENT = 0';
$result = mysqli_query($link, $sql);

$coast_1 = [[]];
$coast_2 = [[]];
$boat = [];
$error_id = [];
$coast_num = [];

for ($i = 0; $i < $_POST['count_crossings']; $i++) {
	for ($j = 1; $j < 4; $j++) {
		$coast_1[$i][$j] = 0;
		$coast_2[$i][$j] = 0;
	}
	$boat[$i] = ($_POST['cargo_'.($i+1)] - 1 );
}

for ($j = 1; $j < 4; $j++) {
	$coast_1[0][$j] = 1;
}

for ($i = 0; $i < $_POST['count_crossings']; $i++) {
	if ($boat[$i] > 0) {
		$id_boat = $boat[$i];
		if ($i % 2 == 0) {
			$coast_num[$i] = 1;
			if ($coast_1[$i][$id_boat] == 1) {
				$coast_1[$i][$id_boat] = 0;
				$coast_2[$i][$id_boat] = 1;	
				$error_id[$i] = 0;	
			} else if ($i > 0) {
				$error_id[$i] = $id_boat+1;
			}
			

		} else {		
			if ($coast_2[$i][$id_boat] == 1) {
				$coast_1[$i][$id_boat] = 1;
				$coast_2[$i][$id_boat] = 0;		
				$error_id[$i] = 0;		
			} else if ($i > 0) {
				$error_id[$i] = $id_boat+1;
			}			
		}	
	} else $error_id[$i] = 0;
	for ($j = 1; $j < 4; $j++) {
		$coast_1[$i+1][$j] = $coast_1[$i][$j];
		$coast_2[$i+1][$j] = $coast_2[$i][$j];
		
		if ($i % 2 == 0) {
			if ($coast_1[$i][1] > 0 && $coast_1[$i][2] > 0) {
				$error_id[$i] = 11;
			}
			else if ($coast_1[$i][2] > 0 && $coast_1[$i][3] > 0) {
				$error_id[$i] = 12;
			}			
		} else {
			if ($coast_2[$i][1] > 0 && $coast_2[$i][2] > 0) {
				$error_id[$i] = 11;
			}
			else if ($coast_2[$i][2] > 0 && $coast_2[$i][3] > 0) {
				$error_id[$i] = 12;
			}			
		}
	}
	if ($i % 2 == 0) $coast_num[$i] = 1;
	else $coast_num[$i] = 2;
	
}


for ($i = 0; $i < $_POST['count_crossings']; $i++) {
	
	if ($i % 2 == 0) {
		$is_right = 1;
	} else {
		$is_right = 0;
	}
	for ($j = 1; $j < 4; $j++) {

		$sql = 'INSERT INTO crossings (num_crossing, boat, is_right, id_cargo, coast_1, coast_2, id_error, num_coast) VALUES ('.($i+1).', '.($boat[$i]+1).', '.$is_right.', '.($j+1).', '.$coast_1[$i][$j].', '.$coast_2[$i][$j].', '.$error_id[$i].', '.$coast_num[$i].')';
		$result = mysqli_query($link, $sql);
		
	}
	
}

$name_cargo = [];

$sql = 'SELECT id_cargo, rus_name FROM cargos';
$result = mysqli_query($link, $sql);


while ($row = mysqli_fetch_array($result)) {
	$name_cargo[$row['id_cargo']] = $row['rus_name'];
}


$data_coast_1 = [];
$data_coast_2 = [];
$data_boat = [];
$data_error = [];
$num_error = [];
$main_error = "";
$total_report = "";

$sql = 'SELECT * FROM crossings';

$result = mysqli_query($link, $sql);

$i = -1;
$j = 0;
$cur_crossing = 0;
$first_error = false;
$last_coast = 0;


while ($row = mysqli_fetch_array($result)) {
	if ($cur_crossing < $row['num_crossing']) {
		$cur_crossing = $row['num_crossing'];		
		$i++;
		$data_boat[$i] = "<li>".$name_cargo[$row['boat']]."</li>";
		if ($row['id_error'] > 0 && $first_error == false) {
			
			if ($row['id_error'] == 11) {
				$data_error[$i] = 'На берегу '.$row['num_coast'].' волк съел козу!';	
			} else if ($row['id_error'] == 12) {
				$data_error[$i] = 'На берегу '.$row['num_coast'].' коза съела капусту!';
			} else {
				$data_error[$i] = 'На берегу '.$row['num_coast'].' нет груза "'.$name_cargo[$row['boat']].'"!';				
			}
			
			if ($first_error == false) {
				$first_error = true;
				$main_error = $data_error[$i];
			}
			$num_error[$i] = $row['id_error'];
		} else if ($first_error == false) {
			if ($row['num_coast'] == 1) $coast_id = 2;
			else $coast_id = 1;
				
			if ($row['boat'] > 1) {
				$data_error[$i] = 'Успешно перевезен груз "'.$name_cargo[$row['boat']].'" на берег '.$coast_id.'!';
			} else {
				$data_error[$i] = 'Успешно!';
			}
			$num_error[$i] = $row['id_error'];
		} else $num_error[$i] = -1;
		
		$j = 0;
	}
	if ($row['coast_1'] > 0) {
		$data_coast_1[$i] .= "<li>".$name_cargo[$row['id_cargo']]."</li>";
	}
	if ($row['coast_2'] > 0) {
		$data_coast_2[$i] .= "<li>".$name_cargo[$row['id_cargo']]."</li>";
	}
	if (($i+1) == $_POST['count_crossings']) {
		if ($row['coast_2'] > 0) $last_coast++; 
	}
	
	$j++;
	

}

if ($last_coast == 3 && $first_error == false) {
	$total_report = "Задача выполнена! На берег 2 перевезены все - волк, коза и капуста!";
	$task_completed = true;
} else {
	$total_report = "Задача НЕ выполнена! На берег 2 НЕ перевезены все - волк, коза и капуста!";
	$task_completed = false;
}

mysqli_close($link);

 
$result = array(
	'coast_1' => $data_coast_1,
	'coast_2' => $data_coast_2,
	'boat' => $data_boat,
	'error' => $data_error,
	'error_id' => $num_error,
	'main_report' => $main_error,
	'total_report' => $total_report,
	'completed' => $task_completed,
);
 
echo json_encode($result);



?>