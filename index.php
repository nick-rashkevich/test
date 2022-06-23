<?php
$array = [
    ['address' => 'г. Минск, ул. Восточнаяя, д. 33', 'date_from' => '31-12-2002', 'date_to' => '31-12-2005'],
    ['address' => 'г. Минск, ул. Восточнаяя, д. 34', 'date_from' => '31-12-2005', 'date_to' => '31-12-2006'],
    ['address' => 'г. Минск, ул. Восточнаяя, д. 34', 'date_from' => '31-12-2006', 'date_to' => '31-12-2008'],
    ['address' => 'г. Минск, ул. Тихая, д. 33',      'date_from' => '31-12-2000', 'date_to' => '31-12-2002'],
    ['address' => 'г. Минск, ул. Ленина, д. 33',     'date_from' => '31-12-2008', 'date_to' => '31-12-2010'],
    ['address' => 'г. Минск, ул. Ленина, д. 33',     'date_from' => '31-12-2010', 'date_to' => '31-12-2011'],
    ['address' => 'г. Минск, ул. Тихая, д. 33',      'date_from' => '31-12-2012'],
    ['address' => 'г. Минск, ул. Ленина, д. 33',     'date_from' => '31-12-2011', 'date_to' => '31-12-2012'],
];

function getData($array){

//  проверка на наличие 'date_to' + преобразование даты в timestamp
    foreach ($array as $key => $value)
    {
        if (!array_key_exists('date_to', $value))
        {
            $value['date_to'] = date('d-m-Y', time());

            $array[$key] = $value;
        }
        $array[$key]['date_to'] = strtotime($array[$key]['date_to']);
        $array[$key]['date_from'] = strtotime($array[$key]['date_from']);
    }


//    сортируем массив по timestamp ======================================
    function sorter($key)
    {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }
    usort($array, sorter('date_from'));


//    группировка одинаковых адресов с сохранением дат ===================
    for ($i = 0, $j = $i -1; $i < count($array); $i++, $j++)
    {
        if($i === 0 ) continue;

        if (($array[$i]['address'] === $array[$j]['address']) &&
            ($array[$i]['date_from'] === $array[$j]['date_to']))
        {
            $array[$i]['date_from'] = $array[$j]['date_from'];
            array_splice($array, $j, 1);
            --$i; --$j;
        }
    }


//    преобразование к строке =============================================
    $str = '';
    foreach ($array as $value)
    {
        $str = $str.
            date('d.m.Y', $value['date_from']).'/'.
            date('d.m.Y', $value['date_to']).': '.
            $value['address'].'<br>';
    }
    return $str;
}

echo getData($array);