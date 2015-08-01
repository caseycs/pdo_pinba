<?php
namespace PDOPinba\Test;

class PDOFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
    }

    public function testTransactionCommit()
    {
        $pdo = new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE IF NOT EXISTS test (id INT);');
        $pdo->beginTransaction();
        $pdo->exec('INSERT INTO test VALUES (10);');
        $pdo->commit();
    }

    public function testTransactionRollback()
    {
        $pdo = new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE IF NOT EXISTS test (id INT);');
        $pdo->beginTransaction();
        $pdo->exec('INSERT INTO test VALUES (10);');
        $pdo->commit();
    }

    public function testQueryAndStatement()
    {
        $pdo = new \PDOPinba\PDO('mysql:dbname=pdo_pinba_test', 'travis');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE IF NOT EXISTS test (id INT);');
        $pdo->exec('INSERT INTO test VALUES (10);');
        $stmt = $pdo->query('SELECT * FROM test');
        $this->assertNotEmpty($stmt->fetchAll());
    }
}
