<?php

namespace App\Traits\App\Common;

use Illuminate\Support\Facades\DB;

trait AppTrait
{
    /**
     * Validates an Algerian-style RIB (bank account) number by calculating and checking the RIB key.
     *
     * @param string $bankAccount Full bank account string (typically 20 digits or more).
     * @return array Parsed components and RIB key validation result.
     */
    public function checkBankAccount(string $bankAccount)
    {
        // Extract components from the bank account string
        $bankCode = substr($bankAccount, 0, 3);
        $agencyCode = substr($bankAccount, 3, 5);
        $accountNumber = substr($bankAccount, 8, 10);
        $providedKey = substr($bankAccount, 18, 2); // Last 2 digits are the provided key

        // Concatenate agency code and account number
        $r1 = $agencyCode . $accountNumber;

        // Calculate the expected RIB key
        $r2 = (int)$r1 * 100;
        $r3 = $r2 % 97;
        $ribKey = 97 - $r3;

        // Validate the provided key
        $ribErrorTest = $ribKey == $providedKey;

        // Return result details
        return [
            "bank-code"    => $bankCode,
            "agency-code"  => $agencyCode,
            "status"       => $ribErrorTest,
            "ribKey"       => $ribKey,
            "providedKey"  => $providedKey
        ];
    }

    /**
     * Inserts a given string (e.g., space) at multiple positions in the input string.
     *
     * @param string $string The original string to modify.
     * @param string $insertedString The string to insert (e.g., ' ').
     * @param array $positions Array of positions (integers) where insertions should occur.
     * @return string The modified string with insertions.
     */
    public function insertSpacesAtPositions(string $string, string $insertedString, array $positions): string
    {
        // Sort positions in descending order to preserve positions during insertion
        rsort($positions);

        foreach ($positions as $pos) {
            $string = substr($string, 0, $pos) . $insertedString . substr($string, $pos);
        }

        return $string;
    }

    /**
     * Sets or adjusts the slide order in a slider context.
     *
     * If $data['order'] is set, it will shift all slides greater than or equal to that order.
     * If not set, it will place the slide at the end.
     *
     * @param array $data Data containing the 'order' key.
     * @param \App\Models\Slider $slider The slider object.
     * @param bool $minus Whether to decrement (`true`) or increment (`false`) the order of affected slides.
     * @return array The modified $data array with resolved order.
     */
    public function setSlideOrder($data, $slider, $minus = false)
    {
        if (isset($data['order'])) {
            if ($minus) {
                // Decrement order of slides starting from given order
                DB::update(
                    'update slides set `order` = `order` - 1 where slider_id = ? and `order` >= ?',
                    [$slider->id, $data['order']]
                );
            } else {
                // Increment order of slides starting from given order
                DB::update(
                    'update slides set `order` = `order` + 1 where slider_id = ? and `order` >= ?',
                    [$slider->id, $data['order']]
                );
            }
        } else {
            // If no order is provided, place the slide at the end
            $maxOrder = $slider->slides()->max('order') ?? 0;
            $data['order'] = $maxOrder + 1;
        }

        return $data;
    }
}
