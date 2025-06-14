*phptop (c) 2009-2024 Bearstech - https://bearstech.com/*

phptop prints per query and average metrics comparable to 'time' (wallclock,
user and system CPU time) along with memory and other ressource usages.

It can be easily globally activated on a LAMP server and requires little
resources and a single line configuration change in your php.ini. It has been
used by [Bearstech](https://bearstech.com/) on many production servers for
years without any problems.

Requires PHP >= 5.2.0. Tested up to PHP 8.2.

Example usage:

    server:~# echo auto_prepend_file=/path/to/phptop_hook.php >>path/to/php.ini (or .user.ini)
    server:~# apache2ctl reload  (or php-fpm reload)

(Wait at least a few minutes to collect data...)

    server:~# phptop -s mem
    URL                                       Hit     Time     User      Sys >Mem/hit  Mem_max
    http://blog.dummy.com/facebook/myapi/       5      0.8      0.5      0.1      6.2     31.0
    http://blog.dummy.com/feed                 10      1.0      0.8      0.1      6.0     30.2
    http://blog.dummy.com/feed/                10      1.2      1.0      0.0      6.0     30.2
    http://blog.dummy.com/tag/peekk/            5      0.6      0.5      0.0      6.2     30.8
    http://blog.dummy.com/2008/09/              5      0.9      0.6      0.1      6.2     31.0
    http://test.org/rss.xml                    10      0.6      0.5      0.1      5.2     25.8
    http://test.org/cron.php                    5      0.6      0.3      0.0      5.2     26.2
    http://test.org/                            5      4.3      0.2      0.1      5.3     26.5
    http://test.org/user/register               5      0.5      0.3      0.0      5.1     25.5
    http://test.org/page/welcome                5      0.7      0.2      0.1      5.0     25.0
    Total (from last 10 min)                 1140     95.9     27.0      3.7


See the man page for operation details and all options.

Thanks for your participation:
* Marc Dequènes aka Duck

