<?php
/**
 * Created by PhpStorm.
 * User: Аня
 * Date: 29.05.2018
 * Time: 8:29
 */

namespace Ksnk\project;


class ItemImport
{

    const BUFSIZE = 30000;

    var $namesrc,
        $namereg = '',
        $namereg_attr = [];

    var $store = false;

    private static $instance=null;

    /**
     * @return ItemImport|null
     */
    public static function get(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set_store($store=null){
        if (is_null($store)) {
            $this->store = new itemDatabaseStore(
            // открываем базу данных, предполагая, что находимся в окружении ENGINE
                \ENGINE::slice_option('db.')
            );
        } else if ($store instanceof itemStore) {
            // потенциальная возможность сохранить ввод в более другой форме
            $this->store = $store;
        }
    }

    private function __construct($store = null)
    {

        // конструируем регулярку и прописываем названия полей захватываемых данных.
        // Использовать именованные маски не люблю.
        // todo: добавить версионность для разных типов имен товаров
        $this->namesrc = [
            'brand' => 'Nokian|BFGoodrich|Pirelli|Toyo|Continental|Hankook|Mitas', // ' \w+ было бы разумнее, вероятно, но до разума нам еще отлаживаццо и отлаживаццо
            '\s+',
            'model' => '.*?',
            '\s+',
            'width' => '\d+',
            '/',
            'height' => '\d+',
            '\s*',
            'constr' => '[a-z]+',
            'diam' => '\d+',
            '\w?\s+', // а еще тут бывает С... :(
            'forceind' => '[\/\d]+',
            'speedind' => '[a-z]+',
            '\s*',
            // начало необязательной части, пробельные символы меняются
            'abbr' => '[a-z]+',
            '?\s*',
            'runflat' => 'RunFlat|Run Flat|ROF|ZP|SSR|ZPS|HRS|RFT',
            '?\s*.*?\s*', // тут еще что-то бывает... Сюрприииииз!
            'camera' => 'TT|TL|TL/TT',
            '?\s*',
            'season' => 'Зимние \(шипованные\)|Внедорожные|Летние|Зимние \(нешипованные\)|Всесезонные'
        ];

        // сборка регулярки и сопроводительного индекса
        $this->namereg = '';
        $this->namereg_attr = [];
        $index = 1; // номер захваченого значения
        foreach ($this->namesrc as $k => $v) {
            if (is_numeric($k)) { // бордерный, непоименованных
                $this->namereg .= $v;
            } else { // именованная маска
                $this->namereg_attr[$index++] = $k;
                $this->namereg .= '(' . $v . ')';
            }
        }

        $this->namereg = '~' . $this->namereg . '~iu';

    }

    /**
     * Читатель файла. Возможно, ОЧЕНЬ длинного файла.
     * todo: добавить остановки по времени,если не успеваем импортировать часть файла
     * @param $filename
     * @param callable $callback
     */
    public function importFile($filename, $callback)
    {
        if (!$this->store) {
            $this->set_store(); // устанавливаем дефолтный транспорт
        }
        if (is_readable($filename)) {
            $tail = '';
            $handle = fopen($filename, 'r+');
            $callback('stat', 'fopen');
            while (!feof($handle)) {
                $buf = $tail . fread($handle, self::BUFSIZE);
                $callback('stat', 'fread');
                $lines = explode("\n", $buf);
                if (!is_array($lines) || count($lines) < 2) {
                    $callback('error', 'Очень длинная строка, некорректный файл');
                    return;
                }
                if (!feof($handle)) {
                    $tail = array_pop($lines);
                }
                foreach ($lines as $line) {
                    $this->importLine($line, $callback);
                }
            }
            fclose($handle);
            $callback('stat', 'fclose');
        }
    }

    public function importLine($line, $callback)
    {
        $line = trim($line);
        if ('' == $line) {
            $callback('stat', 'emptyline');
            return;
        };
        if (preg_match($this->namereg, $line, $m)) {
            $data = [];
            foreach ($this->namereg_attr as $k => $v) {
                if (isset($m[$k])) {
                    $data[$v] = $m[$k];
                }
            }
            $callback('stat', 'itemfound');
            /**
             * доработка результата по месту напильником
             * Иногда TTL попадает в поле абревиатуры
             * Иногда runflat попадает в поле абревиатуры
             * Так как описать регулярку умнее не выходит - напильник не ржавеет
             * todo: исправить или и так ничо...?
             */
            foreach (['camera', 'runflat'] as $w)
                if (!empty($data['abbr']) && empty($data[$w]) && preg_match('~' . $this->namesrc[$w] . '~iu', $data['abbr'])) {
                    $data[$w] = $data['abbr'];
                    $data['abbr'] = '';
                }

            $data['name'] = $line;
            try {
                if (!$this->store) {
                    $callback('error', 'что-то пошло не так');
                } else if ($this->store->store($data)) {
                    $callback('stat', 'itemstored');
                };
            } catch (\Exception $e) {
                $callback('error', $e->getMessage());
            }
        } else {
            $callback('error', 'Некорректное наименование товара:' . mb_substr($line, 0, 80, 'utf-8'));// substr, потому что мало ли какой файл нам зальют для анализа, как бы не грохнуться из за длинных строк...
        }
    }

    public function getData($page=0, $perpage=20)
    {
        if (!$this->store) {
            $this->set_store(); // устанавливаем дефолтный транспорт
        }
        if ($this->store instanceof itemLoad)
            return $this->store->getData($page, $perpage);
        else
            return [];
    }
    public function getTotal()
    {
        if (!$this->store) {
            $this->set_store(); // устанавливаем дефолтный транспорт
        }
        if ($this->store instanceof itemLoad)
            return $this->store->getTotal();
        else
            return [];
    }
    public function deleteall(){
        if (!$this->store) {
            $this->set_store(); // устанавливаем дефолтный транспорт
        }
        if(method_exists($this->store,'clear'))
            return $this->store->clear();
        else
            return false;
    }
}