(function(i) {
    "function" === typeof define && define.amd ? define(["jquery", "datatables.net", "datatables.net-buttons"], function(j) {
        return i(j, window, document)
    }) : "object" === typeof exports ? module.exports = function(j, l, v, u) {
        j || (j = window);
        if (!l || !l.fn.dataTable) l = require("datatables.net")(j, l).$;
        l.fn.dataTable.Buttons || require("datatables.net-buttons")(j, l);
        return i(l, j, j.document, v, u)
    } : i(jQuery, window, document)
})(function(i, j, l, v, u, s) {
    function z(a) {
        for (var b = ""; 0 <= a;) b = String.fromCharCode(a % 26 + 65) + b, a = Math.floor(a /
            26) - 1;
        return b
    }

    function A(a, b) {
        w === s && (w = -1 === y.serializeToString(i.parseXML(B["xl/worksheets/sheet1.xml"])).indexOf("xmlns:r"));
        i.each(b, function(d, c) {
            if (i.isPlainObject(c)) {
                var b = a.folder(d);
                A(b, c)
            } else {
                if (w) {
                    var b = c.childNodes[0],
                        g, e, n = [];
                    for (g = b.attributes.length - 1; 0 <= g; g--) {
                        e = b.attributes[g].nodeName;
                        var h = b.attributes[g].nodeValue; - 1 !== e.indexOf(":") && (n.push({
                            name: e,
                            value: h
                        }), b.removeAttribute(e))
                    }
                    g = 0;
                    for (e = n.length; g < e; g++) h = c.createAttribute(n[g].name.replace(":", "_dt_b_namespace_token_")),
                        h.value = n[g].value, b.setAttributeNode(h)
                }
                b = y.serializeToString(c);
                w && (-1 === b.indexOf("<?xml") && (b = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' + b), b = b.replace(/_dt_b_namespace_token_/g, ":"));
                b = b.replace(/<([^<>]*?) xmlns=""([^<>]*?)>/g, "<$1 $2>");
                a.file(d, b)
            }
        })
    }

    function q(a, b, d) {
        var c = a.createElement(b);
        d && (d.attr && i(c).attr(d.attr), d.children && i.each(d.children, function(a, b) {
            c.appendChild(b)
        }), null !== d.text && d.text !== s && c.appendChild(a.createTextNode(d.text)));
        return c
    }

    function K(a, b) {
        //var d = a.header[b].length        
        for (var d = 0, o = 0; o<a.header.length; o++) d = Math.max(d, a.header[o][b].length); // gets the largest width of column b
        var c;
        a.footer && a.footer[b].length > d && (d = a.footer[b].length);
        for (var f = 0, g = a.body.length; f < g; f++)
            if (c = a.body[f][b], c = null !== c && c !== s ? c.toString() : "", -1 !== c.indexOf("\n") ? (c = c.split("\n"), c.sort(function(a, c) {
                    return c.length - a.length
                }), c = c[0].length) : c = c.length, c > d && (d = c), 40 < d) return 52;
        d *= 1.3;
        return 6 < d ? d : 6
    }
    var o = i.fn.dataTable,
        t;
    var h = "undefined" !== typeof self && self || "undefined" !== typeof j && j || this.content;
    if ("undefined" === typeof h || "undefined" !== typeof navigator && /MSIE [1-9]\./.test(navigator.userAgent)) t =
        void 0;
    else {
        var x = h.document.createElementNS("http://www.w3.org/1999/xhtml", "a"),
            L = "download" in x,
            M = /constructor/i.test(h.HTMLElement) || h.safari,
            C = /CriOS\/[\d]+/.test(navigator.userAgent),
            N = function(a) {
                (h.setImmediate || h.setTimeout)(function() {
                    throw a;
                }, 0)
            },
            D = function(a) {
                setTimeout(function() {
                    "string" === typeof a ? (h.URL || h.webkitURL || h).revokeObjectURL(a) : a.remove()
                }, 4E4)
            },
            E = function(a) {
                return /^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(a.type) ? new Blob([String.fromCharCode(65279),
                    a
                ], {
                    type: a.type
                }) : a
            },
            F = function(a, b, d) {
                d || (a = E(a));
                var c = this,
                    d = "application/octet-stream" === a.type,
                    f, g = function() {
                        for (var a = ["writestart", "progress", "write", "writeend"], a = [].concat(a), b = a.length; b--;) {
                            var d = c["on" + a[b]];
                            if ("function" === typeof d) try {
                                d.call(c, c)
                            } catch (e) {
                                N(e)
                            }
                        }
                    };
                c.readyState = c.INIT;
                if (L) f = (h.URL || h.webkitURL || h).createObjectURL(a), setTimeout(function() {
                    x.href = f;
                    x.download = b;
                    var a = new MouseEvent("click");
                    x.dispatchEvent(a);
                    g();
                    D(f);
                    c.readyState = c.DONE
                });
                else if ((C || d && M) && h.FileReader) {
                    var e =
                        new FileReader;
                    e.onloadend = function() {
                        var a = C ? e.result : e.result.replace(/^data:[^;]*;/, "data:attachment/file;");
                        h.open(a, "_blank") || (h.location.href = a);
                        c.readyState = c.DONE;
                        g()
                    };
                    e.readAsDataURL(a);
                    c.readyState = c.INIT
                } else f || (f = (h.URL || h.webkitURL || h).createObjectURL(a)), d ? h.location.href = f : h.open(f, "_blank") || (h.location.href = f), c.readyState = c.DONE, g(), D(f)
            },
            m = F.prototype;
        "undefined" !== typeof navigator && navigator.msSaveOrOpenBlob ? t = function(a, b, d) {
            b = b || a.name || "download";
            d || (a = E(a));
            return navigator.msSaveOrOpenBlob(a,
                b)
        } : (m.abort = function() {}, m.readyState = m.INIT = 0, m.WRITING = 1, m.DONE = 2, m.error = m.onwritestart = m.onprogress = m.onwrite = m.onabort = m.onerror = m.onwriteend = null, t = function(a, b, d) {
            return new F(a, b || a.name || "download", d)
        })
    }
    o.fileSave = t;
    var O = function(a) {
            var b = "Sheet1";
            a.sheetName && (b = a.sheetName.replace(/[\[\]\*\/\\\?\:]/g, ""));
            return b
        },
        G = function(a) {
            return a.newline ? a.newline : navigator.userAgent.match(/Windows/) ? "\r\n" : "\n"
        },
        H = function(a, b) {
            for (var d = G(b), c = a.buttons.exportData(b.exportOptions), f = b.fieldBoundary,
                    g = b.fieldSeparator, e = RegExp(f, "g"), n = b.escapeChar !== s ? b.escapeChar : "\\", i = function(a) {
                        for (var c = "", b = 0, d = a.length; b < d; b++) 0 < b && (c += g), c += f ? f + ("" + a[b]).replace(e, n + f) + f : a[b];
                        return c
                    }, h = b.header ? i(c.header) + d : "", j = b.footer && c.footer ? d + i(c.footer) : "", k = [], l = 0, m = c.body.length; l < m; l++) k.push(i(c.body[l]));
            
            /* ----- BEGIN added Code ----- */
    		if (b.header) {
    			for (h = [], l = 0, m = c.header.length; l < m; l++) h.push(i(c.header[l]));
    			h = h.join(d) + d;
			}
			/*END added code*/
            
            return {
                str: h + k.join(d) + j,
                rows: k.length
            }
        },
        I = function() {
            if (!(-1 !== navigator.userAgent.indexOf("Safari") && -1 === navigator.userAgent.indexOf("Chrome") && -1 === navigator.userAgent.indexOf("Opera"))) return !1;
            var a = navigator.userAgent.match(/AppleWebKit\/(\d+\.\d+)/);
            return a && 1 < a.length && 603.1 > 1 * a[1] ? !0 : !1
        };
    try {
        var y = new XMLSerializer,
            w
    } catch (P) {}
    var B = {
            "_rels/.rels": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
            "xl/_rels/workbook.xml.rels": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
            "[Content_Types].xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="xml" ContentType="application/xml" /><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" /><Default Extension="jpeg" ContentType="image/jpeg" /><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" /><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" /><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml" /></Types>',
            "xl/workbook.xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><fileVersion appName="xl" lastEdited="5" lowestEdited="5" rupBuild="24816"/><workbookPr showInkAnnotation="0" autoCompressPictures="0"/><bookViews><workbookView xWindow="0" yWindow="0" windowWidth="25600" windowHeight="19020" tabRatio="500"/></bookViews><sheets><sheet name="" sheetId="1" r:id="rId1"/></sheets></workbook>',
            "xl/worksheets/sheet1.xml": '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><sheetData/><mergeCells count="0"/></worksheet>',
            "xl/styles.xml": '<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" mc:Ignorable="x14ac" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"><numFmts count="6"><numFmt numFmtId="164" formatCode="#,##0.00_- [$$-45C]"/><numFmt numFmtId="165" formatCode="&quot;£&quot;#,##0.00"/><numFmt numFmtId="166" formatCode="[$€-2] #,##0.00"/><numFmt numFmtId="167" formatCode="0.0%"/><numFmt numFmtId="168" formatCode="#,##0;(#,##0)"/><numFmt numFmtId="169" formatCode="#,##0.00;(#,##0.00)"/></numFmts><fonts count="5" x14ac:knownFonts="1"><font><sz val="11" /><name val="Calibri" /></font><font><sz val="11" /><name val="Calibri" /><color rgb="FFFFFFFF" /></font><font><sz val="11" /><name val="Calibri" /><b /></font><font><sz val="11" /><name val="Calibri" /><i /></font><font><sz val="11" /><name val="Calibri" /><u /></font></fonts><fills count="6"><fill><patternFill patternType="none" /></fill><fill><patternFill patternType="none" /></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD9D9D9" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD99795" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6efce" /><bgColor indexed="64" /></patternFill></fill><fill><patternFill patternType="solid"><fgColor rgb="ffc6cfef" /><bgColor indexed="64" /></patternFill></fill></fills><borders count="2"><border><left /><right /><top /><bottom /><diagonal /></border><border diagonalUp="false" diagonalDown="false"><left style="thin"><color auto="1" /></left><right style="thin"><color auto="1" /></right><top style="thin"><color auto="1" /></top><bottom style="thin"><color auto="1" /></bottom><diagonal /></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" /></cellStyleXfs><cellXfs count="67"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="0" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="0" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="3" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="4" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="1" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="2" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="3" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="4" fillId="5" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="left"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="right"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment horizontal="fill"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment textRotation="90"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1"><alignment wrapText="1"/></xf><xf numFmtId="9"   fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="164" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="165" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="166" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="167" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="168" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="169" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="3" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="4" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="1" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/><xf numFmtId="2" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/></cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0" /></cellStyles><dxfs count="0" /><tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleMedium4" /></styleSheet>'
        },
        J = [{
            match: /^\-?\d+\.\d%$/,
            style: 60,
            fmt: function(a) {
                return a / 100
            }
        }, {
            match: /^\-?\d+\.?\d*%$/,
            style: 56,
            fmt: function(a) {
                return a / 100
            }
        }, {
            match: /^\-?\$[\d,]+.?\d*$/,
            style: 57
        }, {
            match: /^\-?£[\d,]+.?\d*$/,
            style: 58
        }, {
            match: /^\-?€[\d,]+.?\d*$/,
            style: 59
        }, {
            match: /^\-?\d+$/,
            style: 65
        }, {
            match: /^\-?\d+\.\d{2}$/,
            style: 66
        }, {
            match: /^\([\d,]+\)$/,
            style: 61,
            fmt: function(a) {
                return -1 * a.replace(/[\(\)]/g, "")
            }
        }, {
            match: /^\([\d,]+\.\d{2}\)$/,
            style: 62,
            fmt: function(a) {
                return -1 * a.replace(/[\(\)]/g, "")
            }
        }, {
            match: /^\-?[\d,]+$/,
            style: 63
        }, {
            match: /^\-?[\d,]+\.\d{2}$/,
            style: 64
        }];
    o.ext.buttons.copyHtml5 = {
        className: "buttons-copy buttons-html5",
        text: function(a) {
            return a.i18n("buttons.copy", "Copy")
        },
        action: function(a, b, d, c) {
            this.processing(!0);
            var f = this,
                a = H(b, c),
                g = b.buttons.exportInfo(c),
                e = G(c),
                n = a.str,
                d = i("<div/>").css({
                    height: 1,
                    width: 1,
                    overflow: "hidden",
                    position: "fixed",
                    top: 0,
                    left: 0
                });
            g.title && (n = g.title + e + e + n);
            g.messageTop && (n = g.messageTop + e + e + n);
            g.messageBottom && (n = n + e + e + g.messageBottom);
            c.customize && (n = c.customize(n, c, b));
            c = i("<textarea readonly/>").val(n).appendTo(d);
            if (l.queryCommandSupported("copy")) {
                d.appendTo(b.table().container());
                c[0].focus();
                c[0].select();
                try {
                    var h = l.execCommand("copy");
                    d.remove();
                    if (h) {
                        b.buttons.info(b.i18n("buttons.copyTitle", "Copy to clipboard"), b.i18n("buttons.copySuccess", {
                            1: "Copied one row to clipboard",
                            _: "Copied %d rows to clipboard"
                        }, a.rows), 2E3);
                        this.processing(!1);
                        return
                    }
                } catch (j) {}
            }
            h = i("<span>" + b.i18n("buttons.copyKeys", "Press <i>ctrl</i> or <i>⌘</i> + <i>C</i> to copy the table data<br>to your system clipboard.<br><br>To cancel, click this message or press escape.") +
                "</span>").append(d);
            b.buttons.info(b.i18n("buttons.copyTitle", "Copy to clipboard"), h, 0);
            c[0].focus();
            c[0].select();
            var m = i(h).closest(".dt-button-info"),
                k = function() {
                    m.off("click.buttons-copy");
                    i(l).off(".buttons-copy");
                    b.buttons.info(!1)
                };
            m.on("click.buttons-copy", k);
            i(l).on("keydown.buttons-copy", function(a) {
                27 === a.keyCode && (k(), f.processing(!1))
            }).on("copy.buttons-copy cut.buttons-copy", function() {
                k();
                f.processing(!1)
            })
        },
        exportOptions: {},
        fieldSeparator: "\t",
        fieldBoundary: "",
        header: !0,
        footer: !1,
        title: "*",
        messageTop: "*",
        messageBottom: "*"
    };
    o.ext.buttons.csvHtml5 = {
        bom: !1,
        className: "buttons-csv buttons-html5",
        available: function() {
            return j.FileReader !== s && j.Blob
        },
        text: function(a) {
            return a.i18n("buttons.csv", "CSV")
        },
        action: function(a, b, d, c) {
            this.processing(!0);
            var a = H(b, c).str,
                d = b.buttons.exportInfo(c),
                f = c.charset;
            c.customize && (a = c.customize(a, c, b));
            !1 !== f ? (f || (f = l.characterSet || l.charset), f && (f = ";charset=" + f)) : f = "";
            c.bom && (a = "﻿" + a);
            t(new Blob([a], {
                type: "text/csv" + f
            }), d.filename, !0);
            this.processing(!1)
        },
        filename: "*",
        extension: ".csv",
        exportOptions: {},
        fieldSeparator: ",",
        fieldBoundary: '"',
        escapeChar: '"',
        charset: null,
        header: !0,
        footer: !1
    };
    o.ext.buttons.excelHtml5 = {
        className: "buttons-excel buttons-html5",
        available: function() {
            return j.FileReader !== s && (v || j.JSZip) !== s && !I() && y
        },
        text: function(a) {
            return a.i18n("buttons.excel", "Excel")
        },
        action: function(a, b, d, c) {
            this.processing(!0);
            var f = this,
                g = 0,
                a = function(a) {
                    return i.parseXML(B[a])
                },
                e = a("xl/worksheets/sheet1.xml"),
                h = e.getElementsByTagName("sheetData")[0],
                a = {
                    _rels: {
                        ".rels": a("_rels/.rels")
                    },
                    xl: {
                        _rels: {
                            "workbook.xml.rels": a("xl/_rels/workbook.xml.rels")
                        },
                        "workbook.xml": a("xl/workbook.xml"),
                        "styles.xml": a("xl/styles.xml"),
                        worksheets: {
                            "sheet1.xml": e
                        }
                    },
                    "[Content_Types].xml": a("[Content_Types].xml")
                },
                d = b.buttons.exportData(c.exportOptions),
                l, m, r = function(a) {
                    l = g + 1;
                    m = q(e, "row", {
                        attr: {
                            r: l
                        }
                    });
                    for (var b = 0, d = a.length; b < d; b++) {
                        var f = z(b) + "" + l,
                            j = null;
                        if (null === a[b] || a[b] === s || "" === a[b])
                            if (!0 === c.createEmptyCells) a[b] = "";
                            else continue;
                        var k = a[b];
                        a[b] = i.trim(a[b]);
                        for (var o = 0, r = J.length; o < r; o++) {
                            var p = J[o];
                            if (a[b].match && !a[b].match(/^0\d+/) && a[b].match(p.match)) {
                                j = a[b].replace(/[^\d\.\-]/g, "");
                                p.fmt && (j = p.fmt(j));
                                j = q(e, "c", {
                                    attr: {
                                        r: f,
                                        s: p.style
                                    },
                                    children: [q(e, "v", {
                                        text: j
                                    })]
                                });
                                break
                            }
                        }
                        j || ("number" === typeof a[b] || a[b].match && a[b].match(/^-?\d+(\.\d+)?$/) && !a[b].match(/^0\d+/) ? j = q(e, "c", {
                            attr: {
                                t: "n",
                                r: f
                            },
                            children: [q(e, "v", {
                                text: a[b]
                            })]
                        }) : (k = !k.replace ? k : k.replace(/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]/g, ""), j = q(e, "c", {
                            attr: {
                                t: "inlineStr",
                                r: f
                            },
                            children: {
                                row: q(e,
                                    "is", {
                                        children: {
                                            row: q(e, "t", {
                                                text: k,
                                                attr: {
                                                    "xml:space": "preserve"
                                                }
                                            })
                                        }
                                    })
                            }
                        })));
                        m.appendChild(j)
                    }
                    h.appendChild(m);
                    g++
                };
            i("sheets sheet", a.xl["workbook.xml"]).attr("name", O(c));
            c.customizeData && c.customizeData(d);
            var k = function(a, b) {
                    var c = i("mergeCells", e);
                    c[0].appendChild(q(e, "mergeCell", {
                        attr: {
                            ref: "A" + a + ":" + z(b) + a
                        }
                    }));
                    c.attr("count", parseFloat(c.attr("count")) + 1);
                    i("row:eq(" + (a - 1) + ") c", e).attr("s", "51")
                },
                p = b.buttons.exportInfo(c);
            p.title && (r([p.title], g), k(g, /*d.header.length*/ d.header[d.header.length - 1].length - 1));
            p.messageTop && (r([p.messageTop],
                g), k(g, /*d.header.length*/ d.header[d.header.length - 1].length - 1));

            /* ----- BEGIN added Code ----- */
            //c.header && (r(d.header, g), i("row:last c", e).attr("s", "2"));

			if(c.header){
				row_start_headers = g;
				for (o = 0; o < d.header.length; o++){
	                  r(d.header[o], g);
	                  i("row:last c", e).attr("s", "2");
	                }
            }

            // process merged cells:
            var mgCnt = 0;
            var merges=[];
            if (c.header) {
                //for each header row
                for(o=0; o < d.header.length; o++)
                {
                  //for each column (cell) in the row
                  for(oj=0; oj<d.header[o].length; oj++)
                  {
                    //look for a non-colspan/rowspan cell
                    if(d.header[o][oj] != "")
                    {
                      var startRow = o;
                      var startCol = oj;
                      var endRow = o;
                      var endCol = oj;
                                           
                      //lookahead
                      if(oj+1 < d.header[o].length){ 
	                      if(d.header[o][oj+1] == "") //is the cell to the right a colspan?
	                      { 
	                        
	                        startCol = oj;
	                        endCol = oj+1;

	                        //get to the last column in the colspan
	                        while(endCol < d.header[o].length && d.header[o][endCol] == "")
	                        {
	                          endCol++;
	                        }
	                        endCol--;
	                      }
	                   }
                      
                      if(o+1 < d.header.length) {
	                      if(d.header[o+1][oj] == "") //is the cell below a rowspan?
	                      {  
	                        
	                        startRow = o;
	                        endRow = o+1;

	                        //get to the last row in the rowspan
	                        while(endRow < d.header.length && d.header[endRow][oj] == "")
	                        {
	                          endRow++;
	                        }
	                      }
	                  }
                      
                      //create and store merge ranges
                      //if endCol or endRow show movement
                      if(startRow != endRow || startCol != endCol)
                      {
                        sC = z(startCol); //convert startCol to excel column letter
                        sR = startRow+1+row_start_headers; // actual row to be merged needs to take into account 0 index and current row (g)
                        eC = z(endCol); //convert endCol to excel column letter
                        eR = endRow+row_start_headers; // actual row to be merged needs to take into account 0 index and current row (g)
                                                
                        merges[mgCnt] = sC+""+sR; //start of range
                        
                        if(endCol > startCol){ //end column
                          merges[mgCnt] = merges[mgCnt] + ":" + eC;
                        } else {
                          merges[mgCnt] = merges[mgCnt] + ":" + sC;
                        }
                        
                        if(endRow > startRow) { //end row
                          merges[mgCnt] = merges[mgCnt] + eR;
                        } else {
                          merges[mgCnt] = merges[mgCnt] + sR;
                        }
                                                
                        mgCnt++; //increment number of merge ranges
                      }
                    }
                  }
                }               
            }


            if (mgCnt > 0) {
              //add each merge range as a child
              var oc = i("mergeCells", e);
              for(o=0;o<mgCnt;o++)
              {

              	oc[0].appendChild(q(e, "mergeCell", {
                        attr: {
                            ref: merges[o]
                        }
                    }));
              	oc.attr("count", parseFloat(oc.attr("count")) + 1);
              }
            }

            /*END added code*/
            for (var o = 0, u = d.body.length; o < u; o++) r(d.body[o], g);
            c.footer && d.footer && (r(d.footer, g), i("row:last c", e).attr("s", "2"));
            p.messageBottom && (r([p.messageBottom], g), k(g, /*d.header.length*/d.header[d.header.length - 1].length - 1));
            r = q(e, "cols");
            i("worksheet", e).prepend(r);
            k = 0;
            for (o = /*d.header.length*/d.header[d.header.length - 1].length; k < o; k++) r.appendChild(q(e, "col", {
                attr: {
                    min: k + 1,
                    max: k + 1,
                    width: K(d, k),
                    customWidth: 1
                }
            }));
            c.customize && c.customize(a, c, b);
            0 === i("mergeCells", e).children().length && i("mergeCells",
                e).remove();
            b = new(v || j.JSZip);
            d = {
                type: "blob",
                mimeType: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            };
            A(b, a);
            b.generateAsync ? b.generateAsync(d).then(function(a) {
                t(a, p.filename);
                f.processing(false)
            }) : (t(b.generate(d), p.filename), this.processing(!1))
        },
        filename: "*",
        extension: ".xlsx",
        exportOptions: {},
        header: !0,
        footer: !1,
        title: "*",
        messageTop: "*",
        messageBottom: "*",
        createEmptyCells: !1
    };
    o.ext.buttons.pdfHtml5 = {
        className: "buttons-pdf buttons-html5",
        available: function() {
            return j.FileReader !==
                s && (u || j.pdfMake)
        },
        text: function(a) {
            return a.i18n("buttons.pdf", "PDF")
        },
        action: function(a, b, d, c) {
            this.processing(!0);
            var d = b.buttons.exportData(c.exportOptions),
                a = b.buttons.exportInfo(c),
                f = [];
            /* ----- BEGIN added Code ----- */
            /*c.header && f.push(i.map(d.header, function(a) {
                return {
                    text: "string" === typeof a ? a : a + "",
                    style: "tableHeader"
                }
            }));
            */
            if(c.header){
	            for(g = 0; g < d.header.length; g++)
		            f.push(i.map(d.header[g], function(a) {
		                return {
		                    text:  "string" ===  typeof a ? a : a + "",
		                    style: "tableHeader"
		                }
		            }));
	    	}
	    	/*END added code*/
            for (var g = 0, e = d.body.length; g < e; g++) f.push(i.map(d.body[g], function(a) {
                return {
                    text: "string" === typeof a ? a : a + "",
                    style: g % 2 ? "tableBodyEven" : "tableBodyOdd"
                }
            }));
            c.footer && d.footer && f.push(i.map(d.footer, function(a) {
                return {
                    text: "string" ===
                        typeof a ? a : a + "",
                    style: "tableFooter"
                }
            }));
            d = {
                pageSize: c.pageSize,
                pageOrientation: c.orientation,
                content: [{
                    table: {
                        headerRows: 1,
                        body: f
                    },
                    layout: "noBorders"
                }],
                styles: {
                    tableHeader: {
                        bold: !0,
                        fontSize: 11,
                        color: "white",
                        fillColor: "#2d4154",
                        alignment: "center"
                    },
                    tableBodyEven: {},
                    tableBodyOdd: {
                        fillColor: "#f3f3f3"
                    },
                    tableFooter: {
                        bold: !0,
                        fontSize: 11,
                        color: "white",
                        fillColor: "#2d4154"
                    },
                    title: {
                        alignment: "center",
                        fontSize: 15
                    },
                    message: {}
                },
                defaultStyle: {
                    fontSize: 10
                }
            };
            a.messageTop && d.content.unshift({
                text: a.messageTop,
                style: "message",
                margin: [0, 0, 0, 12]
            });
            a.messageBottom && d.content.push({
                text: a.messageBottom,
                style: "message",
                margin: [0, 0, 0, 12]
            });
            a.title && d.content.unshift({
                text: a.title,
                style: "title",
                margin: [0, 0, 0, 12]
            });
            c.customize && c.customize(d, c, b);
            b = (u || j.pdfMake).createPdf(d);
            "open" === c.download && !I() ? b.open() : b.download(a.filename);
            this.processing(!1)
        },
        title: "*",
        filename: "*",
        extension: ".pdf",
        exportOptions: {},
        orientation: "portrait",
        pageSize: "A4",
        header: !0,
        footer: !1,
        messageTop: "*",
        messageBottom: "*",
        customize: null,
        download: "download"
    };
    return o.Buttons
});