$(document).ready(function () {
    var newItems = $('#new-items'),
        itemTemplate = $('.item-template').hide().eq(0),
        addBtn = $('#add-item-container > button'),
        count = 0,
        isDeletedInputs = $('.item_is_deleted'),
        withBodyInput = $('#with_body'),
        withHtmlInput = $('#with_html'),
        withHtmlContainer = $('#withHtml-container').hide();

    withBodyInput.click(function () {
        withHtmlContainer.toggle($(this).is(':checked'));
        withHtmlInput.prop('checked', false);
    });

    if (itemTemplate.length == 0) {
        return;
    }

    addBtn.click(function (e) {
        e.preventDefault();
        count++;

        var newItem = itemTemplate.clone().show(),
            legend = $('legend', newItem),
            labels = $('label', newItem),
            inputFields = $('input,textarea,select', newItem);

        legend.text(legend.text() + ' (' + count + ')');

        labels.each(function () {
            var $this = $(this);
            $this.attr('for', $this.attr('for') + count);
        });
        inputFields.each(function () {
            var $this = $(this);
            $this.attr('id', $this.attr('id') + count);
            $this.attr('name', 'new' + count + $this.attr('name').substr(3));
        });

        newItems.append(newItem);
    });

    isDeletedInputs.each(function () {
        var $this = $(this),
            $item = $this.parent('fieldset'),
            $labels = $('label', $item),
            $inputs = $('input, textarea', $item);

        console.log('this', $this);
        console.log('label', $labels);
        console.log('input', $inputs);

        $this.change(function () {
            if ($this.prop('checked')) {
                $labels.css('color', '#f00');
                $inputs.css('background-color', '#f00');
            } else {
                $labels.css('color', '#f00');
                $inputs.css('background-color', '#000');
            }
        });
    });
});