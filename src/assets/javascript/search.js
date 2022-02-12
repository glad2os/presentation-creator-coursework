document.getElementById('exampleDataList').onchange = async ev => {
    if (document.getElementById('exampleDataList').value === '') {
        document.getElementById('datalistOptions').innerHTML = '';
    }
}
document.getElementById('exampleDataList').onkeyup = async ev => {
    // if (ev.key !== "Enter") return;

    document.getElementById('datalistOptions').innerHTML = '';

    let byid = document.getElementById("searchPresentationByPresentationId").checked;
    let bykeyword = document.getElementById("searchPresentationByKeyWord").checked;
    let byname = document.getElementById("searchPresentationByAuthorName").checked;

    let data = [];

    if (byid) {
        await getJson('search', {
            "action": "searchPresentationByPresentationId",
            "string": String(document.getElementById('exampleDataList').value)
        }).then(value => value.json()).then(r => {
            if (r.length > 0) data.push(r);
        });
    }

    if (bykeyword) {
        await getJson('search', {
            "action": "searchPresentationByKeyWord",
            "string": String(document.getElementById('exampleDataList').value.toString())
        }).then(value => value.json()).then(r => {
            if (r.length > 0) data.push(r);
        });
    }

    if (byname) {
        await getJson('search', {
            "action": "searchPresentationByAuthorName",
            "string": Number(document.getElementById('exampleDataList').value)
        }).then(value => value.json()).then(r => {
            if (r.length > 0) data.push(r);
        });
    }

    let mySet = new Set();
    if (data.length > 0) {
        data.forEach(d => {
            mySet.add({id: d[0].id, name: d[0].name});
        });
    }
    mySet.forEach(value => {
        document.getElementById('datalistOptions').insertAdjacentHTML('beforeend', `<option value='Презентация#${value.id} от ${value.name}'>`);
    })

}
