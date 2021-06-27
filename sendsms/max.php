<?php

$a = [-12,-6,-9,-89];

$c= $a[0];

for($i=1;$i<count($a);$i++){

	if($c<$a[$i]){

		$c= $a[$i];
	}
}

echo $c;

?>