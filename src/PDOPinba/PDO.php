<?php
namespace PDOPinba;

class PDO extends \PDO
{
    public function beginTransaction()
    {
        $tags = array('group' => 'mysql', 'op' => 'begin');
        $timer = pinba_timer_start($tags);
        $result = parent::beginTransaction();
        pinba_timer_stop($timer);
        return $result;
    }

    public function commit()
    {
        $tags = array('group' => 'mysql', 'op' => 'commit');
        $timer = pinba_timer_start($tags);
        $result = parent::commit();
        pinba_timer_stop($timer);
        return $result;
    }

    public function rollBack()
    {
        $tags = array('group' => 'mysql', 'op' => 'rollback');
        $timer = pinba_timer_start($tags);
        $result = parent::rollBack();
        pinba_timer_stop($timer);
        return $result;
    }

    public function exec($statement)
    {
        $tags = array('group' => 'mysql', 'op' => self::getQueryType($statement));
        $data = array('sql' => $statement);
        $timer = pinba_timer_start($tags, $data);
        $result = parent::exec($statement);
        pinba_timer_stop($timer);
        return $result;
    }

    public function query()
    {
        $args = func_get_args();
        $result = call_user_func_array(array('parent', 'query'), $args);
        return new PDOStatement($result);
    }

    public function prepare($statement, $driver_options = array())
    {
        $result = parent::prepare($statement, $driver_options);
        return new PDOStatement($result);
    }

    public static function getQueryType($queryText)
    {
        $type = strtolower(substr(ltrim($queryText), 0, 6));
        if (in_array($type, array('begin', 'commit', 'rollback', 'insert', 'update', 'delete', 'select'))) {
            return $type;
        } else {
            return 'query';
        }
    }
}
