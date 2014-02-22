<?php

/*
 * Copyright 2014 kelsoncm <falecom@kelsoncm.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Umbrella\SimpleReport\Fields;

use DateTime;
use DateTimeZone;
use Umbrella\SimpleReport\Api\FieldType;

/**
 * Description of DateTimeType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
abstract class DateTimeType extends FieldType
{

    protected static $timezones = array();

    public static function getDefaultDateTimeZone()
    {
        if (!DateTimeType::$defaultTimezone) {
            $me = new DateTimeType();
            $timezoneName = $me->getOption('timezone');
            if (!isset(DateTimeType::$timezones[$timezoneName])) {
                DateTimeType::$timezones[$timezoneName] = new DateTimeZone($timezoneName);
            }
            DateTimeType::$defaultTimezone = DateTimeType::$timezones[$timezoneName];
        }
        return DateTimeType::$defaultTimezone;
    }

    public function format($value)
    {
        return is_null($value) ? '' : $value->format($this->getOption('toformat'));
        }

    protected function getDateTimeZone()
    {
        return DateTimeType::getDefaultDateTimeZone();
    }

    public function toPrimitiveType($value)
    {
        if (is_string($value)) {
            $value = trim("$value");
        }
        if (empty($value)) {
            return null;
        } else if ($value instanceof DateTime) {
            return $value;
        } else if (is_int($value)) {
            $date = new DateTime();
            return $date->setTimestamp($value);
        }
        return $value;
    }

}
