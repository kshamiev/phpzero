//  просмотр всех свойств объекта
function ObjectShowProps(obj, objName) {
    var result = "";
    for (var i in obj) // обращение к свойствам объекта по индексу
        result += objName + "." + i + " = " + obj[i] + "<br />\n";
    document.write(result);
    //alert(result);
}

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

//	календарь
function calendar(obj) {
    //	x=event.x+10; y=event.y+90; //	e.clientX or e.pageX
    x = 0;
    y = 0;
    newWindow = window.open("/library/calendar.php?obj=" + obj, "calendar", "left=" + x + ", top=" + y + ", width=256px, height=216px, toolbar=0, location=0, directories=0, status=1, menubar=0, scrollbars=1, resizable=1");
    newWindow.focus();
    return false;
}
//	скрытие, раскрытие блока
function show(obj) {
    obj = document.getElementById(obj);
    if ("none" == obj.style.display)
        obj.style.display = "block";
    else
        obj.style.display = "none";
}
