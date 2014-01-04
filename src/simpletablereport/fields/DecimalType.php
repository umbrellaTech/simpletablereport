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
 * Description of DecimalType
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class DecimalType extends FloatType {
    
    protected $typeprefix='decimaltype';
    protected $precision;
    protected $separator;
    
    function __construct($options) {
        parent::__construct($options);
        $this->precision = intval($this->getOption("precision")) ?: 2;
        $this->separator = $this->getOption("separator") ?: '.';
    }

    public function format($value) {
        return is_null($value) ? '' : str_replace('.', $this->separator, sprintf("%.{$this->precision}F", $value));
    }
  
}
