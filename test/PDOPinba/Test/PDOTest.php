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

    public function provider_extractTables()
    {
        return array(
            array('select * from a,b,c where', 'a_b_c'),
            array('select * from a , b , c join', 'a_b_c'),
            array('select * from a join b.c on', 'a_b.c'),
            array('select * from a join b on 1=2 join c on 3=4', 'a_b_c'),
            array('select * from a , b  join c , d on', 'a_b_c_d'),
        );
    }

    /**
     * @dataProvider provider_extractTables
     */
    public function test_extractTables($sql, $expected)
    {
        $this->assertSame($expected, \PDOPinba\PDO::extractTables($sql));
    }
}
