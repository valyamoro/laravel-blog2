document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.custom-control-input').forEach(element => {
        element.addEventListener('change', () => {
            updateActiveStatus(
                element.getAttribute('data-id'),
                element.getAttribute('data-item'),
                element.getAttribute('data-status-name'),
                element.checked,
            );
        });
    });

    function updateActiveStatus(id, item, statusName, statusValue) {
        const data = statusValue ? {[statusName]: statusValue} : null;

        toastr.options = {
            "positionClass": "toast-top-right",
            "timeOut": "1000",
            "extendedTimeOut": "2000",
            "closeButton": true
        }

        $.ajax({
            url: '/api/' + item + '/status/' + id,
            method: 'PATCH',
            data: data,
            success: function () {
                toastr.success('Успешно сохранено!');
            },
            error: function () {
                toastr.error('Ошибка сохранения!');
            }
        });
    }
});
