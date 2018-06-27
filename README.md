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

### Authentication
Protect /upload with an .htaccess file.

## Authors
* **Christophe** - *Initial work* - [khrys63](https://github.com/khrys63)
* Base on [Simple Photo Album](http://ilannweb.free.fr) by Ilann C, for the thumbnails generator.

## License
This project is licensed under Apache 2.0.

## Contributing
We welcome contributions from the community!
