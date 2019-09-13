<?php
	require 'MarkovNameGenerator.php';
	$NameSamples = array();
	$fileError = $_FILES['file']['error'];
	if ($_FILES == null) {
		$handle = @fopen("list.txt", "r");
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) 
				array_push($NameSamples, $buffer);
			if (!feof($handle)) 
				echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
			fclose($handle);
		}
	}
	else
	{
		$fp = fopen($_FILES['file']['tmp_name'], 'rb');
		while (($line = fgets($fp)) !== false)
			array_push($NameSamples, $line);
	}
	if (count($NameSamples) > 0) {
		$mng = new MarkovNameGenerator($NameSamples, 1, 4);
		$result;
		$count = 1;
		if($_POST['count'] != "")
			$count = $_POST['count'];
		for($i = 0; $i < $count; $i++) 
			$result .= $mng->GetNextName()."</br>";
		echo json_encode(array('result' => $result));
	}