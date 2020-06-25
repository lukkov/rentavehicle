require('jquery-form');


$(document).on('change', '[data-auto-submit]', function () {

    let $form = $(this).closest('form');
    let name = $form.attr('name');

    $form.ajaxSubmit({
        // target: form,
        headers: {
            'Refresh-Form': 'true',
        },
        replaceTarget: false,
        success: function (html) {
            let $html = $(html);
            let submittedForm = $html.find(`form[name="${name}"]`);

            $form.replaceWith(submittedForm);
        }
    });

    return false;
});
