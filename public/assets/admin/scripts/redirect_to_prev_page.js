document.addEventListener('DOMContentLoaded', function () {
    let pagination = document.getElementById('pagination');

    let count = pagination.options[pagination.selectedIndex].getAttribute('data-count');
    let perPage = (new URLSearchParams(window.location.search)).get('page');

    if (perPage.toString() > '1' && (count === '0' || count === null)) {
        let queryString = window.location.search;
        let uriParams = new URLSearchParams(queryString);
        uriParams.set('page', '1');

        window.location.href = '?' + uriParams.toString();
    }
});
