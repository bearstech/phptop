<?php
/*
 * phptop - Analyse quickly system ressource usage across many PHP queries
 * Copyright (C) 2009-2024 Bearstech - https://bearstech.com/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function _phptop_init() {
  global $_phptop_t0;
  $t0 = microtime(TRUE);
  $ru = getrusage();
  $_phptop_t0 = array(
    'time' => $t0,
    'tusr' => $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6,
    'tsys' => $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6
  );
  register_shutdown_function('_phptop_fini');

  define('SAVEQUERIES', True);  # Ask Wordpress (if present) to record SQL query statistics
}

function _phptop_fini() {
  global $_phptop_disable;
  if ($_phptop_disable) return;

  global $_phptop_t0;
  $t1   = microtime(TRUE);
  $time = $t1 - $_phptop_t0['time'];

  $ru = getrusage();
  $tusr = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6 - $_phptop_t0['tusr'];
  $tsys = $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6 - $_phptop_t0['tsys'];

  $mem   = memory_get_peak_usage(TRUE);

  $proto = isset($_SERVER['HTTPS']) ? 'https' : 'http';
  $vhost = $_SERVER['SERVER_NAME'];
  $uri   = $_SERVER['REQUEST_URI'];
  $self  = $vhost != '' ? "$proto://$vhost$uri" : $_SERVER['SCRIPT_FILENAME'];

  $msg = sprintf("phptop time:%.3F user:%.3F sys:%.3F mem:%03dM", $time, $tusr, $tsys, ($mem + 1048576 - 1)/1048576);

  # Wordpress specific statistics
  global $wpdb;
  if (isset($wpdb)) {
    $sqltime = 0;
    $sqlslower = 0;
    foreach ($wpdb->queries as $q){ // query, timer, caller
      $sqltime += $q[1];
      $sqlslower = max($sqlslower, $q[1]);
    }
    $msg .= sprintf(" sqltime:%.3F sqlslower:%.3F sqlcount:%03d", $sqltime, $sqlslower, $wpdb->num_queries);
  }

  error_log("$msg url:$self");
}

/* Don't run in CLI, it pollutes stderr and makes cronjob un-needingly noisy */
if (php_sapi_name() != 'cli') _phptop_init();
?>
