$(document).ready(function () {
    var a = $('a.safe-mail'),
        domain = a.data('domain'),
        user = a.data('user'),
        f = a.data('f'),
        s = $('span', a),
        addr = '';

    if (f && window[f]) {
        addr = window[f](domain) + '@' + window[f](user);
    } else {
        addr = domain.replace(/\,/g, '') + '@' + user.replace(/\,/g, '');
    }

    s = s.length > 0 ? s : a;

    a.prop('href', 'mailto:' + addr);
    s.append(addr);
});