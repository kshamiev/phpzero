/**
 * Created with JetBrains PhpStorm.
 * User: 97
 * Date: 14.11.12
 * Time: 14:07
 * To change this template use File | Settings | File Templates.
 */

jQuery(function(){
    //текущая подотрасль
    var Directory_SubSector_ID = jQuery('select[data-link-prop=Directory_SubSector_ID]').attr('value');

    function RemoveSubSector()
    {
        jQuery('select[data-link-prop=Directory_SubSector_ID] option').each(function(){
            if( this.value != '' && this.value != 'NULL' && this.value != 'NOTNULL' )
                jQuery(this).remove();
        });
    }

    function LoadSubSector(sector_id)
    {
        if( sector_id )
        {
            var url = '/subsectorjson?ajax=1&sector_id=' + sector_id;
            jQuery.get(url, function(data){
                for(var index in data)
                {
                    var html = '<option value="' + data[index].ID + '" ' + (Directory_SubSector_ID == data[index].ID? 'selected' : '') + '>' + data[index].Name + '</option>';
                    jQuery('select[data-link-prop=Directory_SubSector_ID]').append(html);
                }
                Directory_SubSector_ID = 0;
            }, 'json');
        }
    }

    RemoveSubSector();
    LoadSubSector(jQuery('select[data-link-prop=Directory_Sector_ID]').attr('value'));

    jQuery('select[data-link-prop=Directory_Sector_ID]').on('change', function(){
        RemoveSubSector();
        LoadSubSector(this.value);
    });
});