Install
==
- Save files

- Prepare your `config.ini` file.

- Run in terminal:

        cd ./data/shell && sh ./install.sh

Test load
==
For manual testing speed for update of rows you can open your host with page /installDump.php and open page /index.php and doing a lot of update in index.php.

Database dump with data has over 300k devices and ~1kk rows.

Save new dump:

        cd ./data/shell && sh ./save-dump.sh

Add new generate 1kk rows (background long operation):

        php ./installDump.php &

See all data in your browser in report link: `/report.php`.

See device details in change link in device_id on report page and you redirect to details page.

For send message to device need open page of device details and to submit the form.

Require
==
- PHP >= 7.0
- Composer
- MySQL >= 5.5
- OS family of Linux (Linux, Unix, MacOS)
- Redis (recommended)
