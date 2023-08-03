<?php
if(empty($_POST['title']) || empty($_POST['category']) || empty($_POST['description']) || empty($_POST['source'])){
    $errMsg = 'Заполните все поля формы!';
}else{
    $title = $news->escape($_POST['title']);
    $category = $_POST['category'];
    $description = $news->escape($_POST['description']);
    $source = $news->escape($_POST['source']);
    $result = $news->saveNews($title, $category, $description, $source);
    if($result){
        header("Location: news.php");
        exit;
    }else{
        $errMsg = 'Произошла ошибка при добавлении новости';
    }
}




