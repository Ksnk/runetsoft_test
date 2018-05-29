<?php
/**
 * Created by PhpStorm.
 * User: Аня
 * Date: 29.05.2018
 * Time: 9:52
 */

namespace Ksnk\project;


interface itemLoad
{
    public function getData(int $page = 0, int $perpage = 20) ;

    public function getTotal();
}