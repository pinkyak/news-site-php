<?php
$client = new SoapClient("http://localhost:8888/news-site/mysite.local/soap/news.wsdl");
print_r( $client->__getFunctions() );
// try{
//     // Сколько новостей всего?
//     $result = $client->getNewsCount();
//     echo "<p>Всего новостей: $result</p>";
//     // Сколько новостей в категории Политика? $result = $client->getNewsCountByCat(1);
//     echo "<p>Всего новостей в категории Политика:
//         $result</p>";
//           // Покажем конкретную новость
//           $result = $client->getNewsById(1);
//           $news = unserialize(base64_decode($result));
//           var_dump($news);
//     }catch(SoapFault $e){
//     echo 'Операция '.$e->faultcode.' вернула ошибку: '.$e->
//         getMessage();
// }
