function updateActiveStatus(id, isActive, item) {
    let optionsForRequestData;
    if (isActive) {
        optionsForRequestData = {
            is_active: isActive,
            _token: '{{ csrf_token() }}'
        }
    } else {
        optionsForRequestData = {
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
        data: optionsForRequestData,
        success: function (response) {
            toastr.success('Успешно сохранено!');
        },
        error: function (xhr, status, error) {
            toastr.error('Ошибка сохранения!');
        }
    });
}
