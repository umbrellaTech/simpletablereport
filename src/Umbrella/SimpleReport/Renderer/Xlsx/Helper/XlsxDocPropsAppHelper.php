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

namespace Umbrella\SimpleReport\Renderer\Xlsx\Helper;

use Umbrella\SimpleReport\Renderer\Xlsx\Helper\XlsxBaseHelper;

/**
 * Description of XLSXSharedStringsHelper
 *
 * @author kelsoncm <falecom@kelsoncm.com>
 */
class XlsxDocPropsAppHelper extends XlsxBaseHelper {

    public function render() {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n" .
        '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
                . '<Application>Microsoft Excel</Application>'
                . '<DocSecurity>0</DocSecurity>'
                . '<ScaleCrop>false</ScaleCrop>'
                . '<HeadingPairs>'
                . '<vt:vector size="2" baseType="variant">'
                . '<vt:variant>vt:lpstr>Planilhas</vt:lpstr></vt:variant>'
                . '<vt:variant><vt:i4>1</vt:i4></vt:variant>'
                . '</vt:vector>'
                . '</HeadingPairs>'
                . '<TitlesOfParts>'
                . '<vt:vector size="1" baseType="lpstr"><vt:lpstr>Dados</vt:lpstr></vt:vector>'
                . '</TitlesOfParts>'
                . '<LinksUpToDate>false</LinksUpToDate>'
                . '<SharedDoc>false</SharedDoc>'
                . '<HyperlinksChanged>false</HyperlinksChanged>'
                . '<AppVersion>14.0300</AppVersion>'
                . '</Properties>';
    }
   
}
