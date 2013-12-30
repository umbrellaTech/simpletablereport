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
 * Description of IntegerType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class IntegerType extends FieldType {
    
    public function format($value) {
        return sprintf("%d", $value);
    }

    public function toPrimitiveType($value) {
        if (is_string($value) && is_numeric($value)) {
            $value = 0 + $value;
        }
        if (is_integer($value) || is_long($value) || is_double($value) || is_float($value) || is_real($value)) {
            return (int)$value;
        }
        throw new Exception('Invalid integer.');
    }

}
