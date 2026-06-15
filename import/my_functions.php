<?php

/*
  Plugin Name: my_functions
*/

add_action('init', function () {

  $flag = '/home/d/dachniksh/public_html/import/.run_flag';
  $today = date('Y-m-d');

  $last = file_exists($flag) ? trim(file_get_contents($flag)) : '';
  $last = '';

  if ($last !== $today) {
    file_put_contents($flag, $today);
    require_once '/home/d/dachniksh/public_html/import/import.php';
  }
});