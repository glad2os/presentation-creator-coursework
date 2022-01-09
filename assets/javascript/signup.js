/*
    Функция генерации капчи
 */
function generateCaptcha(length) {
    let result = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

/*
    Отрисовка капчи
 */
function drawCaptcha(captcha) {
    const getRandomInt = (max) => Math.floor(Math.random() * max);

    const canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');
    ctx.strokeStyle = "#000000";
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let i = 0; i < 150; i++) {
        ctx.beginPath();
        ctx.moveTo(getRandomInt(100), getRandomInt(100));
        ctx.lineTo(i + getRandomInt(100), getRandomInt(100));
        ctx.stroke();
    }
    ctx.font = "48px serif";
    ctx.strokeStyle = "#fd8484";
    ctx.strokeText(captcha, 10, 50);
}

function signup() {
    /*
        Валидация
     */
    let userNameValue = document.getElementById('name').value.length;
    let emailValue = document.getElementById('email').value.length;
    let descriptionValue = document.getElementById('description').value.length;
    let loginValue = document.getElementById('login').value.length;
    let passwordValue = document.getElementById('password').value.length;

    if (userNameValue < 3 || descriptionValue < 3 || emailValue < 3 || loginValue < 3 || passwordValue < 3) {
        document.querySelector('.error').innerHTML = '' + '        <div class="alert alert-danger" style="margin: 15px 25%; text-align: center" role="alert">\n' + '            Поля должны быть более 3х символов!\n' + '        </div>';
    } else {
        /*
            Отрисовка капчи
         */
        document.querySelector('.captcha').style.display = 'flex';
        document.querySelector('.wrapper').style = 'filter: blur(4px);';

        let captcha = generateCaptcha(5);
        drawCaptcha(captcha);

        let input = document.getElementById('captcha_input');
        /*
            Триггер ввода данных
         */
        input.onkeyup = ev => {
            if (document.activeElement === input) {
                if (ev.key === "Enter") {
                    /*
                        Валидация капчи
                     */
                    if (input.value === captcha) {
                        document.querySelector('.captcha').style.display = 'none';
                        getJson('signup', {
                            "email": document.getElementById('email').value,
                            "password": document.getElementById('password').value,
                            "name": document.getElementById('login').value,
                            "username": document.getElementById('name').value,
                            "description": document.getElementById('description').value
                        }).then(r => {
                            /*
                             callback ответа сервера (включая все типы ответов)
                             */
                            if (r.status === 200) {
                                /*
                                    Ответ пользователю и переадресация в случае успеха
                                 */
                                document.querySelector('.error').innerHTML = '<div class="alert alert-success" style="margin: 15px 25%; text-align: center" role="alert">\n' +
                                    '  Успешная регистрация!\n' +
                                    '</div>';
                                setTimeout(() => {
                                    window.location.href = '/auth';
                                }, 500);
                            } else {
                                /*
                                     Ответ пользователю в случае не успеха
                                 */
                                document.querySelector('.wrapper').style = 'filter: blur(0px);';
                                r.json().then(value => {
                                    document.querySelector('.error').innerHTML = '' +
                                        '        <div class="alert alert-danger" style="margin: 15px 25%; text-align: center" role="alert">\n' +
                                        `            ${value.error}!\n` +
                                        '        </div>';
                                });

                            }
                        });
                    } else {
                        /*
                            Перерисовка капчи в противном случае
                         */
                        captcha = generateCaptcha(5);
                        drawCaptcha(captcha);
                    }
                }
            }
        }
    }

}

