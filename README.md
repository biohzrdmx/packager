##Introduction##

Pack and unpack complete sites easily, good for deploying and backup creation.

Just ZIP your site, upload the package and unpackage it. Or the inverse, package your online site and download a single ZIP.

###Credits###

**Lead coder:** biohzrdmx [&lt;github.com/biohzrdmx&gt;](http://github.com/biohzrdmx)

###License###

The MIT License (MIT)

Copyright (c) 2013 biohzrdmx

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

##Basic usage##

**Packing**

Upload `packager-zip.php` via FTP to your site's root folder.

Fire up your browser and go to your site's url, append `'/packager-zip.php'`.

Set a file name on 'Archive name' and click the 'Pack now' button.

Your site will be compressed into a ZIP file, the process may take a couple seconds to complete, and you'll be offered to download the resulting ZIP file.

_IMPORTANT: Once you've finished with your backup, you should delete the `packager-zip.php` file as it may be a security concern._

**Unpacking**

Upload `packager-unzip.php` via FTP to your site's root folder.

Fire up your browser and go to your site's url, append `'/packager-unzip.php'`.

Set the name of your ZIP file on 'Package' and click the 'Unpack now' button. You may want to delete the ZIP after decompressing it, if so tick the appropiate checkmark.

Your ZIP will be uncompressed into the current folder, the process may take a couple seconds to complete..

_IMPORTANT: Once you've finished with your deployment, you should delete the `packager-unzip.php` file as it may be a security concern._

##Troubleshooting##

You may find that this doesn't work on your &lt;insert cheap web hosting provider name here&gt;-hosted server, and that's because it doesn't has the PHP Zip extension.

As a workaround, you may use the [PclZip library](http://www.phpconcept.net/pclzip/) (for unpacking only). Just upload `pclzip.lib.php` and place it in the same folder as `packager-unzip.php`.

Also, you may notice that on some hosting services (looking at you, Rackspace) the script runs out of time and dies. This is because they have a ridiculously short execution time, if you can increment it to say, a couple minutes, do so before trying to pack/unpack large sites.