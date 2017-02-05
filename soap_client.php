<?
$client=new SoapClient("http://site/news.wsdl");
try{
$result_1=$client->getNewsCount();
echo "<h1>".$result_1."</h1>";
$result_2=$client->getNewsCountByCat(1);
echo "<h2>".$result_2."</h2>";
$result_3=$client->getNewsById(1);
$news=unserialize(base64_decode($result_3));
var_dump($news);
}catch(SoapFault $e){
	echo "Mistake is with ".$e->faultCode." and ".$e->getMessage();
}
?>
