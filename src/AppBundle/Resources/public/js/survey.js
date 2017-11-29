$(document).ready(function() {
    $('input[type=radio]').change(function() {
    	$(this).parent('td').parent('tr').addClass("checked");
    });
});