const nav = document.querySelector('.nav');
const content = document.querySelector('.content');
let slides = [];
getJson('getslides', {"id": parseInt(document.body.id)}).then(value => {
    value.json().then(r => {
        r.forEach((s, k) => {
            /*
                Front-end отрисовка
             */
            let slide = document.createElement('div');
            slide.classList.add("slide")
            let p = document.createElement('p');
            p.innerText = `#${k + 1}`;
            slide.onclick = ev => {
                content.innerHTML = '';
                let cw = document.createElement('div');
                cw.classList.add("content-wrapper");
                let header = document.createElement('div');
                header.classList.add("header");
                let text = document.createElement('div');
                text.classList.add("text");
                text.contentEditable = true;
                header.innerText = s.title;
                header.contentEditable = true;
                text.innerText = s.content;
                let submit = document.createElement('button');
                submit.innerText = `Обновить слайд #${k + 1}`;
                cw.insertAdjacentElement('beforeend', header);
                cw.insertAdjacentElement('beforeend', text);
                let submitwrapper = document.createElement('div');
                submitwrapper.classList.add("submitwrapper");
                submitwrapper.insertAdjacentElement('beforeend', submit);
                submitwrapper.style.marginTop = "15px";
                submit.onclick = ev1 => {
                    /*
                        Кнопка обновления
                     */
                    getJson('editslide', {
                        'title': document.querySelector('.header').innerText,
                        'content': document.querySelector('.text').innerText,
                        'presentation_id': parseInt(document.body.id),
                        'id': s.id
                    }).then(r => {
                        if (r.status === 200) {
                            window.location.href;
                        } else {
                            r.json().then(error => alert(error.error));
                        }
                    });
                }
                cw.insertAdjacentElement('beforeend', submitwrapper);
                content.insertAdjacentElement('beforeend', cw);

                let files = document.createElement('div');
                files.classList.add('files');
                /*
                    Получение файлов для просмотра
                 */
                getJson('getfiles', {
                    "slide_id": s.id,
                    "presentation_id": parseInt(document.body.id)
                }).then(r => r.json().then(fp => {
                    if (fp.length !== 0) {
                        fp.forEach(f => {
                            let file_img = document.createElement('div');
                            file_img.classList.add("file_img");
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