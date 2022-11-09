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

function npoGetPublishUrl(url, config)
{
    url = url.split('?')[0];
    if (config.appendSecret) {
        url += '?password=' + config.secret;
    }
    return url;
}

function npoGetPublishedUrlLine(url, config)
{
    return '[' + config.linkText + '](' + url + ')\n';
}

function npoGenerateRandomKey(length)
{
    let charSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let key = '', pos = -1;
    for (let i = 0; i < length; i++) {
        pos = Math.floor(Math.random() * charSet.length);
        key += charSet.substring(pos, pos + 1);
    }
    return key;
}

function npoGetOrSetupSettings()
{
    let config = DataStore.settings;
    let changed = false;
    if (!config.accessKey) {
        config.accessKey = npoGenerateRandomKey(32);
        changed = true;
    }
    if (!config.secret) {
        config.secret = npoGenerateRandomKey(32);
        changed = true;
    }
    if (changed) {
        DataStore.settings = config;
    }
    return config;
}

function npoCutLineContaining(noteContent, search)
{
    let found = noteContent.indexOf(search);
    let startOfLine = noteContent.lastIndexOf('\n', found);
    let endOfLine = noteContent.indexOf('\n', found);
    let wholeLine = noteContent.substring(startOfLine, endOfLine);
    return noteContent.replace(wholeLine, '');
}

// ----------------------------------------------------------------------------

function publish() {
    let config = npoGetOrSetupSettings();
    let secret = config.secret;
    let accessKey = config.accessKey;

    let noteTitle = Editor.title;
    let noteContent = Editor.content;

    let guid = '';
    let existingUrl = noteContent.match(/https:\/\/noteplan.online\/([0-9a-zA-Z]+)/);
    if (existingUrl) {
        guid = existingUrl[1];
        noteContent = npoCutLineContaining(noteContent, guid);
        npoUpdatePublished(guid, noteTitle, noteContent, secret, accessKey)
            .then(function(response) {
                console.log('Published note has been updated.');
                let config = npoGetOrSetupSettings();
                let url = npoGetPublishUrl(JSON.parse(response).url, config);
                NotePlan.openURL(url);
            })
            .catch(function(error) {
                console.log('Publishing failed: ' + error);
            });
    } else {
        npoPublish(noteTitle, noteContent, secret, accessKey)
            .then(function(response) {
                let config = npoGetOrSetupSettings();
                let url = npoGetPublishUrl(JSON.parse(response).url, config);
                console.log('Note has been published: ' + url);
                console.log('Inserting the URL into the note.');
                let linkLine = npoGetPublishedUrlLine(url, config);
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
    let config = npoGetOrSetupSettings();
    let accessKey = config.accessKey;
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
            noteContent = npoCutLineContaining(noteContent, DataStore.settings.linkText);
            Editor.content = noteContent;
        })
        .catch(function(error) {
            console.log('Unpublish failed: ' + error);
        });
}