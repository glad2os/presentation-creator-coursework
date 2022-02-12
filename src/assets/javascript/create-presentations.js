const form = document.querySelector('form');
let countSlides = document.getElementById('count-slides');
/*
    count - всего слайдов
    current - текущий слайд
    data - дамп данных для дальнейшей отправки
 */
let count = 0;
let current = 0;
let data = {};

/*
    Отключаем возможность отправки формы
 */
form.onsubmit = ev => {
    ev.preventDefault();
}
/*
    Отлавливаем кол-во слайдов,
    Валидация данных
 */
countSlides.onkeyup = ev => {
    if (ev.key === 'Enter') {
        if (parseInt(ev.target.value) > 0) {
            count = parseInt(ev.target.value);
            countSlides.style.display = 'none';
            addSlide();
        } else {
            alert('Количество презентаций не может быть меньше 0!')
        }
    }
}

function uploadFile(data) {
    sendFile('createPresentation', data).then(r => {
        if (r.status === 200) {
            form.innerHTML = '<div class="alert alert-success" style="margin: 15px 25%; text-align: center" role="alert">\n' +
                '  Успешное добавление!\n' +
                '</div>';
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            r.json().then(value => {
                    form.innerHTML = '' +
                        '        <div class="alert alert-danger" style="margin: 15px 25%; text-align: center" role="alert">\n' +
                        `            ${value.error}!\n` +
                        '        </div>';
                });
        }
    })
}

function addSlide() {
    /*
        Очистка страницы
     */
    form.innerHTML = '';
    /*
        динамическая Front-end отрисовка
        добавления информации на слайд
     */
    let htmlParagraphElement = document.createElement("p");
    htmlParagraphElement.innerText = "Слайд номер: " + current;

    let htmlDivElement = document.createElement('div');
    htmlDivElement.classList.add('mb-3');
    let htmlInputElement = document.createElement('input');
    htmlInputElement.classList.add('form-control');
    htmlInputElement.type = 'text';
    htmlInputElement.name = 'title';
    htmlInputElement.placeholder = 'Title';
    htmlDivElement.insertAdjacentElement('beforeend', htmlInputElement)

    let htmlDivElement2 = document.createElement('div');
    htmlDivElement2.classList.add('mb-3');
    let htmlElement = document.createElement('small');
    htmlElement.classList.add('form-text');
    htmlElement.innerText = 'Содержимое презентаци';
    htmlDivElement2.insertAdjacentElement('beforeend', htmlElement);

    let htmlDivElement3 = document.createElement('div');
    htmlDivElement2.classList.add('mb-3');
    let htmlTextAreaElement = document.createElement('textarea');
    htmlTextAreaElement.classList.add('form-control');
    htmlTextAreaElement.name = 'content';
    htmlTextAreaElement.placeholder = 'Content';
    htmlTextAreaElement.rows = '14';
    htmlDivElement3.insertAdjacentElement('beforeend', htmlTextAreaElement);

    let label = document.createElement('label');
    label.classList.add('form-label');
    label.innerText = 'Выберите файлы (поддерживаемые форматы: видео и аудио)';

    let htmlDivElement4 = document.createElement('div');
    htmlDivElement4.classList.add('mb-3');
    let htmlInputElement1 = document.createElement('input');
    htmlInputElement1.classList.add('form-control');
    htmlInputElement1.name = 'file';
    htmlInputElement1.type = 'file';
    htmlInputElement1.accept = 'audio/*,video/*';
    htmlInputElement1.multiple = true;
    let htmlButtonElement = document.createElement('button');
    htmlButtonElement.style.marginTop = '15px';
    htmlButtonElement.classList.add('btn');
    htmlButtonElement.classList.add('btn-primary');
    htmlButtonElement.innerText = 'Далее';
    htmlButtonElement.onclick = () => {
        if (current + 1 === count) {
            /*
                В случае если это последний слайд
                Очистка страницы,
                Отправка данных
             */
            let inputs = document.querySelectorAll(".mb-3>input");

            let slide = "slide" + current;
            data[slide] = {
                "title": inputs[0].value,
                "content": document.querySelector("textarea").value,
                "files": document.querySelectorAll(".mb-3>input")[1].files
            }

            form.innerHTML = '';
            uploadFile(data);
        } else {
            /*
                В противном случае дальнейшее продолжение сбора информации
             */
            let inputs = document.querySelectorAll(".mb-3>input");

            let slide = "slide" + current;
            data[slide] = {
                "title": inputs[0].value,
                "content": document.querySelector("textarea").value,
                "files": document.querySelectorAll(".mb-3>input")[1].files
            }
            current++;
            addSlide();
        }
    };
    /*
        Отрисовка на странице
     */
    htmlDivElement4.insertAdjacentElement('beforeend', htmlInputElement1);
    htmlDivElement4.insertAdjacentElement('beforeend', htmlButtonElement);

    form.insertAdjacentElement('beforeend', htmlParagraphElement);
    form.insertAdjacentElement('beforeend', htmlDivElement);
    form.insertAdjacentElement('beforeend', htmlDivElement2);
    form.insertAdjacentElement('beforeend', htmlDivElement3);
    form.insertAdjacentElement('beforeend', label);
    form.insertAdjacentElement('beforeend', htmlDivElement4);
}