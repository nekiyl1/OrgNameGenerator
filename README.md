# OrgNameGenerator
Генератор названий организаций с помощью цепей Маркова на PHP

Так же можно отдельно использовать класс MarkovNameGenerator следующим образом:

```
<?php
	require 'MarkovNameGenerator.php';
	$NameSamples = array("Василий", "Петр", "Енисей");
	$mng = new MarkovNameGenerator($NameSamples, 1, 4);
	for($i = 0; $i < 10; $i++)
		echo $mng->GetNextName()."</br>";
```
