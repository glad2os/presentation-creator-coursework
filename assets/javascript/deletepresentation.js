function deletepresentation(id) {
    getJson('deletepresentation', {"presentation_id": id}).then(r => {
        if (r.status === 200) {
            document.querySelectorAll('.container')[1].insertAdjacentHTML('afterbegin',
                '<div class="alert alert-success" style="margin: 15px 25%; text-align: center" role="alert">Успешно удалено!</div>');
            setTimeout(() => {
                window.location.href = '/account';
            }, 500);
        } else {
            r.json().then(value => {
                document.querySelectorAll('.container')[1].insertAdjacentHTML('afterbegin', '' +
                    '        <div class="alert alert-danger" style="margin: 15px 25%; text-align: center" role="alert">\n' +
                    `            ${value.error}!\n` +
                    '        </div>');
            });
        }
    });
}