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

function Multiple_PNGs_to_GIF($file_name1,$file_name2,$file_name3)
{
    require "AnimGif.php";
    $anim1 = new GifCreator\AnimGif();
    $anim1	-> create("images/PNG/"."NEF", array(50))
	-> save("images/GIF/".$file_name1);

    $anim2 = new GifCreator\AnimGif();
    $anim2	-> create("images/PNG/"."IRC", array(50))
	-> save("images/GIF/".$file_name2);

    $anim3 = new GifCreator\AnimGif();
    $anim3	-> create("images/PNG/"."RST", array(50))
	-> save("images/GIF/".$file_name3);
}

// https://www.geeksforgeeks.org/deleting-all-files-from-a-folder-using-php/ //
function Delete_Old_Photo($folder_path)
{
    // PHP program to delete all
    // file from a folder
   
    // List of name of files inside
    // specified folder
    $files = glob($folder_path.'/*'); 
   
    // Deleting all the files in the list
    foreach($files as $file) {
   
        if(is_file($file)) 
    
        // Delete the given file
        unlink($file);
    }
}

// https://stackoverflow.com/questions/20288789/php-date-with-timezone //
function Unix_Time_Converter($Unix_Time)
{
    $dt = new DateTime("@$Unix_Time");
    //$dt->setTimeZone(new DateTimeZone('Europe/Rome'));
    return $dt->format('dmYhi');
}

function Make_JSON($Response)
{

    header("Content-Type: application/json; charset=UTF-8");

    $Result = Array (
        "PNGs" => Array (
            "NEF" => Array(
                "0" => Array(
                    "file_name" => $Response['immagini'][0]['nomefile'],
                    "time" => $Response['immagini'][0]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".(Unix_Time_Converter($Response['immagini'][0]['ora'])."_".$Response['immagini'][0]['nomefile'])
                ),
                "1" => Array(
                    "file_name" => $Response['immagini'][1]['nomefile'],
                    "time" => $Response['immagini'][1]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".(Unix_Time_Converter($Response['immagini'][1]['ora'])."_".$Response['immagini'][1]['nomefile'])
                ),
                "2" => Array(
                    "file_name" => $Response['immagini'][2]['nomefile'],
                    "time" => $Response['immagini'][2]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".(Unix_Time_Converter($Response['immagini'][2]['ora'])."_".$Response['immagini'][2]['nomefile'])
                ),
                "3" => Array(
                    "file_name" => $Response['immagini'][3]['nomefile'],
                    "time" => $Response['immagini'][3]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".(Unix_Time_Converter($Response['immagini'][3]['ora'])."_".$Response['immagini'][3]['nomefile'])
                ),
                "4" => Array(
                    "file_name" => $Response['immagini'][4]['nomefile'],
                    "time" => $Response['immagini'][4]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/NEF/".(Unix_Time_Converter($Response['immagini'][4]['ora'])."_".$Response['immagini'][4]['nomefile'])
                )
            ),
            "IRC" => Array(
                "0" => Array(
                    "file_name" => $Response['immagini'][5]['nomefile'],
                    "time" => $Response['immagini'][5]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/IRC/".(Unix_Time_Converter($Response['immagini'][5]['ora'])."_".$Response['immagini'][5]['nomefile'])
                ),
                "1" => Array(
                    "file_name" => $Response['immagini'][6]['nomefile'],
                    "time" => $Response['immagini'][6]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/IRC/".(Unix_Time_Converter($Response['immagini'][6]['ora'])."_".$Response['immagini'][6]['nomefile'])
                ),
                "2" => Array(
                    "file_name" => $Response['immagini'][7]['nomefile'],
                    "time" => $Response['immagini'][7]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/IRC/".(Unix_Time_Converter($Response['immagini'][7]['ora'])."_".$Response['immagini'][7]['nomefile'])
                ),
                "3" => Array(
                    "file_name" => $Response['immagini'][8]['nomefile'],
                    "time" => $Response['immagini'][8]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/IRC/".(Unix_Time_Converter($Response['immagini'][8]['ora'])."_".$Response['immagini'][8]['nomefile'])
                ),
                "4" => Array(
                    "file_name" => $Response['immagini'][9]['nomefile'],
                    "time" => $Response['immagini'][9]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/IRC/".(Unix_Time_Converter($Response['immagini'][9]['ora'])."_".$Response['immagini'][9]['nomefile'])
                )
            ),
            "RST" => Array(
                "0" => Array(
                    "file_name" => $Response['immagini'][10]['nomefile'],
                    "time" => $Response['immagini'][10]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/RST/".(Unix_Time_Converter($Response['immagini'][10]['ora'])."_".$Response['immagini'][10]['nomefile'])
                ),
                "1" => Array(
                    "file_name" => $Response['immagini'][11]['nomefile'],
                    "time" => $Response['immagini'][11]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/RST/".(Unix_Time_Converter($Response['immagini'][11]['ora'])."_".$Response['immagini'][11]['nomefile'])
                ),
                "2" => Array(
                    "file_name" => $Response['immagini'][12]['nomefile'],
                    "time" => $Response['immagini'][12]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/RST/".(Unix_Time_Converter($Response['immagini'][12]['ora'])."_".$Response['immagini'][12]['nomefile'])
                ),
                "3" => Array(
                    "file_name" => $Response['immagini'][13]['nomefile'],
                    "time" => $Response['immagini'][13]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/RST/".(Unix_Time_Converter($Response['immagini'][13]['ora'])."_".$Response['immagini'][13]['nomefile'])
                ),
                "4" => Array(
                    "file_name" => $Response['immagini'][14]['nomefile'],
                    "time" => $Response['immagini'][14]['ora'],
                    "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/PNG/RST/".(Unix_Time_Converter($Response['immagini'][14]['ora'])."_".$Response['immagini'][14]['nomefile'])
                )
            )
        ),
        "GIFs" => Array(
            "NEF" => Array(
                "file_name" => "NEF.gif",
                "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/".(Unix_Time_Converter($Response['immagini'][0]['ora'])."_"."NEF.gif")
            ),
            "IRC" => Array(
                "file_name" => "IRC.gif",
                "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/".(Unix_Time_Converter($Response['immagini'][0]['ora'])."_"."IRC.gif")
            ),
            "RST" => Array(
                "file_name" => "RST.gif",
                "image" => "https://genio2003.altervista.org/MAM_Satellite_Images/images/GIF/".(Unix_Time_Converter($Response['immagini'][0]['ora'])."_"."RST.gif")
            ),
        ),
        "Time" => Array(
            "UTC" => gmdate("H:i:s")
        )
    );

    $JSON = json_encode($Result);

    echo ($JSON);

}

function main()
{
    $Response=json_decode(CallAPI("GET","http://webapp.meteoam.it/json/immaginisatellitari/immaginisatellitari.json"),true);

    Delete_Old_Photo("images/PNG/NEF");
    Delete_Old_Photo("images/PNG/IRC");
    Delete_Old_Photo("images/PNG/RST");

    Delete_Old_Photo("images/GIF");

    for($i=0;$i<=4;$i++)
    {
        Base64_to_PNG((Unix_Time_Converter($Response['immagini'][$i]['ora'])."_".$Response['immagini'][$i]['nomefile']),$Response['immagini'][$i]['image'],"NEF");
    }
    for($i=5;$i<=9;$i++)
    {
        Base64_to_PNG((Unix_Time_Converter($Response['immagini'][$i]['ora'])."_".$Response['immagini'][$i]['nomefile']),$Response['immagini'][$i]['image'],"IRC");
    }
    for($i=10;$i<=14;$i++)
    {
        Base64_to_PNG((Unix_Time_Converter($Response['immagini'][$i]['ora'])."_".$Response['immagini'][$i]['nomefile']),$Response['immagini'][$i]['image'],"RST");
    }

    Multiple_PNGs_to_GIF((Unix_Time_Converter($Response['immagini'][0]['ora'])."_"."NEF.gif"),(Unix_Time_Converter($Response['immagini'][0]['ora'])."_"."IRC.gif"),(Unix_Time_Converter($Response['immagini'][0]['ora'])."_"."RST.gif"));

    Make_JSON($Response);
    
}

main();

?>