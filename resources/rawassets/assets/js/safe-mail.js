$(document).ready(function () {
    var a = $('a.safe-mail'),
        domain = a.data('domain'),
        user = a.data('user'),
        f = a.data('f'),
        s = $('span', a),
        addr = '';

    if (f && window[f]) {
        addr = window[f](user) + '@' + window[f](domain);
    } else {
        addr = user.replace(/\,/g, '') + '@' + domain.replace(/\,/g, '');
    }

    s = s.length > 0 ? s : a;

    a.prop('href', 'mailto:' + addr);
    s.append(addr);
});