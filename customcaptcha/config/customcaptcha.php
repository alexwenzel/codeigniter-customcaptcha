<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['font_path']		= APPPATH.'/third_party/customcaptcha/fonts/verdana.ttf';
$config['img_path']	 		= './captchas/';
$config['img_width']	 	= 300;
$config['img_height'] 		= 50;
$config['expiration'] 		= 7200;
$config['font_size'] 		= 27;

$config['word_len'] 		= 8;
$config['pool'] 			= '023456789ABCDEFGHJKLMNOPQRSTUVWXYZ';
$config['bg_color'] 		= '000000';
$config['border_color'] 	= '000000';
$config['text_color'] 		= 'ffffff';
$config['grid_color'] 		= 'bc984d';
$config['shadow_color'] 	= 'bc984d';

/* End of file customcaptcha.php */
/* Location: ./application/third_party/customcaptcha/config/customcaptcha.php */