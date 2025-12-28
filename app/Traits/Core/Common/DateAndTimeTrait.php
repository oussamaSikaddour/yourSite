<?php

namespace App\Traits\Core\Common;

use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait DateAndTimeTrait
{
    /**
     * Calculates the work duration in hours between two times.
     * Uses cache to avoid repeated computations for same time intervals.
     *
     * @param string $startTime The start time in HH:MM format.
     * @param string $endTime The end time in HH:MM format.
     * @return float|null The duration in hours, or null if times are invalid.
     */
    protected function calculateWorkDurationInHours(string $startTime, string $endTime): ?float
    {
        // Generate a unique cache key for the given time range
        $cacheKey = "work_duration_{$startTime}_{$endTime}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($startTime, $endTime) {
            // Parse start and end time
            $start = DateTime::createFromFormat('H:i', $startTime);
            $end = DateTime::createFromFormat('H:i', $endTime);

            if ($start === false || $end === false) {
                return null; // Invalid time format
            }

            // If end is earlier than start, assume the work period spans midnight
            if ($start > $end) {
                $end->modify('+1 day');
            }

            // Calculate duration in hours and minutes
            $diff = $start->diff($end);
            $hours = $diff->h + ($diff->i / 60);

            return round($hours, 2); // Return rounded result
        });
    }

    /**
     * Calculates total days between two dates, with optional filtering of non-working days.
     * Caches the result to optimize performance for repeated inputs.
     *
     * @param string $startDate The start date.
     * @param string $endDate The end date.
     * @param array $workingDays Array of working days (0=Sunday to 6=Saturday).
     * @param bool $hourly Whether to exclude non-working days from the count.
     * @return int Total number of days in range.
     */
    protected function getTotalDays(string $startDate, string $endDate, array $workingDays = [], bool $hourly = false): int
    {
        // Create a cache key using the dates and working days
        $cacheKey = "total_days_{$startDate}_{$endDate}_" . implode('_', $workingDays);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($startDate, $endDate, $workingDays, $hourly) {
            try {
                $startDate = Carbon::parse($startDate);
                $endDate = Carbon::parse($endDate);
            } catch (\Exception $e) {
                return 0; // Invalid date format
            }

            if ($startDate > $endDate) {
                return 0;
            }

            // Calculate inclusive difference in days
            $totalDays = $endDate->diffInDays($startDate) + 1;

            if ($hourly && !empty($workingDays)) {
                // Filter out any invalid day numbers (0-6)
                $workingDays = array_filter($workingDays, function ($day) {
                    return is_int($day) && $day >= 0 && $day <= 6;
                });

                // Count number of days that are NOT working days
                $daysOff = $startDate->diffInDaysFiltered(function ($date) use ($workingDays) {
                    return !in_array($date->dayOfWeek, $workingDays);
                }, $endDate);

                // Subtract non-working days from total
                $totalDays -= $daysOff;
            }

            return $totalDays;
        });
    }

    /**
     * Converts a date string to the specified format.
     * Uses caching to avoid re-parsing the same value multiple times.
     *
     * @param string|null $date The input date string.
     * @param string $format The desired output format (default: 'Y-m-d').
     * @return string|null The formatted date string or null if invalid.
     */
    public function parseDate(?string $date, string $format = 'Y-m-d'): ?string
    {
        // Cache key includes date and desired format
        $cacheKey = "parse_date_{$date}_{$format}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($date, $format) {
            if (empty($date)) {
                return null;
            }

            try {
                return Carbon::parse($date)->format($format);
            } catch (\Exception $e) {
                return null; // Return null on invalid date format
            }
        });
    }
}
