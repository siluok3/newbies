/**
 * Created by kpapachristou on 6/23/17.
 */
$('#submit').prop("disabled", true);
$('input:checkbox').click(function() {
    if ($(this).is(':checked')) {
        $('#submit').prop("disabled", false);
    } else {
        if ($('.checkbox-inline').filter(':checked').length < 1){
            $('#submit').attr('disabled',true);}
    }
});