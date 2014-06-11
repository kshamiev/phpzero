/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default', {
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
	imagesPath: CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

	// The templates definitions.
	templates: [
		{
		title: 'Image and Title',
		image: 'template1.gif',
		description: 'One main image with a title and text that surround the image.',
		html: '<h3>' +
			// Use src=" " so image is not filtered out by the editor as incorrect (src is required).
			'<img src=" " alt="" style="margin-right: 10px" height="100" width="100" align="left" />' +
			'Type the title here' +
			'</h3>' +
			'<p>' +
			'Type the text here' +
			'</p>'
	},
		{
		title: 'Strange Template',
		image: 'template2.gif',
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
		image: 'template3.gif',
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
	},
        {
            title: 'Заголовок(h2) 2 рисунка в шахмтном порядке 3 параграфа (ps100х100)',
            image: 'template1.gif',
            description: 'Заголовок 2 рисунка в шахмтном порядке 3 параграфа. Размер рисунков 100х100 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="right" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 2 рисунка в обратном шахмтном порядке 3 параграфа (ps100х100)',
            image: 'template1.gif',
            description: 'Заголовок 2 рисунка в обратном шахмтном порядке 3 параграфа. Размер рисунков 100х100 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="right" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка в шахмтном порядке 3 параграфа (ps100х100)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка в шахмтном порядке 3 параграфа. Размер рисунков 100х100 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>'+
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="right" />'+
                '</div>'+
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка в обратном шахмтном порядке 3 параграфа (ps100х100)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка в обратном шахмтном порядке 3 параграфа. Размер рисунков 100х100 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="right" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>'+
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />'+
                '</div>'+
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="right" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        }, {
            title: 'Заголовок(h2) 2 рисунка в шахмтном порядке 3 параграфа (ps200х200)',
            image: 'template1.gif',
            description: 'Заголовок 2 рисунка в шахмтном порядке 3 параграфа. Размер рисунков 200х200 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="right" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 2 рисунка в обратном шахмтном порядке 3 параграфа (ps200х200)',
            image: 'template1.gif',
            description: 'Заголовок 2 рисунка в обратном шахмтном порядке 3 параграфа. Размер рисунков 200х200 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="right" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка в шахмтном порядке 3 параграфа (ps200х200)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка в шахмтном порядке 3 параграфа. Размер рисунков 200х200 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>'+
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="right" />'+
                '</div>'+
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка в обратном шахмтном порядке 3 параграфа (ps200х200)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка в обратном шахмтном порядке 3 параграфа. Размер рисунков 200х200 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="right" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>'+
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />'+
                '</div>'+
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="right" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 1 большой рисунок слева 3 параграфа (ps250х250)',
            image: 'template1.gif',
            description: 'Заголовок 1 рисунк слева 3 параграфа. Размер рисунка 250х250 пикселов.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="250" width="250" align="left" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 1 большой рисунок слева 1 маленький рисунок справа 3 параграфа',
            image: 'template1.gif',
            description: 'Заголовок 1 большой рисунк слева 1 маленький рисунок справа 3 параграфа. Размер рисунков 250х250 пикселов и 100х100 пикселов, соответственно.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="250" width="250" align="left" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="250" width="250" align="right" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 1 большой рисунок слева 1 маленький рисунок слева 3 параграфа',
            image: 'template1.gif',
            description: 'Заголовок 1 большой рисунк слева 1 маленький рисунок слева 3 параграфа. Размер рисунков 250х250 пикселов и 100х100 пикселов, соответственно.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="250" width="250" align="left" />' +
                '</div>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<div>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="250" width="250" align="left" />' +
                '</div>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка с заголовками(h3) 3 параграфа (ps100x100)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка с заголовками 3 параграфа. Размер рисунков 100х100 пикселов. Риунки выровняны по левому краю.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка с заголовками(h3) 3 параграфа (ps100x100)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка с заголовками 3 параграфа. Размер рисунков 100х100 пикселов. 2 риунка выровняны по левому краю. 1 выровнен по правому краю(центральный).',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="right" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="100" width="100" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка с заголовками(h3) 3 параграфа (ps200x200)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка с заголовками 3 параграфа. Размер рисунков 200х200 пикселов. Риунки выровняны по левому краю.',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        },
        {
            title: 'Заголовок(h2) 3 рисунка с заголовками(h3) 3 параграфа (ps200x200)',
            image: 'template1.gif',
            description: 'Заголовок 3 рисунка с заголовками 3 параграфа. Размер рисунков 200х200 пикселов. 2 риунка выровняны по левому краю. 1 выровнен по правому краю(центральный).',
            html: '<h2>' +
                'Заголовок h2' +
                '</h2>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!!Параграф 1 к рисунку 1 !!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="right" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!!! Параграф 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>' +
                '<h3>' +
                '<img src=" " alt="" style="margin: 3px 10px" height="200" width="200" align="left" />' +
                'Заголовок писать здесь'+
                '</h3>' +
                '<p>' +
                '!! Параграф 3 к рисунку 2!!!!' +
                'Elementum. Odio, quis natoque tristique, odio, rhoncus duis in sed aenean lundium, enim nunc mid ut, sociis dolor dapibus? Tempor integer, facilisis tortor non tincidunt. Ut ac. Risus cum duis porta placerat. Elementum nascetur sociis ridiculus, ridiculus odio nec nisi et magnis? Parturient sagittis egestas. Ac urna scelerisque! Tortor hac hac! Nunc, sit platea integer elit porttitor purus parturient cursus pulvinar enim ultrices, dis. Sed pellentesque tortor in vut! Cum dis ac? Tincidunt a elementum aliquet egestas. A augue turpis. Est arcu, diam magna. Ut enim. Adipiscing ridiculus lectus pid, in nunc ac aliquam! Parturient! Dapibus nascetur mattis pulvinar massa! Urna lorem! Elit elementum, integer augue egestas eros lorem elementum purus cursus? Nec quis porttitor placerat, arcu magnis turpis risus.'+
                '</p>'
        }
    ]
} );
