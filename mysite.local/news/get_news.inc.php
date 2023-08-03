<?php
$items = $news->getNews();
if(!is_array($items)){
    $errMsg = 'Произошла ошибка при выводе новостной ленты';
}else{
    foreach ($items as $item){
        $dt = date("d-m-Y H:i:s", $item['datetime']);
        $desc = nl2br($item['description']);
        echo <<<ITEM
        <h3>{$item['title']}</h3>
        <p>
            {$desc}<br>{$item['category']} @ $dt
        </p>
        <p align='right'>
            <a href='news.php?del={$item['id']}'>delete</a>
        </p>
        ITEM;
    }
}