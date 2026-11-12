<?php

declare(strict_types=1);

/**
 * The four states a scheduled dose can be in.
 * Mirrors the DoseStatus enumeration in the Deliverable 1 class diagram (Figure 11).
 */
enum DoseStatus: string
{
    case UPCOMING = 'upcoming';
    case DUE      = 'due';
    case TAKEN    = 'taken';
    case MISSED   = 'missed';
}

/**
 * One scheduled dose of a medication.
 *
 * Design rule: actualTime records when the USER acted on the dose.
 * System transitions (such as the scheduler marking a dose missed)
 * record no timestamp, so the stored time is never a time the user
 * did not act.
 */
final class Dose
{
    /** Minutes the user has after the scheduled time before a dose counts as missed. */
    public const GRACE_PERIOD_MINUTES = 15;

    private int $doseId;
    private int $scheduledTime;   // Unix timestamp of when the dose is due
    private ?int $actualTime;     // when the user marked it; null until they do
    private DoseStatus $status;

    public function __construct(
        int $doseId,
        int $scheduledTime,
        DoseStatus $status = DoseStatus::UPCOMING
    ) {
        $this->doseId        = $doseId;
        $this->scheduledTime = $scheduledTime;
        $this->status        = $status;
        $this->actualTime    = null;
    }

    /**
     * A dose is overdue once the grace window has closed and the user
     * still has not marked it as taken.
     *
     * The current time is passed in rather than read from the system clock
     * so that tests can place the dose at any point on the timeline.
     */
    public function isOverdue(int $currentTime): bool
    {
        $graceDeadline = $this->scheduledTime + (self::GRACE_PERIOD_MINUTES * 60);

        return $currentTime > $graceDeadline
            && $this->status !== DoseStatus::TAKEN;
    }

    /**
     * Change the dose's status, optionally recording when the user acted.
     *
     * The first recorded timestamp is kept, so a later status change cannot
     * overwrite the time of a real user action. The scheduled time is never
     * modified.
     */
    public function updateStatus(DoseStatus $status, ?int $timestamp = null): void
    {
        $this->status = $status;

        if ($timestamp !== null && $this->actualTime === null) {
            $this->actualTime = $timestamp;
        }
    }

    public function getStatus(): DoseStatus
    {
        return $this->status;
    }

    public function getActualTime(): ?int
    {
        return $this->actualTime;
    }

    public function getScheduledTime(): int
    {
        return $this->scheduledTime;
    }
}