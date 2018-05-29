<?php
/**
 * Created by PhpStorm.
 * User: Аня
 * Date: 29.05.2018
 * Time: 13:30
 */

$reg = '~(Nokian|BFGoodrich|Pirelli|Toyo|Continental|Hankook|Mitas)\s+(.*?)\s+(\d+)/(\d+)\s*([a-z]+)'
    .'(\d+)C?\s+'
    .'([\/\d]+)([a-z]+)\s*([a-z]+)?\s*(RunFlat|Run Flat|ROF|ZP|SSR|ZPS|HRS|RFT)?\s*.*?\s*(TT|TL|TL/TT)'
    //.'?\s*(Зимние \(шипованные\)|Внедорожные|Летние|Зимние \(нешипованные\)|Всесезонные)'
    .'~iu';

$data='Toyo H08 195/75R16C 107/105S TL Летние';

preg_match($reg,$data,$m);
print_r($m);