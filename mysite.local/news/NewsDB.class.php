<?php
require_once "INewsDB.class.php";
class NewsDB implements INewsDB{
    const NAME_DB = 'news.db';
    const ERR_PROPERTY = 'wrong property name';
    const RSS_NAME = 'rss.xml';
    const RSS_TITLE = 'Последние новости';
    const RSS_LINK = 'http://localhost:8888/news-site/mysite.local/news/news.php';
    private $_db;

    public function __construct(){
        $this->_db = new SQLite3(self::NAME_DB);
        if(filesize(self::NAME_DB) == 0){
            $sql = "CREATE TABLE msgs(
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        title TEXT,
                        category INTEGER,
                        description TEXT,
                        source TEXT,
                        datetime INTEGER
            )";
            $this->_db->exec($sql) or die($this->db->lastErrorMsg());
            $sql = "CREATE TABLE category(
                        id INTEGER,
                        name TEXT
            )";
            $this->_db->exec($sql) or die($this->_db->lastErrorMsg());
            $sql = "INSERT INTO category(id, name)
                        SELECT 1 as id, 'Политика' as name
                        UNION SELECT 2 as id, 'Культура' as name
                        UNION SELECT 3 as id, 'Спорт' as name ";
            $this->_db->exec($sql) or die($this->_db->lastErrorMsg());
        }
    }
    public function __distruct(){
        unset($this->_db);
    }
    public function __get($name){
        if($name == 'db'){
            return $this->_db;
        }
        throw new Exception(self::ERR_PROPERTY);
    }
    public function __set($name , $value){
        throw new Exception(self::ERR_PROPERTY);
    }
    function saveNews($title, $category, $description, $source){
        $dt = time();
        $sql = "INSERT INTO msgs(title, category, description, source, datetime) VALUES ('$title', $category, '$description', '$source', $dt)";
        $result =  $this->_db->exec($sql) or die($this->_db->lastErrorMsg());
        if(!$result)
            return false;
        $this->createRss();
        return true;
    }

    function escape($data){
        return $this->_db->escapeString(trim(strip_tags($data)));
    }

    function db2Arr($data){
        $arr=[];
        while ($row = $data->fetchArray(SQLITE3_ASSOC))
            $arr[] = $row;
        return $arr;
    }
    function getNews(){
        $sql = "SELECT msgs.id as id, title, category.name as category,
        description, source, datetime
            FROM msgs, category
            WHERE category.id = msgs.category
            ORDER BY msgs.id DESC";
        $items = $this->_db->query($sql);
        return $this->db2Arr($items);
    }
    function deleteNews($id){
        $sql = "DELETE FROM msgs WHERE id=$id";
        return $this->_db->exec($sql);
    }
    private function createRss(){
        $dom = new DOMDocument("1.0", "utf-8");
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $rss = $dom->createElement('rss');
        $version = $dom->createAttribute("version"); 
        $version->value = '2.0'; 
        $rss->appendChild($version);
        $dom->appendChild($rss);
        $chanel = $dom->createElement('chanel');
        $rss->appendChild($chanel);
        $title = $dom->createElement('title', self::RSS_TITLE);
        $link = $dom->createElement('link', self::RSS_LINK);
        $chanel->appendChild($title);
        $chanel->appendChild($link); 

        $lenta = $this->getNews();
        if(!$lenta) return false;
        foreach($lenta as $news){
            $item = $dom->createElement('item');
            $title = $dom->createElement('title', $news['title']);
            $category = $dom->createElement('category', $news['category']);
            $description = $dom->createElement('description');
            $cdata = $dom->createCDATASection($news['description']);
            $description->appendChild($cdata);
            $linktext = self::RSS_LINK ."?id=".$news['id'];
            $link = $dom->createElement('link', $linktext); 
            $dt = date('r', $news['datetime']);
            $pubDate = $dom->createElement('pubDete', $dt); 
            $item->appendChild($title);
            $item->appendChild($category);
            $item->appendChild($description);
            $item->appendChild($link);
            $item->appendChild($pubDate);
            $chanel->appendChild($item);
        }
        $dom->save(self::RSS_NAME);
    }
}