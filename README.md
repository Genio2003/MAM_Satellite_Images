# MEM_Satellite_Images

### • Descrizione
Script PHP che permette di ottenere tre tipi diversi di immagini satellitari meteo tramite l'API utilizzata dall'app mobile di [MeteoAM](http://www.meteoam.it "MeteoAM") (Meteo Areonautica Militare).

- Nefodina
- RadSatLamp
- IR Color Enhanced

Per ulteriori info: [http://www.meteoam.it/nefodina/it](http://www.meteoam.it/nefodina/it)

### • Come Funziona?
• Lo script effettua la chiamata API all'indirizzo "[http://webapp.meteoam.it/json/immaginisatellitari/immaginisatellitari.json](http://webapp.meteoam.it/json/immaginisatellitari/immaginisatellitari.json)" tramite la funzione "Call API", effettuando uno spoofing dell'user agent in modo da far sembrare che la richiesta provenga da un dispositivo mobile.
```php
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    // User Agent Spoofing // 
    $config['useragent'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1';

    curl_setopt($curl, CURLOPT_USERAGENT, $config['useragent']);
    curl_setopt($curl, CURLOPT_REFERER, $url);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}
```
• Converte le immagini da Base64 in PNG.
```php
function Base64_to_PNG($filename,$b64,$type)
{
    // Obtain the original content (usually binary data)
    $bin = base64_decode($b64);

    // Load GD resource from binary data
    $im = imageCreateFromString($bin);

    // Make sure that the GD library was able to load the image
    // This is important, because you should not miss corrupted or unsupported images
    if (!$im) {
    die('Base64 value is not a valid image');
    }

    // Specify the location where you want to save the image
    $img_file = 'images/PNG/'.$type.'/'.$filename;

    // Save the GD resource as PNG in the best possible quality (no compression)
    // This will strip any metadata or invalid contents (including, the PHP backdoor)
    // To block any possible exploits, consider increasing the compression level
    imagepng($im, $img_file, 0);
}
```
• Tramite la libreria [AnimGif.php](https://github.com/lunakid/AnimGif "AnimGif.php") converte le immagini PNG in immagini GIF animate.
```php
function Multiple_PNGs_to_GIF()
{
    require "AnimGif.php";
    $anim1 = new GifCreator\AnimGif();
    $anim1	-> create("images/PNG/"."NEF", array(50))
	-> save("images/GIF/"."NEF".".gif");

    $anim2 = new GifCreator\AnimGif();
    $anim2	-> create("images/PNG/"."IRC", array(50))
	-> save("images/GIF/"."IRC".".gif");

    $anim3 = new GifCreator\AnimGif();
    $anim3	-> create("images/PNG/"."RST", array(50))
	-> save("images/GIF/"."RST".".gif");
}
```
• In fine genera un file JSON contenente i link per ottenere le singole immagini in formato PNG e le GIF animate.
```php
function Make_JSON($Response)
{

    header("Content-Type: application/json; charset=UTF-8");

    $Result = Array (
        "PNGs" => Array (
            "NEF" => Array(
                "0" => Array(
                    "file_name" => $Response['immagini'][0]['nomefile'],
                    "time" => $Response['immagini'][0]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][0]['nomefile']
                ),
                "1" => Array(
                    "file_name" => $Response['immagini'][1]['nomefile'],
                    "time" => $Response['immagini'][1]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][1]['nomefile']
                ),
                "2" => Array(
                    "file_name" => $Response['immagini'][2]['nomefile'],
                    "time" => $Response['immagini'][2]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][2]['nomefile']
                ),
                "3" => Array(
                    "file_name" => $Response['immagini'][3]['nomefile'],
                    "time" => $Response['immagini'][3]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][3]['nomefile']
                ),
                "4" => Array(
                    "file_name" => $Response['immagini'][4]['nomefile'],
                    "time" => $Response['immagini'][4]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][4]['nomefile']
                )
            ),
            "IRC" => Array(
                "0" => Array(
                    "file_name" => $Response['immagini'][5]['nomefile'],
                    "time" => $Response['immagini'][5]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][5]['nomefile']
                ),
                "1" => Array(
                    "file_name" => $Response['immagini'][6]['nomefile'],
                    "time" => $Response['immagini'][6]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][6]['nomefile']
                ),
                "2" => Array(
                    "file_name" => $Response['immagini'][7]['nomefile'],
                    "time" => $Response['immagini'][7]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][7]['nomefile']
                ),
                "3" => Array(
                    "file_name" => $Response['immagini'][8]['nomefile'],
                    "time" => $Response['immagini'][8]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][8]['nomefile']
                ),
                "4" => Array(
                    "file_name" => $Response['immagini'][9]['nomefile'],
                    "time" => $Response['immagini'][9]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][9]['nomefile']
                )
            ),
            "RST" => Array(
                "0" => Array(
                    "file_name" => $Response['immagini'][10]['nomefile'],
                    "time" => $Response['immagini'][10]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][10]['nomefile']
                ),
                "1" => Array(
                    "file_name" => $Response['immagini'][11]['nomefile'],
                    "time" => $Response['immagini'][11]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][11]['nomefile']
                ),
                "2" => Array(
                    "file_name" => $Response['immagini'][12]['nomefile'],
                    "time" => $Response['immagini'][12]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][12]['nomefile']
                ),
                "3" => Array(
                    "file_name" => $Response['immagini'][13]['nomefile'],
                    "time" => $Response['immagini'][13]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][13]['nomefile']
                ),
                "4" => Array(
                    "file_name" => $Response['immagini'][14]['nomefile'],
                    "time" => $Response['immagini'][14]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".$Response['immagini'][14]['nomefile']
                )
            )
        ),
        "GIFs" => Array(
            "NEF" => Array(
                "file_name" => "NEF.gif",
                "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/NEF.gif"
            ),
            "IRC" => Array(
                "file_name" => "IRC.gif",
                "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/IRC.gif"
            ),
            "RST" => Array(
                "file_name" => "RST.gif",
                "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/RST.gif"
            ),
        ),
        "Time" => Array(
            "UTC" => gmdate("H:i:s")
        )
    );

    $JSON = json_encode($Result);

    echo ($JSON);

}
```

### • Disclaimer

**Informazioni elaborate dal Servizio Meteorologico dell’Aeronautica Militare e pubblicate sul sito www.meteoam.it**
