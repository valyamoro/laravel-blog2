document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#pagination').addEventListener('change', function () {
        let queryString = window.location.search;
        let uriParams = new URLSearchParams(queryString);
        let perPage = this.value;
        uriParams.set('pagination', perPage);

        window.location.href = '?' + uriParams.toString();
    });
});
