<?php
$id = $modx->resource->id;
$page = $modx->getObject("modResource", $id);
$city = (isset($city)) ? $city : "Пермь";
$adress = (isset($adress)) ? $adress : "";
$output = $city;
$output .= $adress; 
$addTv = (isset($addTv)) ? $addTv : 'maps';


$params = array(
    'geocode' => $output, // адрес
    'format'  => 'json',                          // формат ответа
    'results' => 1,                               // количество выводимых результатов
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

if (!$page->setTVValue($addTv, $mapPoint)) {
$modx->log(xPDO::LOG_LEVEL_ERROR, 'Произошла ошибка при сохранении ТВ');
}
