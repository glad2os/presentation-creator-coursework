/*
    XMLHttpRequest  в асинхронном формате
 */
async function getJson(url, data = {}) {
    return await fetch('/api/' + url, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json'
        },
        // redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: JSON.stringify(data)
    });
}

async function sendFile(url, data = {}) {
    const formData = new FormData();
    /*
        Приведение данных из полученных данных уровнем выше
     */
    for (const [key, value] of Object.entries(data)) {
        for (const [k, v] of Object.entries(value)) {
            /*
                Файлы отдельно, текст отдельно
             */
            if (k === "files") {
                for (let i = 0; i < v.length; i++) {
                    formData.append(key + "file" + i, v[i], v[i].name)
                }
            } else
                formData.append(key + k, v);
        }
    }

    return await fetch('/api/' + url, {
        method: 'POST',
        body: formData
    });
}