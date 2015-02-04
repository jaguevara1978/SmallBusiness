function addCommas(nStr, fix) {
	nStr = nStr.toFixed(fix);
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function totalValueMultiply(value1, value2, resultField) {
	var num = value1 * value2;
	var ret = num;
	num = addCommas(num, 0);
	resultField.text(num);
	return ret;
}

function showHideView(link,div,hide,show){
	var text='';
	if($('#'+link).text()==hide) {
		$("#"+div).fadeOut(100);
		text=show;
	} else {
		$("#"+div).fadeIn(200);
		text=hide;
	}
	$("#"+link).text(text);
}