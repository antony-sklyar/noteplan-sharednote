function encryptForPublish(content, secret) {
    return content; // TODO
}

function publish() {
    let config = DataStore.settings;
    let secret = config.secret;
    let accessKey = config.accessKey;

    let guid = '';
    let existingUrl = Editor.content.match(/https:\/\/noteplan.online\/([0-9a-zA-Z]+)/);
    if (existingUrl) {
        guid = existingUrl[1];
    }

    fetch('https://noteplan.online/api/publish', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'guid': guid,
            'accessKey': accessKey,
            'title': Editor.title,
            'content': encryptForPublish(Editor.content, secret)
        })
    })
    .then(function(response) {
        let url = JSON.parse(response).url;
        console.log('done: ' + url);

        if (!existingUrl) {
            let noteContent = Editor.content;
            let linkLine = '[' + DataStore.settings.linkText + '](' + url + ')\n';
            let firstLineEnd = noteContent.indexOf('\n');
            Editor.insertTextAtCharacterIndex(linkLine, firstLineEnd + 1);
        }

        NotePlan.openURL(url);
    })
    .catch(function(error) {
        console.log(error);
    })
}

function unpublish() {
    console.log("Hello World"); // TODO
}