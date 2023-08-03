<?php
const RSS_URL = 'http://localhost:8888/news-site/mysite.local/news/rss.xml';
const FILE_NAME = 'news.xml';
const RSS_TTL = 3600;

function download($url , $filename){
	$file = file_get_contents($url);
	if($file) file_put_contents($filename , $file);
}
if(!is_file(FILE_NAME)){
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
$sxml = simplexml_load_file(FILE_NAME);
foreach($sxml->chanel->item as $item){
	echo <<<ITEM
		<h3>{$item->title}</h3>
		category: {$item->category}
		<p>{$item->description}</p>
		pubDate: {$item->pubDete}
		<p align='right'><a href="{$item->link}">читать далее...</a></p>
	ITEM;
}
if(time() > filemtime(FILE_NAME) +RSS_TTL){
	download(RSS_URL,FILE_NAME); 
} 
?>
</body>
</html>