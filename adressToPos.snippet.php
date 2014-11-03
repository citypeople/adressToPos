<?php
/*
name: adressToPos
Определение координат по адресу.
Modx Revo.
YandexMaps.
Сниппет позволяет на основе введенных данных определить координаты адреса на YandexMaps и записать их в TV параметр.
Пример вызова снипета. [[!adressToPos? &addTv=maps $adress=[[*Adress]] &city=[[*City]]]]
Параметры: &addTv - имя TV параметра для записи координат, по умолчанию используется maps $adress - адрес на карте, координаты которого необходимо определить &city - город для более точного определения координат, параметр не обязательный т.к. город можно передавать в параметре $adress

hi@citypeople.ru
citypeople.ru
*/
//Определяем id рессурса
$id = $modx->resource->id;
$page = $modx->getObject("modResource", $id);

//Получаем параметры из сниппета
$city = (isset($city)) ? $city : "Пермь";
$adress = (isset($adress)) ? $adress : "";
$addTv = (isset($addTv)) ? $addTv : 'maps';
// Составляем полный адрес
$output = $city;
$output .= $adress; 



$params = array(
    'geocode' => $output,	// адрес
    'format'  => 'json',	// формат ответа
    'results' => 1,			// количество выводимых результатов
);
$response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));
 
if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
{
    $mapPoint = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
}
else
{
    $mapPoint = "Ничего не найдено";
}
// Записываем данные в TV параметр
if (!$page->setTVValue($addTv, $mapPoint)) {
$modx->log(xPDO::LOG_LEVEL_ERROR, 'Произошла ошибка при сохранении ТВ');
}
