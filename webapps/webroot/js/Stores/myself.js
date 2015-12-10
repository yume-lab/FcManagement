/**
 * ログイン中店舗編集JS
 */

$(function() {
    /**
     * タブ作動
     */
    $('#storeTab a:first').tab('show');
    $('#storeTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    /**
     * サブミット時
     */
    $('form').submit(function() {
        var restedTimeValues = [];
        $('#break-settings').children('.rested-time-row').each(function() {
            var worked = $(this).find('[name="worked"]').val();
            var rested = $(this).find('[name="rested"]').val();
            // 時間は分に直す
            restedTimeValues.push({
                worked: worked * 60,
                rested: rested
            });
        });
        $('#rested_times').val(JSON.stringify(restedTimeValues));
        return true;
    });

    var addRestedTimeRow = function() {
        $('#break-settings').append($('#base').html());
    }

    /**
     * 休憩設定の追加
     */
    $(document).on('click', '#add-rested-row', function() {
        addRestedTimeRow();
        return false;
    });

    /**
     * 休憩設定の1行削除
     */
    $(document).on('click', '.delete-row', function() {
        $(this).closest('.rested-time-row').remove();
        return false;
    });
});

