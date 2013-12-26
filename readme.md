# Codeigniter Custom Captcha Package

Erweiterter Codeigniter Captcha Helper als Library.

http://ellislab.com/codeigniter/user-guide/helpers/captcha_helper.html

  * alle Features des Captcha Helpers

  * Hintergrund Farbe
  * Vordegrund Farbe
  * Text Farbe
  * Grid Farbe
  * Border Farbe

  * Schriftgröße
  * Wortlänge
  * Zeichenpool

## Installation

  * Session Encryption Key setzen in der config.php
  * Die captcha config im package Ordner anpassen
  * Den entsprechenden Captcha-Image Ordner erstellen

## Nutzung im Controller

### Captcha Package laden

`````
$this->load->add_package_path(APPPATH.'third_party/customcaptcha');
$this->load->library('customcaptcha');
````

### Captcha erstellen

Rückgabewert entspricht dem von create_captcha().

Der erste Parameter $custom_config überschreibt dabei alle in der config Datei definierten Werte.

````
$captcha = $this->customcaptcha->create($custom_config);
````

### Captcha Validieren

Rückgabewert ist Boolean.

````
$captcha = $this->customcaptcha->validate($form_input);
````

### Prüfen ob ein Captcha für den user vorhanden ist

Rückgabewert ist Boolean.

````
$exists = $this->customcaptcha->exists();
````

### Ein vorhandenes Captcha aus der Session auslesen

Wenn ein Captcha schon existiert wird es zurück gegeben.

Rückgabewert entspricht dem von create_captcha().

````
$captcha = $this->customcaptcha->get();
````

## Mögliche Einstellungen

````
$config['font_path']
$config['img_path']	 
$config['img_width']	
$config['img_height'] 
$config['expiration'] 
$config['font_size'] 
$config['word_len'] 
$config['pool'] 	
$config['bg_color'] 
$config['border_color']
$config['text_color'] 
$config['grid_color'] 
$config['shadow_color']
````