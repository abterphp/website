$(document).ready(function () {
    var isDraft = $('#is_draft'),
        isDraftContainer = $('#is-draft-container'),
        draftBtn = $('#draft-btn'),
        publishBtn = $('#publish-btn');

    isDraftContainer.hide();
    if (isDraft.checked) {
        publishBtn
            .show()
            .removeClass('hidden')
            .click(function(e) {
                isDraft.prop('checked', false);
                e.preventDefault();
            });
    } else {
        draftBtn
            .show()
            .removeClass('hidden')
            .click(function(e) {
                isDraft.prop('checked', true);
                e.preventDefault();
            });
    }
});