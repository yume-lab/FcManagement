/**
 * 勤怠一覧JS
 */

$(function() {
    var DATE_FORMAT = 'YYYYMM';
    /**
     * 従業員選択時
     */
    $(document).on('change', '#employee-list', function(e) {
        e.preventDefault();
        loadTimeCardTable($(this).val(), moment().format(DATE_FORMAT));
    });

    /**
     * 次月、前月クリック時
     */
    $(document).on('click', '.pagination a', function(e) {
        var employeeId = $('#employee-list').val();
        var target = $(this).data('target');
        loadTimeCardTable(employeeId, target);
        return false;
    });

    /**
     * 更新クリック時
     */
    $(document).on('click', '.editable-button a', function(e) {
        var $this = $(this);
        $('.editable-label').show();
        $('.editable-input').hide();
        $('.editable-button').show();
        $('.editable-actions').hide();

        var $parent = $this.parents('.time-row');
        $parent.find('.editable-label').hide();
        $parent.find('.editable-input').show();

        $parent.find('.editable-button').hide();
        $parent.find('.editable-actions').show();
        return false;
    });

    /**
     * 編集取消クリック時
     */
    $(document).on('click', '.editable-actions .cancel', function(e) {
        $('.editable-label').show();
        $('.editable-input').hide();
        $('.editable-button').show();
        $('.editable-actions').hide();
        return false;
    });

    /**
     * 編集の更新クリック時
     */
    $(document).on('click', '.editable-actions .update', function(e) {
        var $this = $(this);
        var $parent = $this.parents('.time-row');
        var ymd = $parent.data('ymd');
        var employeeId = $('#employee-list').val();

        var data = {};
        $parent.find('.editable-input').each(function() {
            var $s = $(this).find('.item');
            data[$s.attr('name')] = $s.val();
        });

        var parameter = {
            target: ymd,
            employeeId: employeeId,
            data: data
        };
        console.log(parameter);

        showLoading();
        $.ajax({
            type: 'POST',
            url: '/api/time-cards/touch',
            data: JSON.stringify(parameter),
            dataType: 'json',
            contentType: 'application/json'
        }).done(function( data, textStatus, jqXHR) {
            console.log(data, jqXHR, textStatus);
            loadTimeCardTable(employeeId, moment().format(DATE_FORMAT));
            $('#notice').trigger('click');
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
        }).always(function(jqXHR, textStatus) {
            console.log(jqXHR, textStatus);
            hideLoading();
        });
        return false;
    });

    /**
     * 勤務表の表示
     */
    function loadTimeCardTable(employeeId, target) {
        var $table = $('#time-table');
        if (!employeeId) {
            $table.html('<p> 従業員の方を選択してください。</p>');
            return;
        }

        showLoading();
        var parameter = {
            employeeId: employeeId,
            target_ym: target
        };
        console.log(parameter);
        $.ajax({
            type: 'GET',
            url: '/api/time-cards/table',
            data: parameter,
            dataType: 'html'
        }).done(function(data, textStatus, jqXHR ) {
            console.log(jqXHR, textStatus);
            $table.html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
        }).always(function(jqXHR, textStatus) {
            console.log(jqXHR, textStatus);
            hideLoading();
        });
    }
});
