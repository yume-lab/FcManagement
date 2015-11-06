<?php
/**
 * TODO: 外部JS化が文字化けして難しかった
 * 共通javascript.
 */

?>

<script type="text/javascript">
    /**
     * インディケーター表示
     */
    var showLoading = function() {
        $('#loading').show();
    }

    /**
     * インディケーター非表示
     */
    var hideLoading = function() {
        $('#loading').hide();
    }

    $(document).ready(function () {
        /**
         * メニューの活性化
         */
        $('ul.main-menu li a').each(function () {
            if (String(window.location).indexOf($($(this))[0].href) >= 0) {
                $(this).parent().addClass('active');
            }
        });
    });


</script>

