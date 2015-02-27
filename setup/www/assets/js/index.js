//  обработка кнопок
function button_form(f, Url, Action, flag) {
    if (0 < flag)
        if (false == confirm('вы действительно хотите сделать это!'))
            return false;
    if (typeof f == 'string')
        f = document.getElementById(f);
    f.action = Url;
    f.act.value = Action;
    f.submit();
    return true;
}

//  обработка кнопок с параметром операций
function button_form_obj_id(f, Url, Action, obj_id, flag) {
    if (0 < flag)
        if (false == confirm('вы действительно хотите сделать это!'))
            return false;
    if (typeof f == 'string')
        f = document.getElementById(f);
    f.action = Url;
    f.act.value = Action;
    f.obj_id.value = obj_id;
    f.submit();
    return true;
}

jQuery(function() {
    jQuery(".datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        language: 'ru'
    });
jQuery(".datetimepicker").datetimepicker({
    dateFormat: "yy-mm-dd",
    timeFormat: "HH:mm:ss",
    language: 'ru'
    });
jQuery(".timepicker").timepicker({
    timeFormat: "HH:mm:ss",
    language: 'ru'
    });
});
