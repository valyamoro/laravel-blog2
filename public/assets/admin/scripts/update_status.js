function updateActiveStatus(id, item, statusName, statusValue) {
    let data;
    if (statusValue) {
        data = {
            [statusName]: statusValue,
            _token: '{{ csrf_token() }}'
        }
    } else {
        data = {
            _token: '{{ csrf_token() }}'
        }
    }

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
        success: function (response) {
            toastr.success('Успешно сохранено!');
        },
        error: function (xhr, status, error) {
            toastr.error('Ошибка сохранения!');
        }
    });
}
