<?php defined('SYSPATH') or die('No direct script access.');


return array(
    'cache'   => APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'twig',
    'charset' => Kohana::$charset,
    'auto_reload' => TRUE,
	'template_ext' => 'php'

);