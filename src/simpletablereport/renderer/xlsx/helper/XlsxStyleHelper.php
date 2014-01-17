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
class XlsxStyleHelper extends XlsxBaseHelper {
    public function renderStyle() {
        $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
        $result .= "<styleSheet xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" xmlns:mc=\"http://schemas.openxmlformats.org/markup-compatibility/2006\" mc:Ignorable=\"x14ac\" xmlns:x14ac=\"http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac\">";
        $result .= "<numFmts count=\"2\">";
        $result .=   "<numFmt numFmtId=\"44\" formatCode=\"_-&quot;R$&quot;\ * #,##0.00_-;\-&quot;R$&quot;\ * #,##0.00_-;_-&quot;R$&quot;\ * &quot;-&quot;??_-;_-@_-\"/>";
        $result .=   "<numFmt numFmtId=\"165\" formatCode=\"&quot;R$&quot;\ #,##0.00\"/>";
        $result .= "</numFmts>";
        $result .= "<fonts count=\"1\" x14ac:knownFonts=\"1\">";
        $result .=   "<font><sz val=\"11\"/><color theme=\"1\"/><name val=\"Calibri\"/><family val=\"2\"/><scheme val=\"minor\"/></font>";
        $result .= "</fonts>";
        $result .= "<fills count=\"2\">";
        $result .=   "<fill><patternFill patternType=\"none\"/></fill>";
        $result .=   "<fill><patternFill patternType=\"gray125\"/></fill>";
        $result .= "</fills>";
        $result .= "<borders count=\"1\">";
        $result .=   "<border><left/><right/><top/><bottom/><diagonal/></border>";
        $result .= "</borders>";
        $result .= "<cellStyleXfs count=\"1\">";
        $result .= "<xf numFmtId=\"0\" fontId=\"0\" fillId=\"0\" borderId=\"0\"/>";
        $result .= "</cellStyleXfs>";
        $result .= "<cellXfs count=\"6\">";
        $result .=   "<xf numFmtId=\"0\" fontId=\"0\" fillId=\"0\" borderId=\"0\" xfId=\"0\"/>";
        $result .=   "<xf numFmtId=\"2\" fontId=\"0\" fillId=\"0\" borderId=\"0\" xfId=\"0\" applyNumberFormat=\"1\"/>";
        $result .=   "<xf numFmtId=\"165\" fontId=\"0\" fillId=\"0\" borderId=\"0\" xfId=\"0\" applyNumberFormat=\"1\"/>";
        $result .=   "<xf numFmtId=\"44\" fontId=\"0\" fillId=\"0\" borderId=\"0\" xfId=\"0\" applyNumberFormat=\"1\"/>";
        $result .=   "<xf numFmtId=\"12\" fontId=\"0\" fillId=\"0\" borderId=\"0\" xfId=\"0\" applyNumberFormat=\"1\"/>";
        $result .=   "<xf numFmtId=\"49\" fontId=\"0\" fillId=\"0\" borderId=\"0\" xfId=\"0\" applyNumberFormat=\"1\"/>";
        $result .= "</cellXfs><cellStyles count=\"1\">";
        $result .= "<cellStyle name=\"Normal\" xfId=\"0\" builtinId=\"0\"/></cellStyles>";
        $result .= "<dxfs count=\"0\"/>";
        $result .= "<tableStyles count=\"0\" defaultTableStyle=\"TableStyleMedium2\" defaultPivotStyle=\"PivotStyleLight16\"/>";
        $result .= "<extLst>";
        $result .=   "<ext uri=\"{EB79DEF2-80B8-43e5-95BD-54CBDDF9020C}\" xmlns:x14=\"http://schemas.microsoft.com/office/spreadsheetml/2009/9/main\">";
        $result .=     "<x14:slicerStyles defaultSlicerStyle=\"SlicerStyleLight1\"/>";
        $result .=   "</ext>";
        $result .= "</extLst>";
        $result .= "</styleSheet>";
        return $result;
    }
   
}
