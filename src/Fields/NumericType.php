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

use Exception;
use Umbrella\SimpleReport\Api\FieldType;

/**
 * Description of NumericType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
abstract class NumericType extends FieldType
{

    public function toPrimitiveType($value)
    {
        if (is_null($value)) {
            return null;
        }
        if (is_string($value)) {
            $value = trim($value);
            if (empty($value)) {
                return null;
            } else {
                return $this->toNumeric($value);
            }
        }
        if (is_integer($value) || is_long($value) || is_double($value) || is_float($value) || is_real($value) || is_numeric($value)) {
            return $this->toNumeric($value);
        }
        throw new Exception("Invalid {$this->typeprefix}.");
    }

    abstract protected function toNumeric($value);
}
