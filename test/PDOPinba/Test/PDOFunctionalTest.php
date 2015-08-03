<?php
namespace PDOPinba\Test;

class PDOFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $pdo = new \PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $pdo->exec('DROP TABLE IF EXISTS test');
    }

    public function testConnect()
    {
        new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
    }

    public function testTransactionCommit()
    {
        $pdo = new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE test (id INT);');
        $pdo->beginTransaction();
        $pdo->exec('INSERT INTO test VALUES (10);');
        $pdo->commit();

        $pdoRaw = new \PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $row = $pdoRaw->query('SELECT * FROM test')->fetch(\PDO::FETCH_ASSOC);
        $this->assertSame(array('id' => '10'), $row);
    }

    public function testTransactionRollback()
    {
        $pdo = new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE test (id INT);');
        $pdo->beginTransaction();
        $pdo->exec('INSERT INTO test VALUES (10);');
        $pdo->rollBack();

        $pdoRaw = new \PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $rows = $pdoRaw->query('SELECT * FROM test')->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEmpty($rows);
    }

    public function testQueryAndStatement()
    {
        $pdo = new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE test (id INT);');
        $pdo->exec('INSERT INTO test VALUES (10);');

        $row = $pdo->query('SELECT * FROM test')->fetch(\PDO::FETCH_ASSOC);
        $this->assertSame(array('id' => '10'), $row);
    }
}
