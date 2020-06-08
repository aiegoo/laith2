<?php
  $memcache = new Memcache;
  $memcache->connect('127.0.0.1', 11211) or die ("Unable to connect to Memcached");

  // example of cacheing
 /* $exampleValue = array('0','1','2','3');
  $memcache->set('exampleKey', $exampleValue, false, 1800);
  $exampleValue = $memcache->get('exampleKey');*/
?>
