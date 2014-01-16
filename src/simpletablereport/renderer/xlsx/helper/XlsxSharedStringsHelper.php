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
 * Description of XLSXSharedStringsHelper
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxSharedStringsHelper extends XlsxBaseHelper {
    
    private static $strings = array();
    private static $lastId = 0;
    
    public static function putIfNotExists($value) {
        $trimString = trim("$value");
        if (empty($trimString) || is_null($value)) {
            return null;
        }
        if (!isset(XlsxSharedStringsHelper::$strings[$value])) {
            XlsxSharedStringsHelper::$strings[$value] = XlsxSharedStringsHelper::$lastId;
            XlsxSharedStringsHelper::$lastId = XlsxSharedStringsHelper::$lastId + 1;
        }
        return XlsxSharedStringsHelper::$strings[$value];
    }
    
    public static function getIterator() {
        return new ArrayIterator(array_keys(XlsxSharedStringsHelper::$strings));
    }
    
    public static function count() {
        return XlsxSharedStringsHelper::$lastId-1;
    }
    
    public static function reset() {
        XlsxSharedStringsHelper::$strings = array();
        XlsxSharedStringsHelper::$lastId = 0;
    }

    public function renderSharedStrings() {
        $count = XlsxSharedStringsHelper::count();
        $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
        $result .= "<sst xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" count=\"{$count}\" uniqueCount=\"{$count}\">";
        foreach (XlsxSharedStringsHelper::getIterator() as $value) {
            $result .= "<si><t>{$value}</t></si>";
        }
        $result .= "</sst>";
        XlsxSharedStringsHelper::reset();
        return $result;
    }
   
}
