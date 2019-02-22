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

To watch sass changes

- Edit `package.json`
- Search the sass you want to watch, and set like this where `[...]` is the rest of the code without changes

```
node-sass --watch [...]
```

## To-do

- Version #1
  - Finish the implement of WordPress Media Uploader
  - Handle when user select an image
  - Put the selected image on all posts
  - Setting up a save/cancel/delete button
- Version #2
  - Add an option to choose where on the posts, the CTA should apper
  - Add more options of CTA's. Like: video, text, etc.