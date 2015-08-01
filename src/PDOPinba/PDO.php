<?php
namespace PDOPinba;

class PDO extends \PDO
{
    public static $pinbaTag1Name = 'group';
    public static $pinbaTag1Value = 'pdo';
    public static $pinbaTag2Name = 'op';

    public function __construct()
    {
        $tags = array(self::$pinbaTag1Name => self::$pinbaTag1Value, self::$pinbaTag2Name => 'connect');
        $timer = pinba_timer_start($tags);

        $args = func_get_args();
        $result = call_user_func_array(array('parent', '__construct'), $args);

        pinba_timer_stop($timer);
        return $result;
    }

    public function beginTransaction()
    {
        $tags = array(self::$pinbaTag1Name => self::$pinbaTag1Value, self::$pinbaTag2Name => 'begin');
        $timer = pinba_timer_start($tags);
        $result = parent::beginTransaction();
        pinba_timer_stop($timer);
        return $result;
    }

    public function commit()
    {
        $tags = array(self::$pinbaTag1Name => self::$pinbaTag1Value, self::$pinbaTag2Name => 'commit');
        $timer = pinba_timer_start($tags);
        $result = parent::commit();
        pinba_timer_stop($timer);
        return $result;
    }

    public function rollBack()
    {
        $tags = array(self::$pinbaTag1Name => self::$pinbaTag1Value, self::$pinbaTag2Name => 'rollback');
        $timer = pinba_timer_start($tags);
        $result = parent::rollBack();
        pinba_timer_stop($timer);
        return $result;
    }

    public function exec($statement)
    {
        $tags = array(
            self::$pinbaTag1Name => self::$pinbaTag1Value,
            self::$pinbaTag2Name => self::getQueryType($statement),
        );
        $data = array('sql' => $statement);
        $timer = pinba_timer_start($tags, $data);
        $result = parent::exec($statement);
        pinba_timer_stop($timer);
        return $result;
    }

    /**
     * Actually it returns PDOPinba\PDOStatement but for code-completion i'll fake it
     *
     * @return \PDOStatement
     */
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
        $tmp = strtolower(substr(ltrim($queryText), 0, 8));
        $types = array('begin', 'commit', 'rollback', 'insert', 'update', 'delete', 'select');
        foreach ($types as $type) {
            if (0 === strpos($tmp, $type)) {
                return $type;
            }
        }
        return 'unrecognized';
    }
}
