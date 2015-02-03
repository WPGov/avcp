document.getElementById("avcp_data_inizio").setAttribute("readonly", "true");
document.getElementById("avcp_data_fine").setAttribute("readonly", "true");
document.getElementById("avcp_cig").setAttribute("onkeyup", "validcig(this)");


$('label[for=avcp_aggiudicazione]').parent().parent().parent().css('border-top','1px solid grey');
$('label[for=avcp_aggiudicazione]').parent().parent().parent().prepend('<center><small>Gli importi vanno inseriti nel formato 12345<strong>.</strong>67 o, in assenza di decimali, 12345<strong>.00</strong></small></center><br>');
$('label[for=avcp_aggiudicazione').parent().parent().css('float','left');
$('label[for=avcp_aggiudicazione').parent().parent().css('width','100%');

$('label[for=avcp_s_l_2013]').parent().parent().parent().css('float','left');
$('label[for=avcp_s_l_2014]').parent().parent().parent().css('float','left');
$('label[for=avcp_s_l_2015]').parent().parent().parent().css('float','left');
$('label[for=avcp_s_l_2016]').parent().parent().parent().css('float','left');
$('label[for=avcp_s_l_2017]').parent().parent().parent().css('float','left');
$('label[for=avcp_s_l_2018]').parent().parent().parent().css('float','left');

$('label[for=avcp_s_l_2013]').parent().parent().parent().css('width','50%');
$('label[for=avcp_s_l_2014]').parent().parent().parent().css('width','50%');
$('label[for=avcp_s_l_2015]').parent().parent().parent().css('width','50%');
$('label[for=avcp_s_l_2016]').parent().parent().parent().css('width','50%');
$('label[for=avcp_s_l_2017]').parent().parent().parent().css('width','50%');
$('label[for=avcp_s_l_2018]').parent().parent().parent().css('width','50%');

$('#annirif-tabs li').first().remove();
$('#annirif-tabs li').first().remove();
$('#areesettori-tabs li').first().remove();
$('#areesettori-tabs li').first().remove();


document.getElementById("avcp_data_inizio").setAttribute("onchange", "datespan()");
document.getElementById("avcp_data_fine").setAttribute("onchange", "datespan()");

document.getElementById("avcp_s_l_2013").setAttribute("onchange", "datespan()");
document.getElementById("avcp_s_l_2014").setAttribute("onchange", "datespan()");
document.getElementById("avcp_s_l_2015").setAttribute("onchange", "datespan()");
document.getElementById("avcp_s_l_2016").setAttribute("onchange", "datespan()");
document.getElementById("avcp_s_l_2017").setAttribute("onchange", "datespan()");
document.getElementById("avcp_s_l_2018").setAttribute("onchange", "datespan()");

function datespan() {
    var jdate1 = document.getElementById("avcp_data_inizio").value.slice(-4);
    var jdate2 = document.getElementById("avcp_data_fine").value.slice(-4);

    if (jdate1 != '' && jdate2 != '') { //Controlla se entrambe le date sono inserite
			var counter_i = 0;
			for ( counter_i = jdate1; counter_i < (jdate2*1+1); counter_i++) {
				year_check(counter_i);
			}
	}
	
	if (document.getElementById("avcp_s_l_2013").value > 0) {
		year_check('2013');
	}
	if (document.getElementById("avcp_s_l_2014").value > 0) {
		year_check('2014');
	}
	if (document.getElementById("avcp_s_l_2015").value > 0) {
		year_check('2015');
	}
	if (document.getElementById("avcp_s_l_2016").value > 0) {
		year_check('2016');
	}
	if (document.getElementById("avcp_s_l_2017").value > 0) {
		year_check('2017');
	}
	if (document.getElementById("avcp_s_l_2018").value > 0) {
		year_check('2018');
	}
}

function year_check(year) {
    $("label:contains('" + year + "')").find("input").prop( "checked", true );
}
function year_uncheck(year) {
    $("label:contains('" + year + "')").find("input").prop( "checked", false );
}

function validcig(f) {
    f.value = f.value.replace(/[^A-Z0-9-\s]/ig,'');
    if(f.value.length != '10'){
        $('#avcp_cig').css( "background-color", "yellow" );
    } else if (f.value != '0000000000') {
        $('#avcp_cig').css( "background-color", "lime" );
    } else {
        $('#avcp_cig').css( "background-color", "white" );
    }
}

function formatImporto(value, len) {

    //    if (!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value) )value=0;
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

jQuery(document).ready(function(){
	if (jQuery('#avcp_s_l_2013').length > 0){
        jQuery('#avcp_s_l_2013').change(function(){
            formattaimporto('#avcp_s_l_2013');
		});
    }
	if (jQuery('#avcp_s_l_2014').length > 0){
        jQuery('#avcp_s_l_2014').change(function(){
            formattaimporto('#avcp_s_l_2014');
        });
    }
	if (jQuery('#avcp_s_l_2015').length > 0){
        jQuery('#avcp_s_l_2015').change(function(){
            formattaimporto('#avcp_s_l_2015');
        });
    }
	if (jQuery('#avcp_s_l_2016').length > 0){
        jQuery('#avcp_s_l_2016').change(function(){
            formattaimporto('#avcp_s_l_2016');
        });
    }
	if (jQuery('#avcp_s_l_2017').length > 0){
        jQuery('#avcp_s_l_2017').change(function(){
            formattaimporto('#avcp_s_l_2017');
        });
    }
	if (jQuery('#avcp_s_l_2018').length > 0){
        jQuery('#avcp_s_l_2018').change(function(){
            formattaimporto('#avcp_s_l_2018');
        });
    }
	
})/* Italian initialisation for the jQuery UI date picker plugin. */
/* Written by Antonello Pasella (antonello.pasella@gmail.com). */
(function( factory ) {
    if ( typeof define === "function" && define.amd ) {

        // AMD. Register as an anonymous module.
        define([ "../datepicker" ], factory );
    } else {

        // Browser globals
        factory( jQuery.datepicker );
    }
}(function( datepicker ) {
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
