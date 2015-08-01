<?php
namespace PDOPinba\Test;

class PDOTest extends \PHPUnit_Framework_TestCase
{
    public function provider_getQueryType()
    {
        return array(
            array('select * from', 'select'),
            array('  select * from', 'select'),
            array('SelecT * from', 'select'),
            array('update smth', 'update'),
            array('insert smth', 'insert'),
            array('begin smth', 'begin'),
            array('commit smth', 'commit'),
            array('rollback smth', 'rollback'),
            array('other smth', 'unrecognized'),
            array('fdsf', 'unrecognized'),
            array('', 'unrecognized'),
            array(false, 'unrecognized'),
            array(null, 'unrecognized'),
            array(-1, 'unrecognized'),
        );
    }

    /**
     * @dataProvider provider_getQueryType
     */
    public function test_getQueryType($sql, $expected)
    {
        $this->assertSame($expected, \PDOPinba\PDO::getQueryType($sql));
    }
}
