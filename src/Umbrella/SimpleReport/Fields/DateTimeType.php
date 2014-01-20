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

    public function format($value)
    {
        return $value ? $value->format($this->getOption('toformat')) : '';
    }

    protected function getDateTimeZone()
    {
        $timezoneName = $this->getOption('timezone');
        if (!isset(DateType::$timezones[$timezoneName])) {
            DateType::$timezones[$timezoneName] = new DateTimeZone($this->getOption('timezone'));
        }
        return DateType::$timezones[$timezoneName];
    }

}
