<?php
$t=$news->clearStr($_POST['title']);
$d=$news->clearStr($_POST['description']);
$s=$news->clearStr($_POST['source']);
$c=$news->clearInt($_POST['category']);
if(empty($t)or empty($d)){
	$error_show="Fill all the fields";
}else{
	if(!$news->saveNews($t,$c,$d,$s)){
		$error_show="Error while saving";
	}
	else{
		header("Location:news.php");
		exit;
	}
}
?>
