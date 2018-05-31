<?php
/**
 * Class Main
 * Главный контроллер системы
 */

class Main
{

    /**
     * функция извлечения параметров для шаблона
     * @param $cns
     * @param null $default
     * @return mixed|string
     */
    static function get($cns, $default = null)
    {
        $val = ENGINE::option($cns, $default);
        if (!is_null($val)) {
            return $val;
        } else {
            return sprintf('<!-- unknown value [%s] -->', $cns);
        }
    }

    /**
     * Загрузка файла, подгрузка по частям и все такое
     * @return array
     */
    function do_upload()
    {
        $uploaded = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $file_uploader_name = ENGINE::option('file_uploader_name');
            $upload_dir = ENGINE::option('upload_dir');
            $upload_suffix = ENGINE::option('drop.upload_suffix');
            if (!empty($_FILES[$file_uploader_name]['name'][0])) {
                foreach ($_FILES[$file_uploader_name]['name'] as $position => $name) {
                    $xname = UTILS::translit($name);
                    $fsize = 0;
                    $up = false;
                    $dst = $upload_dir . $xname . $upload_suffix;
                    if (!isset($_POST['chunked']) || empty($_POST['chunked'])) {
                        if (move_uploaded_file(
                            $_FILES[$file_uploader_name]['tmp_name'][$position],
                            $dst
                        )) {
                            $fsize = filesize($dst);
                            $up = array(
                                'name' => $name,
                                'file' => $dst,
                                'progress' => 1,
                            );
                        }
                    } else {
                        //todo: проверить позицию
                        $fp1 = fopen($dst, 'a+');
                        $file2 = file_get_contents($_FILES[$file_uploader_name]['tmp_name'][$position]);
                        $fsize = strlen($file2);
                        fwrite($fp1, $file2);
                        $up = array(
                            'name' => $name,
                            'file' => $dst
                        );
                    }
                    if (!$up) continue;
                    if (isset($_POST['chunked']) && isset($_POST['total'])) {
                        $up['chunked'] = $_POST['chunked'] || 0;
                        $up['progress'] = ($fsize + $_POST['chunked']) / $_POST['total'];
                    }

                    $uploaded[] = $up;

                    if (UTILS::val($up, 'progress') == 1) {
                        $analize = [];
                        //todo: пока нет возможности частичного импорта файла
                        $import = \Ksnk\project\ItemImport::get();
                        $import->importFile($up['file'],
                            function ($reson, $data = null) use (&$analize) {
                                switch ($reson) {
                                    case 'error':
                                        if (!isset($analize[$reson]))
                                            $analize[$reson] = [$data];
                                        else if (count($analize[$reson]) < 20)
                                            $analize[$reson][] = $data;
                                        break;
                                    case 'debug':
                                        if (!isset($analize[$reson]))
                                            $analize[$reson] = [$data];
                                        else
                                            $analize[$reson][] = $data;
                                        break;
                                    case 'stat':
                                        if (!isset($analize[$data]))
                                            $analize[$data] = 1;
                                        else
                                            $analize[$data]++;
                                        break;
                                }
                            });

                        ENGINE::set_option('ajax.analize', $analize);
                        unlink($up['file']);// удаляем файл
                        ENGINE::set_option('ajax.table', $this->getData());

                    }
                }
            }
        }
        return $uploaded;
    }

    /**
     * очистить все данные
     * @return string
     */
    function do_cleardata()
    {
        $import = \Ksnk\project\ItemImport::get();
        $import->deleteall();
        //ENGINE::set_option('page.table', $this->getData());
        return 'ok';
    }

    function getData(){
        $import = \Ksnk\project\ItemImport::get();
        $data=[
            'page'=>(int)ENGINE::option('page',1),
            'perpage'=>(int)ENGINE::option('perpage',5),
            'total'=>(int)$import->getTotal(),
        ];
        if($data['page'] * $data['perpage']>$data['total']){
            $data['page']=0;
        }
        return
            ENGINE::template('tpl_main','_pager',$data).
            ENGINE::template('tpl_main','_table',[
                'data'=>$import->getData($data['page'],$data['perpage'])
            ]);
    }

    /**
     * дефолтный контроллер...
     * @return string
     */
    function do_Default()
    {
        $import = \Ksnk\project\ItemImport::get();
        ENGINE::set_option([
            'page.page'=>(int)ENGINE::option('page',1),
            'page.perpage'=>(int)ENGINE::option('perpage',20),
            'page.total'=>(int)$import->getTotal(),
        ]);
        ENGINE::set_option('page.table', $this->getData());
        return 'ok';
    }

}