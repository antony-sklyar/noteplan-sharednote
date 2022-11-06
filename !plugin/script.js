function npoPublish(title, content, secret, accessKey)
{
    return fetch('https://noteplan.online/api/publishedNote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'password': secret,
            'accessKey': accessKey,
            'title': title,
            'content': content
        })
    });
}

function npoUpdatePublished(guid, title, content, secret, accessKey)
{
    return fetch('https://noteplan.online/api/publishedNote', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'guid': guid,
            'password': secret,
            'accessKey': accessKey,
            'title': title,
            'content': content
        })
    });
}

function npoUnpublish(guid, accessKey)
{
    return fetch('https://noteplan.online/api/publishedNote', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'guid': guid,
            'accessKey': accessKey
        })
    });
}

function npoPublishedUrlLine(url)
{
    return '[' + DataStore.settings.linkText + '](' + url + ')\n';
}

// ----------------------------------------------------------------------------

function publish() {
    let config = DataStore.settings;
    let secret = config.secret;
    let accessKey = config.accessKey;

    let noteTitle = Editor.title;
    let noteContent = Editor.content;

    let guid = '';
    let existingUrl = noteContent.match(/https:\/\/noteplan.online\/([0-9a-zA-Z]+)/);
    if (existingUrl) {
        guid = existingUrl[1];
        url = existingUrl[0];
        noteContent = noteContent.replace(npoPublishedUrlLine(url), '');
        npoUpdatePublished(guid, noteTitle, noteContent, secret, accessKey)
            .then(function(response) {
                console.log('Published note has been updated.');
                NotePlan.openURL(JSON.parse(response).url);
            })
            .catch(function(error) {
                console.log('Publishing failed: ' + error);
            });
    } else {
        npoPublish(noteTitle, noteContent, secret, accessKey)
            .then(function(response) {
                let url = JSON.parse(response).url;
                console.log('Note has been published: ' + url);
                let linkLine = npoPublishedUrlLine(url);
                let firstLineEnd = Editor.content.indexOf('\n');
                Editor.insertTextAtCharacterIndex(linkLine, firstLineEnd + 1);
                NotePlan.openURL(url);
            })
            .catch(function(error) {
                console.log('Publishing failed: ' + error);
            });
    }
}

function unpublish() {
    let accessKey = DataStore.settings.accessKey;
    let existingUrl = Editor.content.match(/https:\/\/noteplan.online\/([0-9a-zA-Z]+)/);
    if (!existingUrl) {
        console.log('No published note detected.');
        return;
    }
    let guid = existingUrl[1];

    npoUnpublish(guid, accessKey)
        .then(function(response) {
            console.log('Unpublished');
            let noteContent = Editor.content;
            let urlLineBegin = noteContent.indexOf('[' + DataStore.settings.linkText + ']');
            let urlLineEnd = noteContent.indexOf('\n', urlLineBegin);
            Editor.replaceTextInCharacterRange('', urlLineBegin, urlLineEnd - urlLineBegin);
        })
        .catch(function(error) {
            console.log('Unpublish failed: ' + error);
        });
}