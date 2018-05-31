<?php
/**
 * this file is created automatically at "31 May 2018 18:08". Never change anything,
 * for your changes can be lost at any time. 
 */ 
class tpl_main extends tpl_base {
function __construct(){
parent::__construct();
$this->macro['table']=array($this,'_table');
$this->macro['pager']=array($this,'_pager');
}

function _table(&$namedpar,$data=0){
extract($namedpar);
$result='';
if( $data ) {

$result.='
    <table class="table table-striped">
        <thead> 
        <tr>
            <th>id</th>';
$loop1_array=self::ps($this->func_bk($data,0));
if ((is_array($loop1_array) && !empty($loop1_array))||($loop1_array instanceof Traversable)){
foreach($loop1_array as $cell =>$val){

if( (($cell)!=('id')) ) {

$result.='
                <th>'
    .($cell)
    .'</th>';
};
}};
$result.='
        </tr>
        </thead>
        <tbody>';
$loop1_array=self::ps($data);
if ((is_array($loop1_array) && !empty($loop1_array))||($loop1_array instanceof Traversable)){
foreach($loop1_array as $row){

$result.='
    <tr>
            <td><code class="w3-codespan">'
    .$this->func_bk($row,'id')
    .'</code></td>';
$loop2_array=self::ps($row);
if ((is_array($loop2_array) && !empty($loop2_array))||($loop2_array instanceof Traversable)){
foreach($loop2_array as $cell =>$val){

if( (($cell)!=('id')) ) {

$result.='
            <td>'
    .($val)
    .'</td>';
};
}};
$result.='
        </tr>';
}};
$result.='
        </tbody>
    </table>';
}
else {

$result.='
        No data';
};
    return $result;
}

function _pager(&$namedpar,$total=0,$perpage=0,$page=0,$url='#',$linkclass='',$styles=''){
extract($namedpar);
$result=' 
    <div class="row"><div class="col-sm-8">';
if( (($total)>($perpage)) ) {

$result.='<ul class="pagination"';
if( $styles ) {

$result.=' style="'
    .($styles)
    .'"';
};
$result.='>';
$maxnumb=ceil($total/$perpage);
$start=(($page)-(3));
if( (($start)>(1)) ) {

$result.='<li><a data-handle="page" data-data="'
    .((($start)-(1)))
    .'"';
if( $linkclass ) {

$result.=' class="'
    .($linkclass)
    .'"';
};
$result.=' href="';
if( (($start)==(2)) ) {

$result.=((trim($url,'?&')));
}
else {

$result.=($url)
    .'page='
    .((($start)-(1)));
};
$result.='">&laquo;</a>&nbsp;</li>';
};
$loop1_array=self::ps($this->func_range(7,1));
if ((is_array($loop1_array) && !empty($loop1_array))||($loop1_array instanceof Traversable)){
foreach($loop1_array as $xpage){

$pagex=(($start)+($xpage));
if( ((($pagex)>(0))) && ((($pagex)<=($maxnumb))) ) {

if( (($pagex)==($page)) ) {

$result.='<li class="active"><span>'
    .($pagex)
    .'<span class="sr-only">(current)</span></span></li>';
}
elseif( (($pagex)==(1)) ) {

$result.='<li><a data-handle="page" data-data="'
    .($pagex)
    .'"';
if( $linkclass ) {

$result.=' class="'
    .($linkclass)
    .'"';
};
$result.=' href="'
    .((trim($url,'?&')))
    .'">'
    .($pagex)
    .'</a></li>';
}
else {

$result.='<li><a data-handle="page" data-data="'
    .($pagex)
    .'"';
if( $linkclass ) {

$result.=' class="'
    .($linkclass)
    .'"';
};
$result.=' href="'
    .($url)
    .'page='
    .($pagex)
    .'">'
    .($pagex)
    .'</a></li>';
};
};
}};
if( (($pagex)<($maxnumb)) ) {

$result.='<li><a data-handle="page" data-data="'
    .((($pagex)+(1)))
    .'"';
if( $linkclass ) {

$result.=' class="'
    .($linkclass)
    .'"';
};
$result.=' href="'
    .($url)
    .'page='
    .((($pagex)+(1)))
    .'">&raquo;</a></li>';
};
};
$result.='</ul>'
    .(isset($par['endif'])?$par['endif']:"")
    .'</div>'
    .(ENGINE::debug($perpage))
    .'
        <div class="col-sm-4">
            <ul class="pagination ">';
$loop1_array=self::ps(array(5,10,15,25,50));
if ((is_array($loop1_array) && !empty($loop1_array))||($loop1_array instanceof Traversable)){
foreach($loop1_array as $lab){

if( (($perpage)!=($lab)) ) {

$result.='
                <li><a href="#" data-handle="perpage" data-data="'
    .($lab)
    .'">'
    .($lab)
    .'</a></li>';
}
else {

$result.='
                        <li class="active"><span>'
    .($lab)
    .'<span class="sr-only">(current)</span></span></li>';
};
}};
$result.='
            </ul> 
        </div>
    </div>';
    return $result;
}

function _ (&$par){
$result='<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>'
    .(isset($par['title'])?$par['title']:"")
    .'</title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="main.css" rel="stylesheet">
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Тестовое задание</a>
        </div>
        <div class="collapse navbar-collapse">

            <ul class="nav navbar-nav navbar-right">
                <li><a href="#clear" data-handle="cleardata">Очистить данные</a></li>

            </ul>
            <ul class="nav navbar-nav  navbar-right">

                <form class="dropzone" title="Сюда можно перетащить файл с данными, или просто нажать на кнопку \'Загрузить\'">
                    <input type="hidden" name="handler" value="::upload">

                    <input type="file" name="'
    .($this->callex('Main','get','file_uploader_name'))
    .'[]" >
                </form>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<main role="main" class="container">
    <div id="table">'
    .(isset($par['table'])?$par['table']:"")
    .'</div>
</main>
<footer class="footer">
    <div class="container">
        <span class="text-muted"><div id="uploads">
    <ul style="margin-bottom: 0;">

    </ul>
</div>
            <div id="info"></div></span>
    </div>
</footer>


</body>

<!-- Latest compiled and minified JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="vendors/Ksnk/js/dropplus.js"></script>
</html>';
    return $result;
}
}