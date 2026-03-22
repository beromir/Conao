<?php

namespace App\Helpers;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class DateHelper
{
    public static function getPlannedForLabel(?string $date): string
    {
        if (empty($date)) {
            return '';
        }

        $plannedFor = Carbon::parse($date);
        $now = now();

        if ($plannedFor->isTomorrow()) {
            return 'tomorrow';
        }
        if ($plannedFor->lessThanOrEqualTo(now()->addDays(30)) && $plannedFor->greaterThan($now)) {
            return $now->diffForHumans($plannedFor->addDay(), [
                'syntax' => CarbonInterface::DIFF_RELATIVE_TO_OTHER,
                'options' => CarbonInterface::ONE_DAY_WORDS,
            ], true);
        } else {
            return $plannedFor->toDate()->format('j.n.');
        }
    }

    public static function getDeadlineLabel(?string $date): string
    {
        if (empty($date)) {
            return '';
        }

        $deadline = Carbon::parse($date);
        $now = now();

        if ($deadline->isSameDay($now)) {
            return 'today';
        } elseif ($deadline->isTomorrow()) {
            return 'tomorrow';
        } elseif ($deadline->isPast()) {
            return 'overdue';
        } elseif ($deadline->greaterThanOrEqualTo($now) && $deadline->lessThanOrEqualTo(now()->addDays(30))) {
            return $now->diffForHumans($deadline->addDay(), [
                'syntax' => CarbonInterface::DIFF_RELATIVE_TO_OTHER,
                'options' => CarbonInterface::ONE_DAY_WORDS,
            ], true);
        } else {
            return $deadline->toDate()->format('j.n.');
        }
    }
}
