<?php

namespace test\model;

use model\Admin;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    public function testCriarObjeto()
    {
        $admin = new Admin(
            "Tom Jobim",
            "111.222.333-44",
            new \DateTime("1975-01-01"),
            "hash123",
            "M-001",
            "TI",
            "Gerente",
            "2020-01-10",
            new \DateTime("2020-01-10"),
            "admin"
        );
        $this->assertNotNull($admin);
        $this->assertEquals("Tom Jobim", $admin->getName());
        $this->assertEquals("111.222.333-44", $admin->getCpf());
        $this->assertEquals("M-001", $admin->getMatricula());
        $this->assertEquals("TI", $admin->getSetor());
        $this->assertEquals("Gerente", $admin->getCargo());
    }
}
