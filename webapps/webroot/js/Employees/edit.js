/**
 * 従業員編集JS
 */

$(function() {
    /**
     * タブ作動
     */
    $('#employeeTab a:first').tab('show');
    $('#employeeTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
})

