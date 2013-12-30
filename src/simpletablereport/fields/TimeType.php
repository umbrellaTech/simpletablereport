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
 * Description of TimeType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class TimeType extends FieldType {
    
    public function format($value) {
        return $value->format($this->getOption('timetype.toformat'));
    }

    public function toPrimitiveType($value) {
        if ($value instanceof DateTime) {
            return $value;
        } else if (is_string($value)) {
            if (substr_count($value, $this->getOption('timeseparator')) == 1) {
                return DateTime::createFromFormat($this->getOption('timetype.fromformat'), $value, $this->getOption('timezone'));
            } else {
                return DateTime::createFromFormat($this->getOption('timetype.fromlongformat'), $value, $this->getOption('timezone'));
            }
        } else if ( is_array($value) ) {
            $time = new DateTime();
            return $time->setTime ( $value['hours'], $value['minutes'], $value['seconds'] );
        } else if ( is_int($value) ) {
            $time = new DateTime();
            return $time->setTimestamp($value);
        }
        throw new Exception('Invalid time.');
    }

}
