function signin() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    /*
        Валидация данных
     */
    if (email.length < 3 || password.length < 3) {
        /*
            Ответ в случае ошибки
         */
        document.querySelector('.error').innerHTML = '' +
            '        <div class="alert alert-danger" style="margin: 15px 25%; text-align: center" role="alert">\n' +
            '            Пароль и логин должны быть более 3х символов!\n' +
            '        </div>';
        return;
    }
    getJson('signin', {
        "email": email,
        "password": password
    }).then(r => {
        /*
         callback ответа сервера (включая все типы ответов)
         */
        if (r.status === 200) {
            /*
                Ответ пользователю и переадресация в случае успеха
             */
            document.querySelector('.error').innerHTML = '<div class="alert alert-success" style="margin: 15px 25%; text-align: center" role="alert">\n' +
                '  Успешная авторизация!\n' +
                '</div>';
            setTimeout(() => {
                window.location.href = '/account';
            }, 500);
        } else {
            /*
                 Ответ пользователю в случае не успеха
             */
            r.json().then(value => {
                document.querySelector('.error').innerHTML = '' +
                    '        <div class="alert alert-danger" style="margin: 15px 25%; text-align: center" role="alert">\n' +
                    `            ${value.error}!\n` +
                    '        </div>';
            });
        }
    });
}