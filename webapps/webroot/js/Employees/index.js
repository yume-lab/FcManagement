/**
 * 従業員一覧JS
 */
$(function () {
    var selector = '.delete-form';

    $('.btn-confirm').on('click', function(e) {
        e.preventDefault();
        var action = $(this).data('action') || '#';
        var $modal = $('#confirm-modal');
        $modal.find(selector).attr('action', action);
        $modal.modal('show');
    });

    $('.done-delete').on('click', function() {
        $(selector).submit();
    });
});

