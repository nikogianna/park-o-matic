$("#decFile").change(function() {
    if ($(this).prop("checked") == true) {

        $('#decInputText').hide();
        $('#decInputFile').show();
        $('#decText').prop('checked', false);
    }
});

$("#decText").change(function() {
    if ($(this).prop("checked") == true) {
        $('#decInputText').show();
        $('#decInputFile').hide();
        $('#decFile').prop('checked', false);
    }
});

$("#AESdec").change(function() {
    if ($(this).prop("checked") == true) {

        $(this).val('true');
        $('#RSAdec').val('false');
    }
});

$("#RSAdec").change(function() {
    if ($(this).prop("checked") == true) {

        $(this).val('true');
        $('#AESdec').val('false');

    }
});
