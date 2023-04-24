# Shared Note Changelog

## [1.1.0] - 2023-04-24

- Improved markdown parser that splits the content into logical blocks (according to the rules that are typical for NotePlan markdown flavour) and then parses markdown in these blocks
- Added support for end-line comments parsing
- Improved design of the tables and page wrapper

## [1.0.0] - 2023-04-23

This is the first release of the backend under https://sharednote.space brand.

Right now it supports:
- password prompt to decrypt the notes
- option to append the password to the URL to avoid prompting
- display both the common markdown and some advanced markup that became common in NotePlan community (task and checklist priorities, inline and end line comments)
