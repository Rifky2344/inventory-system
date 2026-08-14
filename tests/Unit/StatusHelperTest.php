<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class StatusHelperTest extends TestCase
{
    public function test_it_translates_known_status_labels(): void
    {
        $this->assertSame('Lunas', statusLabel('Paid'));
        $this->assertSame('Belum Dibayar', statusLabel('Unpaid'));
        $this->assertSame('Menunggu', statusLabel('Pending'));
    }

    public function test_it_returns_original_value_for_unknown_status(): void
    {
        $this->assertSame('Archived', statusLabel('Archived'));
    }
}
