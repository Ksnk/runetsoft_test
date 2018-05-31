<?php
/**
 * Created by PhpStorm.
 * User: Аня
 * Date: 30.05.2018
 * Time: 17:04
 */

/**
 * let's compile all templates
 */

include '../../autoload.php';
Autoload::register('template');

$INDEX2= realpath('../..').'/';

template_compiler::checktpl(array(
    'TEMPLATE_PATH'=>$INDEX2. 'template/',
    'PHP_PATH'=>$INDEX2. 'template/',
    'TEMPLATE_EXTENSION'=>'twig'
));