$(document).ready(function () {
    var isDraft = $('#is_draft'),
        isDraftContainer = $('#is-draft-container'),
        draftBtn = $('#draft-btn'),
        publishBtn = $('#publish-btn');

    isDraftContainer.hide();
    if (isDraft.attr('checked')) {
        publishBtn
            .show()
            .removeClass('hidden')
            .click(function() {
                isDraft.attr('checked', false);
            });
    } else {
        draftBtn
            .show()
            .removeClass('hidden')
            .click(function() {
                isDraft.attr('checked', true);
            });
    }
});