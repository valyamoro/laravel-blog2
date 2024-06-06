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
        let data = {}

        if (statusValue) {
            data = {
                [statusName]: 1,
                status_name: statusName,
            }
        } else {
            data = {
                [statusName]: 0,
                status_name: statusName,
            }
        }

        toastr.options = {
            "positionClass": "toast-top-right",
            "timeOut": "1000",
            "extendedTimeOut": "2000",
            "closeButton": true
        }

        console.log('/api/status/' + item + '/' + id);
        $.ajax({
            url: '/api/status/' + item + '/' + id,
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
