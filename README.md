# Plugin WordPress Custom CTA

This is a WordPress Plugin, to simple add a call to action on all posts of a website.

## Commands

To install dependencies:

```
npm i
```

For compile sass for `admin` folder

```
npm run sass-admin
```

For compile sass for `public` folder

```
npm run sass-admin
```

For compile js for `admin` folder

```
npm run js-admin
```

For compile js for `public` folder

```
npm run js-public
```

To watch sass changes

- Edit `package.json`
- Search the sass you want to watch, and set like this where `[...]` is the rest of the code without changes

```
node-sass --watch [...]
```

### Shortcutes

For compile all js

```
npm run all-js
```

For compile all sass

```
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

[See more](changelog.md)

## License

[Apache 2.0](LICENSE)
