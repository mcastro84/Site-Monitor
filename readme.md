## Website Monitor

Interface to add website urls and check their availability.

Each Url is pushed into a queue in Redis and then picked up by a supervisor worker.

When all Urls are checked, an email is sent with a list of unavailable urls.

## Requires
- VirtualBox [https://www.virtualbox.org/](https://www.virtualbox.org/)
- Vagrant [https://www.vagrantup.com/](https://www.vagrantup.com/)

## Installation

1. Clone project
    * git clone https://github.com/mcastro84/website-monitor.git
    * cd website-monitor

2. Create VM (Ubuntu 16.04 VM with: PHP7.2, MySQL5.7, Nginx, Redis Server and Supervisor)
    * vagrant up

3. Edit .env file
   * Add your email to MAIL_TO

4. Open [http://localhost:8080](http://localhost:8080)

## Run the monitor from the terminal
php artisan urls:check

## Tests
run: vendor/bin/phpunit