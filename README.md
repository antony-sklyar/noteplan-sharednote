# Shared Note

## About

This is aimed to be the improved version of "NotePlan Publish" built-in feature that allows you to share your notes to the web via a secret URL.

Main improvements are:

1. **Security out of the box**. You *always* set the password for your notes. This password is used to encrypt your note when it is being sent to the publish server. When anyone wants to view your shared content, he should also know the password to decrypt the note content. So the server has zero knowledge of your content and stores it encrypted. And you can be sure that no one will see your content unless they have a password.
2. **More flexibility in secure sharing**. You should be able to share your content to a specific list of people (by providing their emails). The backend then will send an email with personal secret link that only the receiver of that email will be able to open. You should also be able to easily limit the time that the note will be available via the secret link.
3. **Support for custom markup**. Tasks and checklists priorities via `!`, `!!` and `!!!`. Underline using `~`, strikethrough using `~~` and highlight using `::` are also supported. More to come (ideally full scope of the NotePlan themes, including custom markdown that is allowed in themes, should be supported).
4. **Recursive sharing**. The basic idea is to detect wiki-links in the note and check whether they are published with NotePlan Online. If they are, replace wiki-link with a NotePlan Online URL of the linked page (only in the published version of the note, keeping the original note content as it is). The next step would be to allow notes be published recursively with one command: if you reference some other note in your current note and choose to do recursive publishing, the plugin will publish "network of the notes" (interlinking them properly), not just the current note.

## Usage

You may publish your notes to the Shared Note space easily using "Share Note" plugin. You may install it from the settings or commandbar of your NotePlan instance.
