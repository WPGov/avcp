jQuery(document).ready(function($){
        document.getElementById("addMoneyRow").addEventListener("click", avcpAddRow);
});

function formatImporto(value, len) {

    decSeparator = '.';
    curSeparator = '';
    if (value == ""){
        return value;
    }
    value = formatImportoBack(value);
    if (isNaN(value)) {
        return("");
    }
    var tmp = value;

    if (decSeparator == ',') {
        var idx = tmp.indexOf('.');
        if (idx > 0) {
            tmp = tmp.substring(0, idx) + ',' + tmp.substring(idx + 1);
        }
    }
    var sgn = false;
    if (tmp.substring(0, 1) == '-') {
        sgn = true;
        tmp = tmp.substring(1);
    }
    var arr = tmp.split(decSeparator);

    var intPart = arr[0];
    var len = intPart.length;
    var rem = len % 3;

    var result = "";
    for (i = len - 3; i > 0; i -= 3)
        result = curSeparator + intPart.substr(i, 3) + result;
    result = intPart.substring(0, rem == 0 ? 3 : rem) + result;

    if (sgn)
        result = "-" + result;

    result += decSeparator;

    len = 0;
    if (arr.length > 1) {
        result += arr[1];
        len = arr[1].length;
    }

    for (i = len; i < 2; i++) {
        result += '0';
    }

    return result;

}

function formatImportoBack(value) {

    if (value == "")
        return value;

    value = value.replace(',', '.');
    return value;

}

function formattaimporto(id) {
    newval = formatImporto(jQuery(id).val(), 15);
    jQuery(id).val(newval);
}

((function( datepicker ) {
    datepicker.regional['it'] = {
        closeText: 'Chiudi',
        prevText: '&#x3C;Prec',
        nextText: 'Succ&#x3E;',
        currentText: 'Oggi',
        monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
            'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
        dayNames: ['Domenica','Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato'],
        dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
        dayNamesMin: ['Do','Lu','Ma','Me','Gi','Ve','Sa'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};
    datepicker.setDefaults(datepicker.regional['it']);

    return datepicker.regional['it'];

}));

function avcpAddRow( e ) {
    e.preventDefault();
    var e = document.getElementById("avcp_liquidazioni_add_year");
    var year = e.value;
    if ( year > 2000 ) {
        jQuery("#avcp_liquidazioni_add_year option[value='"+year+"']").remove();
        var table = document.getElementById("tab_liquidazioni").getElementsByTagName("tbody")[0];
        var row = table.insertRow(0);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        cell1.innerHTML = "<b>"+year+"</b>";
        cell2.innerHTML = '<input onchange="formattaimporto(\'#avcp_s_l_'+year+'\');updateLiqTot();" id="avcp_s_l_'+year+'" placeholder="0.00" type="text" name="avcp_s_l_'+year+'" id="avcp_s_l_'+year+'">';
    }
}