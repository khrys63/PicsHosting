# PicsHosting
A very simple PHP script for hosting your pics.

PicsHosting is a easy-to-use PHP script that generates dynamically a Photo Album from an upload directory full of JPEG/PNG images.
- You can upload, five by five, 2Mo JPG/PNG file.
- It generates the thumbnails once for all pics, the first time you launch it.
- It generates a viewer with pager.

It includes previous/next links for easier navigation. 
It includes share url for easier forum share. 

### Install
Only files are required.
No database are needed. 

One url to upload your files :
```
https://yourdomain/pics-hosting/upload/
```

One url to view and navigate in your files :
```
https://yourdomain/pics-hosting/
```

### How it work?
A simple usage :
- Copy all files and folders in your pics repository.
- Use **</upload/index.html>** url to upload your png or jpg files.
> Files will be upload on **<../>** folder. So, in your repository.
- Use **</index.php>** url to browse your repository.
> The first time, alls new pics will be minimized in **</thumb/>** repository.
> The page have a pager with 20 pics by page.
- Clic on a minimized pic to get the pic in full size with all share url



### Authentication
Protect /upload with an .htaccess file.

## Authors
* **Christophe** - *Initial work* - [khrys63](https://github.com/khrys63)

## Inspired by
* [Simple Photo Album](http://ilannweb.free.fr) by Ilann C, for the thumbnails generator.
* [HTML5 Drag and Drop Multiple File Uploader](http://www.script-tutorials.com/html5-drag-and-drop-multiple-file-uploader/) by Andrey Prikaznov, for the upload page.

## License
This project is licensed under Apache 2.0.

## Contributing
We welcome contributions from the community!
