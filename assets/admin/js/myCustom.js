
$(document).on('click', '.are_you_sure', function() {

    var ret = confirm('هل فعلا تريد الحذف');

    if (!ret) {
        return false;
    }

});
