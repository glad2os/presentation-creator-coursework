const nav = document.querySelector('.nav');
const content = document.querySelector('.content');
let slides = [];
getJson('getslides', {"id": parseInt(document.body.id)}).then(value => {
    value.json().then(r => {
        r.forEach((s, k) => {
            /*
                Front-end отрисовка элементов
             */
            let slide = document.createElement('div');
            slide.classList.add("slide")
            let p = document.createElement('p');
            p.innerText = `#${k + 1}`;
            /*
                Пагинация
             */
            slide.onclick = ev => {
                content.innerHTML = '';
                let cw = document.createElement('div');
                cw.classList.add("content-wrapper");
                let header = document.createElement('div');
                header.classList.add("header");
                let text = document.createElement('div');
                text.classList.add("text");
                header.innerText = s.title;
                text.innerText = s.content;
                cw.insertAdjacentElement('beforeend', header);
                cw.insertAdjacentElement('beforeend', text);
                content.insertAdjacentElement('beforeend', cw);

                let files = document.createElement('div');
                files.classList.add('files');
                /*
                    Получение слайдов
                 */
                getJson('getfiles', {
                    "slide_id": s.id,
                    "presentation_id": parseInt(document.body.id)
                }).then(r => r.json().then(fp => {
                    if (fp.length !== 0) {
                        fp.forEach(f => {
                            let file_img = document.createElement('div');
                            file_img.classList.add("file_img");
                            /*
                                Доступ к медиафайлам
                             */
                            file_img.onclick = ev1 => {
                                window.location.href = f.file_path;
                            }
                            file_img.innerText = `#${f.id}`;
                            files.insertAdjacentElement('beforeend', file_img);
                        });
                    }
                }));
                content.insertAdjacentElement('beforeend', files);
            }
            slide.insertAdjacentElement('beforeend', p);
            nav.insertAdjacentElement('beforeend', slide)
        });
        slides = r;
        document.querySelector('.slide').click();
    });
});