document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#order').addEventListener('change', function() {
        let queryString = window.location.search;
        console.log(queryString)
        let uriParams = new URLSearchParams(queryString);
        let order = this.value;
        uriParams.set('order', order);

        window.location.href = '?' + uriParams.toString();
    });
});
