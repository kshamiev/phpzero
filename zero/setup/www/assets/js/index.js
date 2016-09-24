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
    f.id.value = obj_id;
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
//	новое окно
function system_window(path, target, w, h, pos, face)
{
    if ( pos==1 )
    { posx=screen.width/2-w/2; posy=screen.height/2-h/2; }
    else
    { posx=0; posy=0; }
    if ( face==1 )
    { newWindow=window.open(path,target,"left="+posx+", top="+posy+", width="+w+"px, height="+h+"px, toolbar=1, location=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1"); }
    else
    { newWindow=window.open(path,target,"left="+posx+", top="+posy+", width="+w+"px, height="+h+"px, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1"); }
    newWindow.focus();
}
//	новое окно 2
function system_window2(path, w, h, pos, face)
{
    if ( pos==1 )
    { posx=screen.width/2-w/2; posy=screen.height/2-h/2; }
    else
    { posx=0; posy=0; }
    if ( face==1 )
    { window.open(path,"","left="+posx+", top="+posy+", width="+w+"px, height="+h+"px, toolbar=1, location=1, directories=1, status=1, menubar=1, scrollbars=1, resizable=1"); }
    else
    { window.open(path,"","left="+posx+", top="+posy+", width="+w+"px, height="+h+"px, toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1"); }
}
