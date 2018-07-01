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
Simple usage :) :
1 - Copy all files and folders in your pics repository.
2 - Use </upload/index.html> ur to uplmoad tour png or jpg file
2.1 - Files will be upload on ../ folder. So, your repository
3 - Use </index.php> url to browse youre repository
3.1 - The first time, alls news pics will be thumbed in /thumb repository
3.2 - The page has a pager by 20 pics by page.
4 - Clic on a thumb to get the pics in full size with all share url.


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
