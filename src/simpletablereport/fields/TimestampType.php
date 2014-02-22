<?php

/*
 * Copyright 2013 kelsoncm <falecom@kelsoncm.com>.
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
 * Description of TimestampType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class TimestampType extends DateTimeType {
    
    protected $typeprefix = 'timestamptype';

    public function toPrimitiveType($value) {
        $value = parent::toPrimitiveType($value);
        if (is_null($value) || $value instanceof DateTime) {
            return $value;
        }
        if (is_string($value)) {
            if (substr_count($value, $this->getOption('timeseparator')) == 1) {
                $value .= $this->getOption('timeseparator') . '00';
            }
            $d = DateTime::createFromFormat($this->getOption('fromlongformat'), $value, $this->getDateTimeZone());
            return $d;
        } else if ( is_array($value) ) {
            return DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%d-%02d-%02d %02d:%02d:%02d', $value['year'], $value['mon'], $value['mday'], $value['hours'], $value['minutes'], $value['seconds']), $this->getDateTimeZone());
        }
        throw new Exception('Invalid timestamp.');
    }

}
