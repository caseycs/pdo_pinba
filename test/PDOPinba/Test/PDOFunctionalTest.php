<?php
namespace PDOPinba\Test;

class PDOFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function test_connect()
    {
        $pdo = new \PDOPinba\PDO('mysql:dbname=myapp_test', 'travis');
    }
}
