<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Automated tests for the Dose unit (Section 4.2).
 *
 * Every test fixes the clock explicitly instead of reading system time,
 * so each boundary is exercised deterministically and the suite gives
 * the same result whenever it is run.
 */
final class DoseTest extends TestCase
{
    /** Monday 4 January 2027, 08:00:00 UTC — the scheduled time for every dose below. */
    private const SCHEDULED = 1799049600;

    /** Helper: a timestamp N minutes after the scheduled time. */
    private function at(int $minutes): int
    {
        return self::SCHEDULED + ($minutes * 60);
    }

    /** TC-1: 14 minutes past the scheduled time is still inside the 15-minute grace window. */
    public function testDoseIsNotOverdueInsideGraceWindow(): void
    {
        $dose = new Dose(1, self::SCHEDULED, DoseStatus::DUE);

        $this->assertFalse($dose->isOverdue($this->at(14)));
    }

    /** TC-2: 16 minutes past the scheduled time is outside the grace window. */
    public function testDoseIsOverdueAfterGraceWindow(): void
    {
        $dose = new Dose(2, self::SCHEDULED, DoseStatus::DUE);

        $this->assertTrue($dose->isOverdue($this->at(16)));
    }

    /** TC-3: a dose marked taken inside the window records the status and the time, and never goes overdue. */
    public function testDoseMarkedTakenInsideWindow(): void
    {
        $dose    = new Dose(3, self::SCHEDULED, DoseStatus::DUE);
        $takenAt = $this->at(5);

        $dose->updateStatus(DoseStatus::TAKEN, $takenAt);

        $this->assertSame(DoseStatus::TAKEN, $dose->getStatus());
        $this->assertSame($takenAt, $dose->getActualTime());
        $this->assertFalse($dose->isOverdue($this->at(600)));
    }

    /** TC-4: a dose marked taken late records the real time and leaves the schedule untouched. */
    public function testDoseMarkedTakenAfterWindowPreservesScheduledTime(): void
    {
        $dose    = new Dose(4, self::SCHEDULED, DoseStatus::DUE);
        $takenAt = $this->at(80);

        $dose->updateStatus(DoseStatus::TAKEN, $takenAt);

        $this->assertSame(DoseStatus::TAKEN, $dose->getStatus());
        $this->assertSame($takenAt, $dose->getActualTime());
        $this->assertSame(self::SCHEDULED, $dose->getScheduledTime());
    }

    /** TC-5: a missed dose later marked taken records when the USER acted, not when the scheduler gave up. */
    public function testMissedDoseMarkedTakenRecordsWhenUserActed(): void
    {
        $dose = new Dose(5, self::SCHEDULED, DoseStatus::DUE);

        // The scheduler marks the dose missed. It passes no timestamp,
        // because a system transition is not a user action.
        $dose->updateStatus(DoseStatus::MISSED);
        $this->assertSame(DoseStatus::MISSED, $dose->getStatus());
        $this->assertNull($dose->getActualTime());

        // The user marks it taken over an hour later.
        $takenAt = $this->at(80);
        $dose->updateStatus(DoseStatus::TAKEN, $takenAt);

        $this->assertSame(DoseStatus::TAKEN, $dose->getStatus());
        $this->assertSame($takenAt, $dose->getActualTime());
        $this->assertSame(self::SCHEDULED, $dose->getScheduledTime());
    }
}