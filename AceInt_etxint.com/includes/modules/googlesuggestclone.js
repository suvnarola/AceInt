var gsc_basicmatch = /[a-z0-9]/i;

function gsc_getquery(elt, q)
{
    q = ltrim(q);
    q = q.replace('\s+', ' ');
    if (q.length == 0 || !gsc_basicmatch.test(q)) {
        gsc_emptyresults(elt);
        return '';
    }

    if (elt.currentQuery && (elt.currentQuery == q || elt.tempQuery == q))
        return '';

    elt.currentQuery = q;
    return q;
}

function gsc_hide(elt)
{
    if (elt) elt.style.display = 'none';
}

function gsc_ishidden(elt)
{
    return elt.style.display == 'none';
}

function gsc_show(elt)
{
    if (elt) elt.style.display = 'block';
}

function gsc_emptyresults(elt)
{
    if (!elt) return;

    elt.innerHTML = '';
    elt.numResults = 0;
    elt.selectedIndex = 0;
    elt.results = [];
    gsc_hide(elt);
}

function gsc_addresult(elt, qElt, q, c, sel)
{
    if (!elt) return;

    if (sel) elt.selectedIndex = elt.numResults;

    idx = elt.numResults;
    elt.results[elt.numResults++] = q;

    var _res = '';
    _res += '<div class="' + (sel ? 'srs' : 'sr') + '"'
         +  ' onmouseover="gsc_mouseover(\'' + elt.id + '\', \'' + qElt.id + '\', ' + idx + ')"'
         +  ' onmouseout="gsc_mouseout(\'' + elt.id + '\', ' + idx + ')"'
         +  ' onclick="gsc_mouseclick(\'' + elt.id + '\', \'' + qElt.id + '\', ' + idx + ')">';
    _res += '<span class="srt">' + q + '</span>';
    if (c.length > 0)
        _res += '<span class="src">' + c + '</span>';
    _res += '</div>';

    elt.innerHTML += _res;
}

function gsc_mouseover(id, qId, idx)
{
    elt = document.getElementById(id);
    elt.selectedIndex = idx;
    qElt = document.getElementById(qId);
    qElt.focus();

    gsc_highlightsel(elt);
}

function gsc_mouseout(id, idx)
{
    elt = document.getElementById(id);
    elt.selectedIndex = -1;

    gsc_highlightsel(elt);
}

function gsc_mouseclick(id, qId, idx)
{
    elt = document.getElementById(id);
    qElt = document.getElementById(qId);

    qElt.value = elt.results[idx];
    qElt.form.submit();
}

function gsc_handleup(elt, qElt)
{
    if (elt.numResults > 0 && gsc_ishidden(elt)) {
        gsc_show(elt);
        return;
    }

    if (elt.selectedIndex == 0)
        return;
    else if (elt.selectedIndex < 0)
        elt.selectedIndex = elt.numResults - 1;
    else
        elt.selectedIndex--;
    gsc_highlightsel(elt, qElt);
}

function gsc_handledown(elt, qElt)
{
    if (elt.numResults > 0 && gsc_ishidden(elt)) {
        gsc_show(elt);
        return;
    }

    if (elt.selectedIndex == elt.numResults - 1)
        return;
    else if (elt.selectedIndex < 0)
        elt.selectedIndex = 0;
    else
        elt.selectedIndex++;
    gsc_highlightsel(elt, qElt);
}

function gsc_highlightsel(elt, qElt)
{
    divs = elt.getElementsByTagName('div');

    for (i = 0; i < divs.length; i++) {
        if (i == elt.selectedIndex) {
            divs[i].className = 'srs';
            elt.tempQuery = elt.results[i];

            if (qElt) {
                qElt.value = elt.results[i];
                if (qElt.createTextRange) {
                    r = qElt.createTextRange();
                    r.moveStart('character', elt.currentQuery.length);
                    r.moveEnd('character', qElt.value.length);
                    r.select();
                }
            }
        }
        else
            divs[i].className = 'sr';
    }
}

__query.onkeydown = function(e)
                            {
                                key = ajaxac_getkeycode(e);
                                switch (key) {
                                    case 27: // escape
                                        gsc_hide(__results);
                                        return false;
                                        break;
                                    case 38: // up arrow
                                        gsc_handleup(__results, __query);
                                        return false;
                                        break;
                                    case 40: // down arrow
                                        gsc_handledown(__results, __query);
                                        return false;
                                        break;
                                    default:
                                        __gsctimer.start();
                                }
                                return true;
                            }
__results.onload = function() { gsc_emptyresults(this); }

__results.onload();
__gsctimer = new ajaxac_countdowntimer('__gsctimer.ontimerexpire()', 350);
__gsctimer.ontimerexpire = function()
                            {
                                _q = gsc_getquery(__results, __query.value);
                                if (_q.length == 0)
                                    return false;
                                try {
                                    __gscfetch = ajaxac_createXMLHttp();

__gscfetch.open('get', '/includes/modules/mem_searchnew.php/getsuggestions' + '?' + 'q=' + encodeURIComponent(_q));

__gscfetch_xmlhttpsuccess = function()
                            {
                                _data = ajaxac_receivejsarray(__gscfetch.responseText);
                                gsc_emptyresults(__results);
                                if (_data.length > 0) {
                                    for (i = 0; i < _data.length; i++) {
                                        gsc_addresult(__results, __query, _data[i][0], _data[i][1], i == 0);
                                    }
                                    gsc_show(__results);
                                }
                            }

__gscfetch_onreadystatechange = function()
                            {
                                if (__gscfetch.readyState == 4 && __gscfetch.status == 200 && __gscfetch_xmlhttpsuccess) {
                                    __gscfetch_xmlhttpsuccess();
                                }
                            }

__gscfetch.onreadystatechange = __gscfetch_onreadystatechange;

__gscfetch.send(null);
                                }
                                catch (e) { }


                                return false;
                            }