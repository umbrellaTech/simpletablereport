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
namespace Umbrella\SimpleReport\Fields;

/**
 * Description of FloatType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class FloatType extends NumericType
{
    protected $typeprefix = 'floattype';
    protected $precision;
    protected $separator;

    function __construct($options = null)
    {
        parent::__construct($options);
        $this->precision = intval($this->getOption("precision")) ? : 2;
        $this->separator = $this->getOption("separator") ? : '.';
    }
   /**
     * 
     * @param string $value
     * @return string
     */
    public function format($value)
    {
        return is_null($value) ? '' : number_format($this->toNumeric($value), $this->precision, ',', '.');
    }

    /**
     * 
     * @param string $value
     * @return string
     */
    public function toNumeric($value)
    {
        if (!preg_match('#(\,|\.)[\d]{2}$#', $value)) {
            $value = preg_match('#(\,|\.)[\d]{1}$#', $value) ? "{$value}0": "{$value}00";
        }
        return number_format(preg_replace('#[^\d]#', '', $value)/100, $this->precision, '.','');
    }
}