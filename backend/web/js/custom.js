$('a').on('click', function(e) {
    if ($(this).attr('disabled') == 'disabled') {
        e.preventDefault();
    }
});