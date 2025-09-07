jQuery(function($){
    var forms = window.stkcGfScData ? (window.stkcGfScData.forms || {}) : {};
    var i18n  = window.stkcGfScData ? (window.stkcGfScData.i18n || {}) : {};

    function populateFields(formId, $fieldSelect, selected){
        var fields = forms[formId] ? forms[formId].fields : [];
        var opts = '<option value="">'+(i18n.selectField || '')+'</option>';
        for(var i=0;i<fields.length;i++){
            var f = fields[i];
            var sel = (f.id == selected) ? ' selected' : '';
            opts += '<option value="'+f.id+'"'+sel+'>'+f.label+'</option>';
        }
        $fieldSelect.html(opts);
    }

    function updatePreview($row){
        var form = $.trim($row.find('select[name$="[form_id]"]').val());
        var field = $.trim($row.find('select[name$="[field_id]"]').val());
        var preview = '';
        if(form && field){
            preview = '?eid={entry_id}&f'+form+'_'+field+'={Field:'+field+'}';
        }
        var $previewRow = $row.next('.stkc-preview-row');
        $previewRow.find('code').text(preview);
        $previewRow.find('.stkc-copy').attr('data-copy', preview);
    }

    $('.stkc-gf-mappings tbody').on('change', 'select[name$="[form_id]"]', function(){
        var $row = $(this).closest('tr');
        var $fieldSelect = $row.find('select[name$="[field_id]"]');
        populateFields($(this).val(), $fieldSelect, '');
        updatePreview($row);
    });

    $('.stkc-gf-mappings tbody').on('change', 'select[name$="[field_id]"]', function(){
        updatePreview($(this).closest('tr'));
    });

    $('.stkc-add-row').on('click', function(){
        var $tbody = $('.stkc-gf-mappings tbody');
        var index = $tbody.find('tr').length / 2; // two rows per mapping
        var tpl = $('#stkc-gf-sc-row-template').html().replace(/__index__/g, index);
        $tbody.append(tpl);
        var $rows = $tbody.find('tr').slice(-2);
        var $formSelect = $rows.eq(0).find('select[name$="[form_id]"]');
        var $fieldSelect = $rows.eq(0).find('select[name$="[field_id]"]');
        populateFields($formSelect.val(), $fieldSelect, '');
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

    $('.stkc-gf-mappings tbody tr').each(function(){
        var $tr = $(this);
        if(!$tr.hasClass('stkc-preview-row')){
            var $formSelect = $tr.find('select[name$="[form_id]"]');
            var $fieldSelect = $tr.find('select[name$="[field_id]"]');
            populateFields($formSelect.val(), $fieldSelect, $fieldSelect.val());
            updatePreview($tr);
        }
    });
});
