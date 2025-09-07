jQuery(function($){
    function updatePreview($row){
        var form = $.trim($row.find('input[name$="[form_id]"]').val());
        var field = $.trim($row.find('input[name$="[field_id]"]').val());
        var preview = '';
        if(form && field){
            preview = '?eid={entry_id}&f'+form+'_'+field+'={Field:'+field+'}';
        }
        var $previewRow = $row.next('.stkc-preview-row');
        $previewRow.find('code').text(preview);
        $previewRow.find('.stkc-copy').attr('data-copy', preview);
    }

    $('.stkc-gf-mappings tbody').on('input', 'input[name$="[form_id]"], input[name$="[field_id]"]', function(){
        updatePreview($(this).closest('tr'));
    });

    $('.stkc-add-row').on('click', function(){
        var $tbody = $('.stkc-gf-mappings tbody');
        var index = $tbody.find('tr').length / 2; // two rows per mapping
        var tpl = $('#stkc-gf-sc-row-template').html().replace(/__index__/g, index);
        $tbody.append(tpl);
    });

    $('.stkc-gf-mappings tbody').on('click', '.stkc-remove-row', function(){
        var $row = $(this).closest('tr');
        $row.next('.stkc-preview-row').remove();
        $row.remove();
    });

    $('.stkc-gf-mappings tbody').on('click', '.stkc-copy', function(){
        var text = $(this).data('copy');
        if(text){
            navigator.clipboard.writeText(text);
        }
    });

    // Initial update for existing rows
    $('.stkc-gf-mappings tbody tr').each(function(){
        var $tr = $(this);
        if(!$tr.hasClass('stkc-preview-row')){
            updatePreview($tr);
        }
    });
});
