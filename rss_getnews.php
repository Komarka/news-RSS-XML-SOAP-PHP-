<?php
const RSS_URL="http://site/rss.xml";
const FILE_NAME="news.xml";
function download($url,$filename){
	$file=file_get_contents($url);
	if($file)file_put_contents($filename, $file);
}
if(!is_file($filename)){
	download(RSS_URL,FILE_NAME);
}
?>
<!DOCTYPE html>

<html>
<head>
	<title>Новостная лента</title>
	<meta charset="utf-8" />
</head>
<body>

<h1>Последние новости</h1>
<?php
$xml=simplexml_load_file(FILE_NAME);
foreach ($xml->channel->item as $item) {
	echo "<h2>".$item->tittle."</h2>";
	echo "<p>".$item->description."</p>";
	echo "<p>".$item->category."</p>";
	echo "<p>".$item->pubDate."</p>";
	echo "<p><a href=".$item->link.">Читать дальше...</a></p>";
	download(RSS_URL,FILE_NAME);
}
?>
</body>
</html>
