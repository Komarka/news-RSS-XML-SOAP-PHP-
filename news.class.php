<?php
require_once "interface_news.class.php";
class News implements I_news{
	const DB_NAME="news.db";
	const RSS_NAME="rss.xml";
	const RSS_TITTLE="Новостная лента";
	const RSS_LINK="http://site/news.php";

	private $_db=null;
function __get($n){
if($n=="_db"){
	return $this->_db;
}else{
	throw new Exception("Wrong property of the object");
}
}

function __construct(){
	$this->_db=new SQLite3(self::DB_NAME);
	if(filesize(self::DB_NAME)==0){
		$sql="CREATE TABLE message(id INTEGER PRIMARY KEY AUTOINCREMENT,tittle TEXT,category INTEGER,description TEXT,source TEXT,datetime INTEGER)";
		$this->_db->exec($sql) or die($this->_db->lastErrorCode());
	$sql="CREATE TABLE category(id INTEGER,name TEXT)";
		$this->_db->exec($sql) or die($this->_db->lastErrorCode());
$sql="INSERT INTO category(id, name)
SELECT 1 as id, 'Политика' as name
UNION SELECT 2 as id, 'Культура' as name
UNION SELECT 3 as id, 'Спорт' as name ";
		$this->_db->exec($sql) or die($this->_db->lastErrorCode());
	}
}
function createRss(){
$dom=new DomDocument("1.0","utf-8");
$dom->formatOutput=true;
$dom->preserveWhiteSpace=false;
$rss=$dom->createElement("rss");
$dom->appendChild($rss);
$version=$dom->createAttribute("version");
$version->value="2.0";
$rss->appendChild($version);
$channel=$dom->createElement("channel");
$tittle=$dom->createElement("tittle",self::RSS_TITTLE);
$link=$dom->createElement("link",self::RSS_LINK);
$channel->appendChild($tittle);
$channel->appendChild($link);
$rss->appendChild($channel);
$lenta=$this->getNews();
if(!$lenta)
	return false;
foreach ($lenta as $news) {
	$item=$dom->createElement("item");
	$tittle=$dom->createElement("tittle",$news['tittle']);
	$category=$dom->createElement("category",$news['category']);
	$description=$dom->createElement("description",$news['description']);
	$link=$dom->createElement("link","#");
	$dt=date("r",$news['datetime']);
	$pubDate=$dom->createElement("pubDate",$dt);
	$item->appendChild($tittle);
	$item->appendChild($category);
	$item->appendChild($description);
	$item->appendChild($link);
	$item->appendChild($pubDate);
	$channel->appendChild($item);
	
	
}

$dom->save(self::RSS_NAME);

}
 protected function dbtoarray($data){
	$array=[];
	while($row=$data->fetchArray())
		$array[]=$row;
	return $array;
}
function __destruct(){
	unset($this->_db);
}
function saveNews($title,$category,$description,$source){
	$dt=time();
	$sql="INSERT INTO message(tittle,category,description,source,datetime)VALUES('$title','$category','$description','$source',$dt)";
	$res= $this->_db->exec($sql);
	if(!$res)
		return false;
	$this->createRss();
	return true;
	

}
function getNews(){
	$sql="SELECT message.id as id,tittle,category.name as category,description,source,datetime FROM message,category WHERE category.id=message.category ORDER BY message.id DESC";
	$res=$this->_db->query($sql);
	if(!$res)return false;
	return $this->dbtoarray($res);
}
function deleteNews($id){
	$sql=" DELETE  FROM message  WHERE id='$id'";
	return $this->_db->exec($sql);
}
function clearStr($data){
	$data=strip_tags($data);
	return $this->_db->escapeString($data);
}
function clearInt($data){
	return abs((int)$data);
}
}


?>
