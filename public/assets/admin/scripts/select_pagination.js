document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#pagination').addEventListener('change', function () {
        let perPage = (new URLSearchParams(window.location.search)).get('page');
        let paginate = this.value;

        fetch(`?pagination=${paginate}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка сети.');
                }
                return response.text();
            })
            .then(() => {
                window.location.href = '?pagination=' + paginate + (perPage !== null ? '&page=' + perPage : '');
            })
            .catch(error => {
                console.error('Ошибка AJAX запроса:', error);
            });
    });
});
