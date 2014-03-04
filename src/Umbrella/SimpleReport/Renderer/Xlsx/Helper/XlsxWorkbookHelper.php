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
class XlsxWorkbookHelper extends XlsxBaseHelper {

    public function render() {
        return  '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n" . 
                '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
                . '<fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="9303"/>'
                . '<workbookPr defaultThemeVersion="124226"/>'
                . '<bookViews><workbookView xWindow="240" yWindow="135" windowWidth="20115" windowHeight="8010"/></bookViews>'
                . '<sheets><sheet name="Dados" sheetId="1" r:id="rId1"/></sheets>'
                . '<calcPr calcId="145621"/>'
                . '</workbook>';
    }
   
}
