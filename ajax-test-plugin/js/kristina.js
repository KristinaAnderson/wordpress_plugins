jQuery(document).ready(function($){
    	
	var frm = $('#ajax_form');
	var fname = $('#fname').val();
	
	jQuery(function () {
    
    frm.submit(function (ev) {
		alert( fname );
		//console.log(frm.serialize());
        $.ajax({
            type: "POST",
            url: settings.ajax_url,
            data:  {"action": "send_ajax", "name": "Kristina"},
            success: function (data) {
                alert('ok');
            },
			 error: function (jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'aubort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        alert(msg);
    }
        });
        ev.preventDefault();
    });
});

});