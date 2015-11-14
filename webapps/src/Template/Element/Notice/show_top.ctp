<?php
/**
 * 画面上部に表示する完了メッセージ.
 *
 * 表示させる際は、JSで下記のイベントを発火させてください.
 * $('#notice').trigger('click');
 */

$message = empty($message) ? '完了しました。' : $message;
?>

<button id="notice" class="noty hide"
        data-noty-options="{&quot;text&quot;:&quot;<?= $message ?>&quot;,&quot;layout&quot;:&quot;top&quot;,&quot;type&quot;:&quot;information&quot;}">
</button>

