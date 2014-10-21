// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'custom', {
    // The name of sub folder which hold the shortcut preview images of the
    // templates.
    imagesPath: CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

    // The templates definitions.
    templates: [
        {
            title: 'Заголовок 2 рисунка в шахмтном порядке 3 параграфа',
            image: 'template1.gif',
            description: 'Заголовок 2 рисунка в шахмтном порядке 3 параграфа.',
            html: '<h2>' +
                    'Заголовок h2'+
                  '</h2>' +
                  '<div>'+
                    '<img src=" " alt="" style="margin: 0 10px" height="100" width="100" align="left" />' +
                  '</div>'+
                  '<p>'+
                    'Параграф 1 к рисунку 1' +
                  '</p>'+
                  '<p>'+
                    'Параграф 2' +
                  '</p>'+
                  '<div>'+
                    '<img src=" " alt="" style="margin: 0 10px" height="100" width="100" align="right" />' +
                  '</div>'+
                  '<p>' +
                    'Параграф 3 к рисунку 2' +
                  '</p>'
        },
        {
            title: 'Strange Template',
            image: 'template1.gif',
            description: 'A template that defines two colums, each one with a title, and some text.',
            html: '<table cellspacing="0" cellpadding="0" style="width:100%" border="0">' +
                '<tr>' +
                '<td style="width:50%">' +
                '<h3>Title 1</h3>' +
                '</td>' +
                '<td></td>' +
                '<td style="width:50%">' +
                '<h3>Title 2</h3>' +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>' +
                'Text 1' +
                '</td>' +
                '<td></td>' +
                '<td>' +
                'Text 2' +
                '</td>' +
                '</tr>' +
                '</table>' +
                '<p>' +
                'More text goes here.' +
                '</p>'
        },
        {
            title: 'Text and Table',
            image: 'template1.gif',
            description: 'A title with some text and a table.',
            html: '<div style="width: 80%">' +
                '<h3>' +
                'Title goes here' +
                '</h3>' +
                '<table style="width:150px;float: right" cellspacing="0" cellpadding="0" border="1">' +
                '<caption style="border:solid 1px black">' +
                '<strong>Table title</strong>' +
                '</caption>' +
                '<tr>' +
                '<td>&nbsp;</td>' +
                '<td>&nbsp;</td>' +
                '<td>&nbsp;</td>' +
                '</tr>' +
                '<tr>' +
                '<td>&nbsp;</td>' +
                '<td>&nbsp;</td>' +
                '<td>&nbsp;</td>' +
                '</tr>' +
                '<tr>' +
                '<td>&nbsp;</td>' +
                '<td>&nbsp;</td>' +
                '<td>&nbsp;</td>' +
                '</tr>' +
                '</table>' +
                '<p>' +
                'Type the text here' +
                '</p>' +
                '</div>'
        }
    ]
} );
