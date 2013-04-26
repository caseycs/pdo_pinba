<?php
namespace PDOPinba;

class PDOStatement
{
    private $PDOStatement;

    public function __construct(\PDOStatement $PDOStatement)
    {
        $this->PDOStatement = $PDOStatement;
    }

    public function execute(array $input_parameters = null)
    {
        $tags = array(
            'group' => 'mysql',
            'op' => PDO::getQueryType($this->PDOStatement->queryString),
//            'tbls' => PDO::extractTables($this->PDOStatement->queryString),
        );
        $data = array('sql' => $this->PDOStatement->queryString);
        $timer = pinba_timer_start($tags, $data);
        $result = $this->PDOStatement->execute($input_parameters);
        pinba_timer_stop($timer);
        return $result;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->PDOStatement, $method), $args);
    }

    public function __get($name)
    {
        return $this->PDOStatement->$name;
    }
}
