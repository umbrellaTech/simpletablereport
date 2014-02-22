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

/**
 * Description of XlsxDateTimeUtils
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxDateTimeUtils {
   
    protected static $START_DATE = null;
    protected static $TIMEZONE = null;
    
    public static function toTime(DateTime $endTime) {
        $seconds = ($endTime->format('H') * 60 * 60) +
                   ($endTime->format('i') * 60) +
                   ($endTime->format('i') + 0);
        $xlxSeconds = $seconds / 86400;
        return $xlxSeconds;
    }
   
    public static function toDate(DateTime $endDate) {
        if (!XlsxDateTimeUtils::$START_DATE) {
            XlsxDateTimeUtils::$START_DATE = DateTime::createFromFormat('Y-m-d H:i:s', '1900-01-01 00:00:00', DateTimeType::getDefaultDateTimeZone());
        };
        $diff = XlsxDateTimeUtils::$START_DATE->diff($endDate);
        $days = $diff->format('%a') + 2;
        return $days;
    }
    
}
