<?php

include 'autoload.php';

ENGINE::read_options('varexport~src/config.php');

ENGINE::set_option('page', null, 'cookie');
ENGINE::set_option('perpage', null, 'cookie');

ENGINE::route();

ENGINE::startSessionIfExists();
ob_start();
try {
    ENGINE::action();
} catch (Exception $e) {
    error_log('>' . $e->getMessage());
}
ob_end_flush();