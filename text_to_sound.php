<?php
/* Copyright (C) 2023 Alberto Luque Rivas <aluquerivasdev@gmail.com>*/


require './vendor/autoload.php';

use GuzzleHttp\Client;

/* La clase Velocidad define constantes para velocidades lentas y normales. */

class Speed
{
    const SLOW = 0;
    const NORMAL = true;
}

/* La clase gTTS es una clase PHP que le permite generar y guardar voz a partir de texto utilizando el
servicio Texto a voz del Traductor de Google. */
class gTTS
{
    private $text;
    private $tld;
    private $lang;
    private $slow;
    private $lang_check;
    private $client;

    /**
     * La función es un constructor que inicializa las propiedades de un objeto.
     * 
     * @param text El parámetro "texto" se utiliza para especificar el texto que será procesado por la
     * función. Es un parámetro obligatorio y debe proporcionarse al llamar a la función.
     * @param tld El parámetro "tld" significa "dominio de nivel superior" y representa la extensión de
     * dominio de un sitio web. De forma predeterminada, está configurado en 'com', que significa
     * sitios web comerciales. Sin embargo, puede pasar un valor diferente para especificar un dominio
     * de nivel superior diferente, como 'org'
     * @param lang El parámetro "lang" se utiliza para especificar el idioma del texto. Está
     * configurado en 'en' de forma predeterminada, que significa inglés. Sin embargo, puede pasar un
     * código de idioma diferente como una cadena a este parámetro si desea utilizar un idioma
     * diferente para el texto.
     * @param slow El parámetro "lento" es un valor booleano que determina si la solicitud debe
     * realizarse a un ritmo más lento. Si se establece en verdadero, la solicitud se realizará a una
     * velocidad más lenta.
     * @param lang_check El parámetro `lang_check` es un indicador booleano que determina si la
     * verificación de idioma debe habilitarse o no. Si se establece en "verdadero", se comprobará y
     * verificará el idioma del texto. Si se establece en "falso", la verificación de idioma se
     * desactivará.
     */
    public function __construct($text, $tld = 'com', $lang = 'en', $slow = false, $lang_check = false)
    {
        $this->text = $text;
        $this->tld = $tld;
        $this->lang = $lang;
        $this->slow = $slow;
        $this->lang_check = $lang_check;
        $this->client = new Client();
    }

    /**
     * La función guarda el contenido de una URL en un archivo.
     * 
     * @param savefile El parámetro savefile es la ruta y el nombre del archivo donde se guardará la
     * respuesta de la URL.
     */
    public function save($savefile)
    {
        $url = $this->get_url();

        $response = $this->client->get($url);
        file_put_contents($savefile, $response->getBody());
    }

    public function save_to_base64()
    {
        $url = $this->get_url();
        $response = $this->client->get($url);
        $base64 = base64_encode($response->getBody());
        return $base64;
    }

    /**
     * La función `get_url()` devuelve una URL para el servicio de texto a voz del Traductor de Google,
     * con el texto, el idioma y otros parámetros proporcionados codificados en la URL.
     * 
     * @return una cadena de URL.
     */
    private function get_url()
    {
        $text = urlencode($this->text);
        $tld = urlencode($this->tld);
        $lang = urlencode($this->lang);
        $slow = $this->slow ? '1' : '0';
        $url = "https://translate.google.{$tld}/translate_tts?ie=UTF-8&tl={$lang}&q={$text}&total=1&idx=0&textlen={$this->get_textlen()}&client=tw-ob&prev=input&ttsspeed={$slow}";
        return $url;
    }

    /**
     * La función "get_textlen" devuelve la longitud de la cadena de texto.
     * 
     * @return la longitud del texto almacenado en el objeto.
     */
    private function get_textlen()
    {
        return strlen($this->text);
    }
}

$gtts = new gTTS('Esto es un mensaje automatizado.', 'es', 'es', Speed::NORMAL);
print $gtts->save_to_base64();
$gtts->save('audio.mp3');
