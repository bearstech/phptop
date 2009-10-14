<?php
function _phptop_fini() {
  global $_phptop_disable;
  if ($_phptop_disable) return;

  global $_phptop_t0;
  $t1   = microtime(TRUE);
  $time = sprintf('%.6f', $t1 - $_phptop_t0['time']);

  $ru = getrusage();
  $tusr = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6 - $_phptop_t0['tusr'];
  $tsys = $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6 - $_phptop_t0['tsys'];

  $mem   = memory_get_peak_usage(TRUE);

  $inc   = count(get_included_files());

  $proto = $_SERVER['HTTPS'] != '' ? 'https' : 'http';
  $vhost = $_SERVER['SERVER_NAME'];
  $uri   = $_SERVER['REQUEST_URI'];
  $self  = $proto != '' ? "$proto://$vhost$uri" : $_SERVER['SCRIPT_FILENAME'];

  error_log(str_replace(',', '.', "phptop $self time:$time user:$tusr sys:$tsys mem:$mem inc:$inc"));
}

global $_phptop_t0;
$t0 = microtime(TRUE);
$ru = getrusage();
$_phptop_t0 = array(
  'time' => $t0,
  'tusr' => $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6,
  'tsys' => $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6
);
register_shutdown_function('_phptop_fini');
?>
