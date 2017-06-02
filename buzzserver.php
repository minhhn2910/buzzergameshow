<?php
// get the q parameter from URL
$q = $_REQUEST["q"];

$hint = "";
if ($q === "start" ){
	echo "started";
	$file = fopen("start.txt", "w+"); 
	$stringToWrite = "start".PHP_EOL;
	fwrite($file,$stringToWrite);
	fclose($file);	
}
else if ($q === "getwinner" ){
        $fo = fopen("flocktest.txt", 'r'); 
        $cts = fgets($fo); 
        fclose($fo); 
		echo $cts;
		
	
}
else if($q === "status" ){
//	echo "status";
	$status ="";
	if(file_exists("start.txt")){
		$startFile = fopen("start.txt", "r"); 
		$status = fgets($startFile);
		//echo " ---".$status."---";
		fclose($startFile);
	}
	if(empty($status)){
		echo 1;
		return;
	}else{
		echo 0;
		return;
	}
}
else if ($q !== "resetall") {
//	echo "submitted ";
	$status ="";
	if(file_exists("start.txt")){
		$startFile = fopen("start.txt", "r"); 
		$status = fgets($startFile);
		//echo " ---".$status."---";
		fclose($startFile);
	}
	if(empty($status)){
		echo "Not ready yet " ;
		return;
	}
	
	
// Output "no suggestion" if no hint was found or output correct values 
//$hint === "" ? "no suggestion" : $hint;
/*
$file = fopen("test.txt","a+");
$firstTeam = fgets($file);
echo $q;
echo PHP_EOL;
echo $firstTeam;
if (!empty($firstTeam)){
	if(strcmp($firstTeam,$q)==0){
		echo "out";
		$hint = " out";
	}
	else{
		echo "in";
		$hint = " in";
	}
	return;
}
echo "imhere";

$stringToWrite = $q.PHP_EOL;
fwrite($file,$stringToWrite);
fclose($file);
*/
//test flock race cond
$file = fopen("flocktest.txt", "a+"); 
if (flock($file, LOCK_EX|LOCK_NB)) { 
    //print "No problems, I got the lock, now I'm going to sit on it.";
	$firstTeam = fgets($file);
	//echo $q;
	//echo PHP_EOL;
	//echo $firstTeam;
	
	if (!empty($firstTeam)){
		$teamInt = intval($firstTeam);
		$winnerInt = intval($q);
		//echo strcmp($firstTeam,$q);
		if($teamInt != 0  && $winnerInt!=0 && $teamInt==$winnerInt){
			echo "in";
		}
		else{
			echo "out";
		}
		return;
	}else{
		echo "in";
		$stringToWrite = $q.PHP_EOL;
		fwrite($file,$stringToWrite);	
	}
} else { 
    //print "Didn't quite get the lock. Quitting now. Good night."; 
	echo "Not ready yet ";
} 
fclose($file); 

}
else{ //q===resetall
	echo "reset all";
	$file = fopen("flocktest.txt", "w+"); 
	flock($file,LOCK_UN);
	fclose($file);
	$fileStart = fopen("start.txt", "w+"); 
	fclose($fileStart);
}
?>