У вашего аккаунта {{ $adminUser->username }} был изменен пароль. <br>
Ваши новые реквизиты для входа:<br>
Логин: {{ $adminUser->email }} <br>
Пароль: {{ $newPassword }} <br>
<br>
Можете войти по ссылке - <a href="{{ route('admin.login.form') }}">Перейти</a>
