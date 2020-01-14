# Plugin WordPress Custom CTA

This is a WordPress Plugin, to simple add a call to action on all posts of a website.

## Install on production

1. Create a folder in `wp-content/plugins` named `cta-posts`

2. Into this folder, paste all the content from `dist` folder

## Dev commands

To install dependencies:

```bash
npm i
```

Compile sass for `admin` folder

```bash
npm run sass-admin
```

Compile sass for `public` folder

```bash
npm run sass-admin
```

Compile js for `admin` folder

```bash
npm run js-admin
```

Compile js for `public` folder

```bash
npm run js-public
```

To watch sass changes

- Edit `package.json`
- Search the sass you want to watch, and set like this where `[...]` is the rest of the code without changes

```bash
node-sass --watch [...]
```

## Shortcutes

Compile all js

```bash
npm run all-js
```

Compile all sass

```bash
npm run all-sass
```

## To-do

- Version #1
  - [x] Finish the implement of WordPress Media Uploader
  - [x] Handle when user select an image
  - [x] Put the selected image on all posts
  - [x] Setting up a save/cancel/delete button
- Version #2
  - [ ] Add an option to choose where on the posts, the CTA should appers
  - [ ] Add more options of CTA's, like: video, text, etc.
- Extras
  - [x] Add history options to pick recent posts
  - [x] Add link option to image
  - [x] Add log changes using localStorage to handle incoming errors

## Fonts

- [WP Plugin Intro](https://developer.wordpress.org/plugins/intro/)
- [Incorpore WP Uploader](https://www.inkthemes.com/code-to-integrate-wordpress-media-uploader-in-plugintheme/)
- [Array + this + fn](https://stackoverflow.com/questions/14553623/what-does-mean-arraythis-some-method-string)
- [Media Uploader](http://qnimate.com/adding-a-single-image-using-wordpress-media-uploader/)
- [Creating tables](https://codex.wordpress.org/Creating_Tables_with_Plugins)
- [Ajax on WordPres](https://codex.wordpress.org/AJAX_in_Plugins)
- [WordPress Get Image](https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/)

## Changelog

[See more](CHANGELOG.md)

## License

[Apache 2.0](LICENSE)
