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

    function buildPreview(){
        var formParts = {};
        var scs       = [];
        $('.stkc-gf-mappings tbody tr').each(function(){
            var $row  = $(this);
            var form  = $.trim($row.find('input[name$="[form_id]"]').val());
            var field = $.trim($row.find('input[name$="[field_id]"]').val());
            var tag   = $.trim($row.find('input[name$="[tag]"]').val());
            if(form && field){
                formParts[form] = formParts[form] || [];
                formParts[form].push('f'+form+'_'+field+'={Field:'+field+'}');
            }
            if(tag){
                scs.push('['+tag+']');
            }
        });
        var $example = $('#stkc-gf-sc-example');
        $example.empty();
        $.each(formParts, function(fid, parts){
            var preview = '?eid={entry_id}';
            if(parts.length){
                preview += '&'+parts.join('&');
            }
            var $p = $('<p class="description"></p>');
            $p.append($('<code></code>').text(preview));
            $p.append(' ');
            $p.append($('<button type="button" class="button button-small stkc-copy"></button>').text('Copy').attr('data-copy', preview));
            $example.append($p);
        });
        $('#stkc-gf-sc-shortcodes').text(scs.join(' '));
    }

    $('.stkc-gf-mappings tbody').on('change', 'select[name$="[form_id]"]', function(){
        var $row = $(this).closest('tr');
        var val = $(this).val();
        var $fieldSelect = $row.find('select[name$="[field_id]"]');
        $row.find('input[name$="[form_id]"]').val(val);
        populateFields(val, $fieldSelect, '');
        buildPreview();
    });

    $('.stkc-gf-mappings tbody').on('change', 'select[name$="[field_id]"]', function(){
        var $row = $(this).closest('tr');
        var val = $(this).val();
        $row.find('input[name$="[field_id]"]').val(val);
        buildPreview();
    });

    $('.stkc-gf-mappings tbody').on('input', 'input[name$="[form_id]"], input[name$="[field_id]"], input[name$="[tag]"]', function(){
        buildPreview();
    });

    $('.stkc-add-row').on('click', function(){
        var $tbody = $('.stkc-gf-mappings tbody');
        var index = $tbody.find('tr').length;
        var tpl = $('#stkc-gf-sc-row-template').html().replace(/__index__/g, index);
        $tbody.append(tpl);
        var $row = $tbody.find('tr').last();
        var $formSelect = $row.find('select[name$="[form_id]"]');
        var $fieldSelect = $row.find('select[name$="[field_id]"]');
        populateFields($formSelect.val(), $fieldSelect, '');
        $row.find('input[name$="[form_id]"]').val($formSelect.val());
        $row.find('input[name$="[field_id]"]').val($fieldSelect.val());
        buildPreview();
    });

    $('.stkc-gf-mappings tbody').on('click', '.stkc-remove-row', function(){
        var $row = $(this).closest('tr');
        $row.remove();
        buildPreview();
    });
    $(document).on('click', '.stkc-copy', function(){
        var text = $(this).data('copy');
        if(text){
            navigator.clipboard.writeText(text);
        }
    });

    $('.stkc-gf-mappings tbody tr').each(function(){
        var $tr = $(this);
        var $formSelect = $tr.find('select[name$="[form_id]"]');
        var $fieldSelect = $tr.find('select[name$="[field_id]"]');
        populateFields($formSelect.val(), $fieldSelect, $fieldSelect.val());
        $tr.find('input[name$="[form_id]"]').val($formSelect.val());
        $tr.find('input[name$="[field_id]"]').val($fieldSelect.val());
    });
    buildPreview();
});

