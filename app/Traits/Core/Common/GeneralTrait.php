<?php

namespace App\Traits\Core\Common;

use App\Enum\Core\Web\RoutesNames;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait GeneralTrait
{
    /**
     * Resolves a route name from the RoutesNames enum (if defined).
     * Uses caching to optimize repeated lookups.
     *
     * @param string $route The route constant name (e.g., 'DASHBOARD').
     * @return string|null The corresponding route value or null if not defined.
     */
    public function resolveRouteName(string $route): ?string
    {
        // Create a unique cache key based on the route name
        $cacheKey = "resolve_route_name_" . strtoupper($route);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($route) {
            $normalizedRoute = strtoupper($route);

            // Check if constant is defined in RoutesNames enum
            if (defined(RoutesNames::class . '::' . $normalizedRoute)) {
                $routeEnum = constant(RoutesNames::class . '::' . $normalizedRoute);
                return $routeEnum->value;
            }

            return null;
        });
    }

    /**
     * Checks if a value exists in an array, and adds/removes it accordingly.
     * This modifies the original array by reference.
     *
     * @param array $array The array to update.
     * @param mixed $value The value to toggle (add if not present, remove if present).
     */
    public function checkAndUpdateArray(&$array, $value)
    {
        if (in_array($value, $array, true)) {
            // Value exists — remove it from array
            $array = array_diff($array, [$value]);
        } else {
            // Value does not exist — append it to array
            $array[] = $value;
        }
    }

    /**
     * Builds a key-value array suitable for <select> dropdowns.
     *
     * @param iterable $data Array of objects (or arrays) to build options from.
     * @param string $key The field name for the option value.
     * @param string $value The field name for the option label.
     * @param string $defaultValue Label for default empty option.
     * @param bool $ignoreDefaultValue Whether to skip the default empty option.
     * @return array Dropdown-compatible key-value array.
     */
    public function populateSelectorOption(
        iterable $data,
        string $key,
        string $value,
        string $defaultValue = "",
        bool $ignoreDefaultValue = false
    ): array {

        $options = [];

        // Add a default empty option if not ignored
        if (!$ignoreDefaultValue) {
            $options[''] = $defaultValue;
        }

        // Loop through each data item and extract the value
        foreach ($data as $item) {
            if (is_object($item) && isset($item->$key, $item->$value)) {
                $options[$item->$key] = $item->$value;
            } elseif (is_array($item) && isset($item[$key], $item[$value])) {
                $options[$item[$key]] = $item[$value];
            }
        }


        return $options;
    }

    /**
     * Finds the key/index of a value in an array using case-insensitive comparison.
     * Used for reverse lookups in configuration arrays.
     *
     * @param string $attributeToFind The value to look for (case-insensitive).
     * @param array $data The array to search in.
     * @return int|null The key of the found item or null if not found.
     */
    public function findAttributeKey(string $attributeToFind, array $data): ?int
    {
        foreach ($data as $key => $value) {
            if (strcasecmp($value, $attributeToFind) === 0) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Searches for a key in a multilingual config array.
     * Tries all supported languages ("ar", "fr", "en").
     * Uses cache to avoid repeated search.
     *
     * @param string $constantKey The key from `config/constants.php`.
     * @param string $searchValue The value to search for (case-insensitive).
     * @return int|null The key/index if found, or null otherwise.
     */
    public function findKeyInMultilingualArray(string $constantKey, string $searchValue): ?int
    {
        // Cache key includes constant name and lowercase value
        $cacheKey = "find_key_in_multilingual_array_{$constantKey}_" . strtolower($searchValue);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($constantKey, $searchValue) {
            $multilingualOptions = config("constants")[strtoupper($constantKey)] ?? [];

            // Try all supported languages
            foreach (["ar", "fr", "en"] as $languageCode) {
                if (isset($multilingualOptions[$languageCode])) {
                    // Find matching value's key (case-insensitive)
                    $foundKey = $this->findAttributeKey(strtolower($searchValue), $multilingualOptions[$languageCode]);
                    if ($foundKey !== null) {
                        return $foundKey;
                    }
                }
            }

            return null;
        });
    }


    public function buildAppUrl(?string $path = null, array $params = []): string
    {
        // Base URL from app configuration


        $routeName = $this->resolveRouteName($path);


        if($routeName){
        return route($routeName, $params);
       }
        $baseUrl = rtrim(config('app.url'), '/');

        // Normalize path
        $path = $path ? '/' . ltrim($path, '/') : '';

        // Build URL
        $url = $baseUrl . $path;

        // Add query string if provided
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
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
public function setSlideOrder(array $data, $slider, $slide = null)
{
    // If no order is provided → place at the end
    if (!isset($data['order'])) {
        $data['order'] = ($slider->slides()->max('order') ?? 0) + 1;
        return $data;
    }

    $newOrder = (int) $data['order'];

    // 🆕 CREATE
    if (!$slide) {
        DB::update(
            'UPDATE slides SET `order` = `order` + 1
             WHERE slider_id = ? AND `order` >= ?',
            [$slider->id, $newOrder]
        );

        return $data;
    }

    // ✏️ UPDATE
    $oldOrder = (int) $slide->order;

    // Order didn't change → do nothing
    if ($oldOrder === $newOrder) {
        return $data;
    }

    // Moving UP (e.g. 5 → 2)
    if ($newOrder < $oldOrder) {
        DB::update(
            'UPDATE slides SET `order` = `order` + 1
             WHERE slider_id = ?
               AND `order` >= ?
               AND `order` < ?',
            [$slider->id, $newOrder, $oldOrder]
        );
    }

    // Moving DOWN (e.g. 2 → 5)
    if ($newOrder > $oldOrder) {
        DB::update(
            'UPDATE slides SET `order` = `order` - 1
             WHERE slider_id = ?
               AND `order` <= ?
               AND `order` > ?',
            [$slider->id, $newOrder, $oldOrder]
        );
    }

    return $data;
}

}
