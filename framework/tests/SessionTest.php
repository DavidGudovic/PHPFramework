<?php

namespace Dgudovic\Framework\Tests;

use Dgudovic\Framework\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        unset($_SESSION);
        parent::setUp();
    }

    public function testSetAndGetFlash(): void
    {
        $session = new Session();

        $session->setFlash('success', 'Success Test Message');
        $session->setFlash('error', 'Error test message');

        $this->assertEquals(['Success Test Message'], $session->getFlash('success'));
        $this->assertEquals(['Error test message'], $session->getFlash('error'));

        $this->assertEmpty($session->getFlash('warning'));
    }

}