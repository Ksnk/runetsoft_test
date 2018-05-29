<?php
/**
 * плагин для работы с параметрами в виде var_export файлов
 * ----------------------------------------------------------------------------------
 * $Id: X-Site cms (2.0, LapsiTV build), written by Ksnk (sergekoriakin@gmail.com),
 * ver: xxx, Last build: 1805281355
 * status : draft build.
 * GIT: origin	https://github.com/Ksnk/ENGINE.git (push)$
 * ----------------------------------------------------------------------------------
 * License MIT - Serge Koriakin - Jule 2012
 * ----------------------------------------------------------------------------------
 */
/*  --- point::ENGINE_namespace --- */
namespace Ksnk\core;

class engine_options_varexport  {

    var $option_filename,
        $changed=false,
        $option=array();

    function __construct($option_filename=''){
        $this->option_filename =$option_filename;
        if(is_readable($this->option_filename))
            $this->option = include ($this->option_filename) ;
        if(!empty($this->option))
            ENGINE::set_option(array_keys($this->option),$this);
    }

    function __destruct(){
        $this->save();
    }

    public function save(){
        if($this->changed)  {
            file_put_contents($this->option_filename ,'<'."?php\nreturn ".var_export($this->option,true).';');
            $this->changed=false;
        }
    }

    static function init($par){
        return new self($par);
    }

    function get($name){
        if(isset($this->option[$name]))
            return $this->option[$name];
        else
            return null;
    }

    function set($name,$value){
        if (!is_null($value)) {
            if(!is_array($value) || !array_key_exists($name,$this->option)){
                if(!isset($this->option[$name]) || $this->option[$name]!=$value) {
                $this->option[$name] = $value;
                    $this->changed = true;
                }
            } else {
                $this->changed = array_merge_deep($this->option[$name],$value) || $this->changed ;
            }
        }
    }

}