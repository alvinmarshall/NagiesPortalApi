<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\test\utils;

use App\common\utils\PageUtils;
use PHPUnit\Framework\TestCase;

class PageUtilsTest extends TestCase
{
    protected $pageUtil;

    protected function setUp(): void
    {
        $this->pageUtil = new PageUtils();
    }

    public function testSetPagination_Is_Null()
    {
        $expected = $this->pageUtil->setPagination(0, 0, 0, 'test.com');
        self::assertNull($expected);
    }

    public function testSetPagination_Not_Null()
    {
        $expected = $this->pageUtil->setPagination(1, 15, 5, 'test.com');
        self::assertNotNull($expected);
    }
}
