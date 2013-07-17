<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomCaptcha
{
	private $ci_instance;

	public function __construct()
	{
		// lade helper
		$this->ci_instance =& get_instance();
		$this->ci_instance->load->helper(array('url', 'captcha', 'security'));
		$this->ci_instance->load->library(array('session'));

		// lade config in seperatem bereich
		$this->ci_instance->config->load('customcaptcha', true);
	}

	/**
	 * Erstellt ein Captcha und gibt es zurück
	 *
	 * @return array
	 **/
	public function create($custom_config = array())
	{
		// hole config werte

		$config['font_path']		= $this->ci_instance->config->item('font_path', 'customcaptcha');
		$config['img_path']	 		= $this->ci_instance->config->item('img_path', 'customcaptcha');
		$config['img_url']	 		= base_url($config['img_path']).'/';
		$config['img_width']	 	= $this->ci_instance->config->item('img_width', 'customcaptcha');
		$config['img_height'] 		= $this->ci_instance->config->item('img_height', 'customcaptcha');
		$config['expiration'] 		= $this->ci_instance->config->item('expiration', 'customcaptcha');
		$config['font_size'] 		= $this->ci_instance->config->item('font_size', 'customcaptcha');
		$config['word_len'] 		= $this->ci_instance->config->item('word_len', 'customcaptcha');
		$config['pool'] 			= $this->ci_instance->config->item('pool', 'customcaptcha');
		$config['bg_color'] 		= $this->ci_instance->config->item('bg_color', 'customcaptcha');
		$config['border_color'] 	= $this->ci_instance->config->item('border_color', 'customcaptcha');
		$config['text_color'] 		= $this->ci_instance->config->item('text_color', 'customcaptcha');
		$config['grid_color'] 		= $this->ci_instance->config->item('grid_color', 'customcaptcha');
		$config['shadow_color'] 	= $this->ci_instance->config->item('shadow_color', 'customcaptcha');

		// führe beide arrays zusammen
		$config = array_merge($config, $custom_config);

		// generiere das captcha
		$captcha = create_captcha($config);

		var_dump($captcha);

		// speichert in der session
		$this->ci_instance->session->set_userdata('scimcaptcha.word', $this->hash_word($captcha['word']));
		$this->ci_instance->session->set_userdata('scimcaptcha.image', $captcha['image']);
		$this->ci_instance->session->set_userdata('scimcaptcha.time', $captcha['time']);

		return $captcha;
	}

	/**
	 * Prüft ob der Nutzer schon ein captcha hat
	 *
	 * @return bool
	 **/
	public function exists()
	{
		$word = $this->ci_instance->session->userdata('scimcaptcha.word');
		$image = $this->ci_instance->session->userdata('scimcaptcha.image');
		$time = $this->ci_instance->session->userdata('scimcaptcha.time');

		if ($word && $image && $time) {
			return true;
		}

		return false;
	}

	/**
	 * Prüft ob der Nutzer schon ein captcha hat
	 *
	 * @return bool
	 **/
	public function get()
	{
		$word = $this->ci_instance->session->userdata('scimcaptcha.word');
		$image = $this->ci_instance->session->userdata('scimcaptcha.image');
		$time = $this->ci_instance->session->userdata('scimcaptcha.time');

		return array(
			'word' => $word,
			'image' => $image,
			'time' => $time,
		);
	}

	/**
	 * Validiert das Captcha und entfernt session Eintrag
	 *
	 * @return bool
	 **/
	public function validate($input)
	{
		$word = $this->ci_instance->session->userdata('scimcaptcha.word');
		
		if ($word === $this->hash_word($input)) {

			// entferne session
			$this->ci_instance->session->unset_userdata('scimcaptcha.word');
			$this->ci_instance->session->unset_userdata('scimcaptcha.image');
			$this->ci_instance->session->unset_userdata('scimcaptcha.time');

			return true;
		}

		return false;
	}

	/**
	 * Maskiert das Capchta Wort
	 *
	 * @return string
	 **/
	private function hash_word($word)
	{
		return do_hash(strtolower($word));
	}
}

function hex2rgb($hex) {
       preg_match("/^#{0,1}([0-9a-f]{1,6})$/i",$hex,$match);
       if(!isset($match[1])) {return false;}
       if(strlen($match[1]) == 6) {
               list($r, $g, $b) = array($hex[0].$hex[1],$hex[2].$hex[3],$hex[4].$hex[5]);
       }
       elseif(strlen($match[1]) == 3) {
               list($r, $g, $b) = array($hex[0].$hex[0],$hex[1].$hex[1],$hex[2].$hex[2]);
       }
       else if(strlen($match[1]) == 2) {
               list($r, $g, $b) = array($hex[0].$hex[1],$hex[0].$hex[1],$hex[0].$hex[1]);
       }
       else if(strlen($match[1]) == 1) {
               list($r, $g, $b) = array($hex.$hex,$hex.$hex,$hex.$hex);
       }
       else {
               return false;
       }

       return array(hexdec($r), hexdec($g), hexdec($b));
}

function create_captcha($data = '')
{
	$defaults = array(
		'word' => '',
		'word_len' => 8,
		'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
		'img_path' => '',
		'img_url' => '',
		'img_width' => '150',
		'img_height' => '30',
		'font_path' => '',
		'expiration' => 7200,
		'font_size' => 16,
		'bg_color' => 'ffffff',
		'border_color' => 'ffffff',
		'text_color' => '000000',
		'grid_color' => 'cccccc',
		'shadow_color' => '000000',
	);

	foreach ($defaults as $key => $val)
	{
		if ( ! is_array($data))
		{
			if ( ! isset($$key) OR $$key == '')
			{
				$$key = $val;
			}
		}
		else
		{
			$$key = ( ! isset($data[$key])) ? $val : $data[$key];
		}
	}

	if ($img_path == '' OR $img_url == '')
	{
		return FALSE;
	}

	if ( ! @is_dir($img_path))
	{
		return FALSE;
	}

	if ( ! is_writable($img_path))
	{
		return FALSE;
	}

	if ( ! extension_loaded('gd'))
	{
		return FALSE;
	}

	// -----------------------------------
	// Remove old images
	// -----------------------------------

	list($usec, $sec) = explode(" ", microtime());
	$now = ((float)$usec + (float)$sec);

	$current_dir = @opendir($img_path);

	while ($filename = @readdir($current_dir))
	{
		if ($filename != "." and $filename != ".." and $filename != "index.html")
		{
			$name = str_replace(".jpg", "", $filename);

			if (($name + $expiration) < $now)
			{
				@unlink($img_path.$filename);
			}
		}
	}

	@closedir($current_dir);

	// -----------------------------------
	// Do we have a "word" yet?
	// -----------------------------------

   if ($word == '')
   {
		$str = '';
		for ($i = 0; $i < $word_len; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}

		$word = $str;
   }

	// -----------------------------------
	// Determine angle and position
	// -----------------------------------

	$length	= strlen($word);
	$angle	= ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
	$x_axis	= rand(6, (360/$length)-16);
	$y_axis = ($angle >= 0 ) ? rand($img_height, $img_width) : rand(6, $img_height);

	// -----------------------------------
	// Create image
	// -----------------------------------

	// PHP.net recommends imagecreatetruecolor(), but it isn't always available
	if (function_exists('imagecreatetruecolor'))
	{
		$im = imagecreatetruecolor($img_width, $img_height);
	}
	else
	{
		$im = imagecreate($img_width, $img_height);
	}

	// -----------------------------------
	//  Assign colors
	// -----------------------------------

	$bg_color		= hex2rgb($bg_color);
	$border_color	= hex2rgb($border_color);
	$text_color		= hex2rgb($text_color);
	$grid_color		= hex2rgb($grid_color);
	$shadow_color	= hex2rgb($shadow_color);

	$bg_color		= imagecolorallocate ($im, $bg_color[0], $bg_color[1], $bg_color[2]);
	$border_color	= imagecolorallocate ($im, $border_color[0], $border_color[1], $border_color[2]);
	$text_color		= imagecolorallocate ($im, $text_color[0], $text_color[1], $text_color[2]);
	$grid_color		= imagecolorallocate($im, $grid_color[0], $grid_color[1], $grid_color[2]);
	$shadow_color	= imagecolorallocate($im, $shadow_color[0], $shadow_color[1], $shadow_color[2]);

	// -----------------------------------
	//  Create the rectangle
	// -----------------------------------

	ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);

	// -----------------------------------
	//  Create the spiral pattern
	// -----------------------------------

	$theta		= 1;
	$thetac		= 7;
	$radius		= 16;
	$circles	= 20;
	$points		= 32;

	for ($i = 0; $i < ($circles * $points) - 1; $i++)
	{
		$theta = $theta + $thetac;
		$rad = $radius * ($i / $points );
		$x = ($rad * cos($theta)) + $x_axis;
		$y = ($rad * sin($theta)) + $y_axis;
		$theta = $theta + $thetac;
		$rad1 = $radius * (($i + 1) / $points);
		$x1 = ($rad1 * cos($theta)) + $x_axis;
		$y1 = ($rad1 * sin($theta )) + $y_axis;
		imageline($im, $x, $y, $x1, $y1, $grid_color);
		$theta = $theta - $thetac;
	}

	// -----------------------------------
	//  Write the text
	// -----------------------------------

	$use_font = ($font_path != '' AND file_exists($font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;

	if ($use_font == FALSE)
	{
		$font_size = 5;
		$x = rand(0, $img_width/($length/3));
		$y = 0;
	}
	else
	{
		$x = rand(0, $img_width/($length/1.5));
		$y = $font_size+2;
	}

	for ($i = 0; $i < strlen($word); $i++)
	{
		if ($use_font == FALSE)
		{
			$y = rand(0 , $img_height/2);
			imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
			$x += ($font_size*2);
		}
		else
		{
			$y = rand($img_height/2, $img_height-3);
			imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
			$x += $font_size;
		}
	}


	// -----------------------------------
	//  Create the border
	// -----------------------------------

	imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color);

	// -----------------------------------
	//  Generate the image
	// -----------------------------------

	$img_name = $now.'.jpg';

	ImageJPEG($im, $img_path.$img_name);

	$img = "<img src=\"$img_url$img_name\" width=\"$img_width\" height=\"$img_height\" style=\"border:0;\" alt=\" \" />";

	ImageDestroy($im);

	return array('word' => $word, 'time' => $now, 'image' => $img);
}

/* End of file scimcaptcha.php */
/* Location: ./application/libraries/scimcaptcha.php */