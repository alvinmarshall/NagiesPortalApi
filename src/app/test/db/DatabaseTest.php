<?php

namespace App\test\db;

use PHPUnit\DbUnit\DataSet\IDataSet;

class DatabaseTest extends dbTest
{

    /**
     * Returns the test dataset.
     *
     * @return IDataSet
     */
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/fixtures/portal_fixtures.xml');
    }

    public function testStudentRowCount()
    {
        self::assertSame(2, $this->getConnection()->getRowCount('student'));
    }

    public function testTeachersRowCount()
    {
        self::assertSame(2, $this->getConnection()->getRowCount('teachers'));
    }

    public function testAssignmentRowCount()
    {
        self::assertSame(2, $this->getConnection()->getRowCount('assignment'));
    }

    public function testAssignmentImageRowCount()
    {
        self::assertSame(2, $this->getConnection()->getRowCount('assignment_image'));
    }

    public function testMessagesRowCount()
    {
        self::assertSame(1, $this->getConnection()->getRowCount('message'));
    }

    public function testComplaintRowCount()
    {
        self::assertSame(1, $this->getConnection()->getRowCount('complaints'));
    }
}
