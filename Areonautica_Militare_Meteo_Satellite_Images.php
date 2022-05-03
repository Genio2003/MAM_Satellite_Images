<?php

// https://stackoverflow.com/questions/9802788/call-a-rest-api-in-php //
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

    //User Agent Spoofing // 
    // https://stackoverflow.com/questions/17801094/php-curl-how-to-add-the-user-agent-value-or-overcome-the-servers-blocking-curl-r //
    $config['useragent'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1';

    curl_setopt($curl, CURLOPT_USERAGENT, $config['useragent']);
    curl_setopt($curl, CURLOPT_REFERER, $url);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

// https://base64.guru/developers/php/examples/decode-image //
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
    //echo ("https://genio2003.altervista.org".$img_file);
}

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

function main()
{
    $Response=json_decode(CallAPI("GET","http://webapp.meteoam.it/json/immaginisatellitari/immaginisatellitari.json"),true);

    for($i=0;$i<=4;$i++)
    {
        Base64_to_PNG($Response['immagini'][$i]['nomefile'],$Response['immagini'][$i]['image'],"NEF");
    }
    for($i=5;$i<=9;$i++)
    {
        Base64_to_PNG($Response['immagini'][$i]['nomefile'],$Response['immagini'][$i]['image'],"IRC");
    }
    for($i=10;$i<=14;$i++)
    {
        Base64_to_PNG($Response['immagini'][$i]['nomefile'],$Response['immagini'][$i]['image'],"RST");
    }

    Multiple_PNGs_to_GIF();
}

main();

header("Content-Type: application/json; charset=UTF-8");

$Result = new stdClass();
$Result->NEF = "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/NEF.gif";
$Result->IRC = "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/IRC.gif";
$Result->RST = "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/RST.gif";

$JSON = json_encode($Result);

echo ($JSON);

?>