function encryptForPublish(content) {
    return content; // TODO
}

function publish() {
    fetch('https://noteplan.online/api/publish', {
        method: 'POST',
        headers: {
            // 'Authorization': 'Bearer ',
            'Content-Type': 'application/json'
        },
        body: {
            'title': Editor.title,
            'content': encryptForPublish(Editor.content)
        }
    })
    .then(function(response) {
        let url = response.url;
        let firstLineEnd = Editor.content.indexOf('\n');
        Editor.insertTextAtCharacterIndex(url, firstLineEnd + 1);
        NotePlan.openURL(url);
    })
    .catch(function(error) {
        console.log(error);
    })
}

function unpublish() {
    console.log("Hello World"); // TODO
}