<?php
/**
 * Created by PhpStorm.
 * User: Аня
 * Date: 29.05.2018
 * Time: 9:53
 */

namespace Ksnk\project;

/**
 * При работе с базой данных, ловим исключения снаружи
 */
class itemDatabaseStore implements itemStore, itemLoad
{
    /** @var \PDO */
    var $db;

    public function __construct(array $dbconfig)
    {
        $this->db = new \PDO("mysql:host=" . $dbconfig['host']
            . ";dbname=" . $dbconfig['base']
            . ";charset=utf8"
            , $dbconfig['user'], $dbconfig['password']);
        $this->db->exec("set names utf8"); // вроде и не нужно, но не везде...
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * выдать порцию данных для отображения на страничке
     * @param int $page
     * @param int $perpage
     * @return array|bool
     */
    public function getData(int $page = 0, int $perpage = 20)
    {
        if (!$this->db) return false;

        $stm = $this->db->prepare('select i.name, a.* from rns_items as i left join rns_attr as a on i.id=a.id order by a.id limit ?, ?');
        $stm->bindValue(1, $page * $perpage, \PDO::PARAM_INT);
        $stm->bindValue(2, $perpage, \PDO::PARAM_INT);
        $stm->execute();
        return  $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * количество строк в таблице имен
     * @param int $page
     * @param int $perpage
     * @return array|bool
     */
    public function getTotal()
    {
        if (!$this->db) return false;

        $sql = 'SELECT count(*) FROM `rns_items`';
        $result = $this->db->prepare($sql);
        $result->execute();
        return  $result->fetchColumn();
    }

    /**
     * очистить таблицы, обе две
     * @return bool
     */
    public function clear()
    {
        if (!$this->db) return false;

        $result = $this->db->prepare('TRUNCATE `rns_attr`');
        $result->execute();
        $result = $this->db->prepare('TRUNCATE `rns_items`');
        $result->execute();
        return  true;
    }

    /**
     * Чудесным образом поля данных совпадают с названиями полей базы. Конверсия не требуется
     * @param $data
     * @return bool
     */
    public function store($data)
    {
        if (!$this->db) return false;
        // заполняем таблицу имен
        $lastId = false;
        $prep = $this->db->prepare(
            'select id from rns_items where name=:name;'
        );
        $prep->execute([
            'name' => $data['name']
        ]);
        while ($row = $prep->fetch(\PDO::FETCH_LAZY)) {
            $lastId = $row['id'] . "\n";
        }
        if (false === $lastId) {
            $prep = $this->db->prepare(
                'INSERT into rns_items(name) VALUES(:name)'
            );
            $prep->execute([
                'name' => $data['name']
            ]);
            $lastId = $this->db->lastInsertId();
        }
        unset($data['name']);
        $data['id'] = $lastId;
        $names = array_keys($data);
        $prep = $this->db->prepare(
            'select id from rns_attr where id=:id;'
        );
        $prep->execute([
            'id' => $data['id']
        ]);
        while ($row = $prep->fetch(\PDO::FETCH_LAZY)) {
            return false;
            // не нужно ничего вставлять
        }

        $sql = 'INSERT into rns_attr(' .
            implode(',', $names)
            . ') VALUES(:' .
            implode(',:', $names) .
            ');';
        //echo $sql;
        if (!($prep = $this->db->prepare($sql))) {
            print_r($this->db->errorInfo());
        } else
            $prep->execute($data);

        return true;
    }

}