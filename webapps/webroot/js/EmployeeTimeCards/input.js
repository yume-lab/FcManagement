/**
 * タイムカード入力画面JS
 */

$(function () {
    var $modal = $('#time-card-modal');
    var buttonSelector = '.action-button';
    /**
     * 従業員行クリック時
     */
    $(document).on('click', '.employee-row', function(e) {
        e.preventDefault();
        $this = $(this);
        $('#employee-name').html($this.find('.name').html()+'さん');
        $('#employee-id').val($this.data('id'));

        switchActionButton($this.data('path'));
        $modal.modal('show');
    });

    /**
     * ボタン押下時
     */
    $(document).on('click', buttonSelector, function(e) {
        e.preventDefault();
        showLoading();
        var parameter = {
            token: $('#token').val(),
            employeeId: $('#employee-id').val(),
            path: $(this).data('path'),
            time: moment().format('YYYY-MM-DD HH:mm:ss'),
            rested_minutes: $('#break-time').val()
        };
        console.log(parameter);
        $.ajax({
            type: 'POST',
            url: '/api/time-card/write',
            data: JSON.stringify(parameter),
            dataType: 'json',
            contentType: 'application/json',
        }).done(function( data, textStatus, jqXHR) {
            console.log(data, jqXHR, textStatus);
            switchActionButton(parameter.path);
            loadEmployeeRows();
            $('#notice').trigger('click');
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
            showErrorDialog();
        }).always(function(jqXHR, textStatus) {
            console.log(jqXHR, textStatus);
            hideLoading();
        });

    });

    /**
     * デジタル時計
     */
    setInterval(function() {
        var now = moment();
        $modal.find('#clock').html(now.format('HH:mm:ss'));
        $('#clock-large').html(now.format('YYYY年MM月DD日 HH:mm:ss'));
    }, 1000);

    /**
     * 従業員一覧部分を表示します.
     */
    function loadEmployeeRows() {
        showLoading();
        var parameter = {
            token: $('#token').val()
        };
        console.log(parameter);
        $.ajax({
            type: 'GET',
            url: '/api/time-card/rows',
            data: parameter,
            dataType: 'html'
        }).done(function(data, textStatus, jqXHR ) {
            console.log(jqXHR, textStatus);
            $('#table-area').html(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
            showErrorDialog();
        }).always(function(jqXHR, textStatus) {
            console.log(jqXHR, textStatus);
            hideLoading();
        });
    }

    /**
     * ボタンの表示切り替えを行います.
     */
    function switchActionButton(path) {
        var showButtons = getShowButton(path);
        $(buttonSelector).hide();
        $(buttonSelector).each(function() {
            if (0 <= $.inArray($(this).data('path'), showButtons)) {
                $(this).css('display', 'inline-block');
                $('#break-input-area').css('display', ($(this).data('path') === '/end') ? 'block' : 'none');
            }
        });
    }

    /**
     * 不正ログイン時のダイアログを表示します.
     */
    function showErrorDialog() {
        // 閉じられないようにする.
        $('#fail-request-modal').modal({backdrop: 'static', keyboard: false});
    }

    /**
     * 表示するボタンを返します.
     * @param path
     * @return array
     */
    function getShowButton(path) {
        var matrix = {
            'default': [
                '/start'
            ],
            '/start': [
                '/end',
            ],
            '/end': [
                '/start'
            ]
        };

        path = path || 'default';
        return matrix[path];
    }
    loadEmployeeRows();
});

