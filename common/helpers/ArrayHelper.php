<?php

namespace common\helpers;


class ArrayHelper extends \yii\helpers\ArrayHelper
{

    public static function mapWithCountForSameKeys($array, $from, $to)
    {
        $results = [];
        foreach ($array as $element) {
            $key = static::getValue($element, $from);
            $value = static::getValue($element, $to);

            if (array_key_exists($key, $results)) {
                $results[$key] = $value + $results[$key];
                continue;
            }

            $results[$key] = $value;
        }

        return $results;
    }

    public static function addPrefixToKeys($array, $prefix, $prefixSeparator = '_')
    {
        $results = [];
        foreach ($array as $key => $item) {
            $prefixedKey = $prefix . $prefixSeparator . $key;
            $results[$prefixedKey] = $item;
        }

        return $results;
    }
}