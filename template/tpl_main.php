<?php

class tpl_main
{

    static function _table($data){
        $keys=array_keys($data['data'][0]);
        $result= '<table><tr><th>'.implode('</th><th>',$keys).'</th></tr>';
        foreach($data['data'] as $line){
            $result.= '<tr><td>'.implode('</td><td>',array_values($line)).'</td></tr>';
        }
        $result.= '</table>';
        return $result;
    }

    static function _pager($data){
        $result='';

        if($data['total']>$data['perpage']) {
            $result .= '<label> Перейти на страницу: <select data-handle="pager">';
            $cnt = 0;
            while ($data['total'] > 0) {
                $result .= '<option value="' . ($cnt) . '"';
                if ($data['page'] == $cnt)
                    $result .= ' selected="selected"';
                $result .= '>' . ($cnt + 1) . '</option>';
                $cnt++;
                $data['total'] -= $data['perpage'];
            }
            $result.='</select></label>';
        }
        $result .= '<label> выводить на странице : <select data-handle="perpage">';
        foreach([5,10,15,20,50] as $cnt) {
            $result .= '<option value="' . ($cnt) . '"';
            if ($data['perpage'] == $cnt)
                $result .= ' selected="selected"';
            $result .= '>' . ($cnt ) . '</option>';
        }
        $result.='</select></label>';
        $result .= '<button data-handle="cleardata"> Очистить все данные</button>';
        return $result;
    }

    static function _($data)
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>' . $data['title'] . '</title>
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <style>
        /** таблицы */
        .table-row {
            display: table-row;
        }
        .table-row>span {
            display: table-cell;
        }
        .table {
            display: table;
        }
        /** бордеры  */
        .table-row>span {
            padding:5px 10px;
        }
        .table-row.head>span {
            padding:5px 10px;
            text-align: center;
            font-weight: bold;
        }
        /** загрузко */
        .dropme {
            background: lightgray;
        }
        @keyframes timeout_mobile {
            from {
                transform: rotate(0deg); }

            to {
                transform: rotate(360deg); }
        }
        .wrapper_timeout {
            position: fixed;
            display: none;
            height: 100%;
            width: 100%;
            top: 0;
            background: #000;
            left: 0;
            z-index: 1100;
            opacity: 0.6; 
            }
        .wrapper_timeout span {
            font-size: 60px;
            width: 60px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -30px;
            margin-top: -30px;
            padding-left: 15px;
            animation-name: timeout_mobile;
            animation-duration: 1200ms;
            animation-iteration-count: infinite;
            animation-timing-function: linear; 
            color: white;
        } 
        /** ионические кнопочки */
        .ion {
            padding-right:5px;
        }
        .loading:before {
            content: "\f29a"; /* ion-load-a */
        }
        .runonce:before {
            content: "\f215"; /* play */
        }
        .runstop:before {
            content: "\f201"; /* loop */
        }
        .running .runstop:before {
            content: "\f210"; /* pause */
        }
        /* украшательства */
        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 14px;
            line-height: 1.428571429;
            color: #333;
            background-color: #fff;
        }
        .dropzone {
            border: dotted 3px lightgray;
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
        }
        .fixed .dropzone {
            float: right;
            width: 150px;
            margin-right:30px;
            margin-bottom: 0;
            padding: 4px 19px;
        }
        .fixed .dropzone label {
            display: none;
        }
        h3, .h3 {
            font-size: 24px;
        }
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-weight: 500;
            line-height: 1.1;
        }
        .bold{
            font-weight: bold;
        }
        h1, h2, h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        /**   */
        .name {
            font-weight: bold;
        }
        .complete {
            color: darkgray;
        }
        .available {
            color: darkgreen;
        }
        .notavailable {
            color: darkred;
        }
        .title {
            background: darkgray;
            color: white;
            font-weight: bold;
        }
        /** tristate */
        .tristate {
            padding: 5px 10px;
            border:1px solid gray;
            border-radius: 5px;
            margin-right:3px;
        }
        .tristate.on:before {
            content: "+" ; /* add */
            color: green;
        }
        .tristate:before {
           content: " " ; /* radio-button-off */
           padding: 0 0.3em 0 0.1em;
           font-weight: bold;
        }
        .tristate.off:before {
            content: "-" ; /* button-off */
            color: red;
        }
        /**  fixed line      */
        .fixed {
            position: fixed;
            top: 0;
            left: 0;
            padding: 4px 20px;
            background: white;
            width: 100%;
        }
        .fixed .nofix {
            display: none;
        }
        .unselectable {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

    </style>
</head>
<body>
<div id="buttons" class="unselectable">
<form class="file_upload dropzone" title="Сюда можно перетащить файл с данными, или просто нажать на кнопку \'Загрузить\'">
<input type="hidden" name="handler" value="::upload">
    Drop your data file here<span class="nofix">, or upload it by usual way</span><br>
    <label >
    <input type="file" name="' . Main::get('file_uploader_name') . '[]" >
</label></form>
</div>
<div id="uploads">
    <ul>

    </ul>
</div><div id="info"></div>
<div id="table"></div>
<div class="wrapper_timeout" >
<span class="ion loading"></span>
</div>
</body>
<script>
$(\'#table\').html('.json_encode($data['table']).');
</script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="vendors/Ksnk/js/dropplus.js"></script>

</html>';
    }
}