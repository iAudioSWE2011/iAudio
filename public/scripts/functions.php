<?php


function email_check($email) {
  if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i",
    $email))
    return false;
  return true;
}

/**
 * Fixes the odd indexing of multiple file uploads from the format:
 *
 * $_FILES['field']['key']['index']
 *
 * To the more standard and appropriate:
 *
 * $_FILES['field']['index']['key']
 *
 * @param array $files
 * @author Corey Ballou
 * @link http://www.jqueryin.com
 */
function fixFilesArray(&$files)
{
    $names = array( 'name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);

    foreach ($files as $key => $part) {
        // only deal with valid keys and multiple files
        $key = (string) $key;
        if (isset($names[$key]) && is_array($part)) {
            foreach ($part as $position => $value) {
                $files[$position][$key] = $value;
            }
            // remove old key reference
            unset($files[$key]);
        }
    }
}

function mp3info($file)
{
    $fp = fopen($file, "rb");
    if (!$fp) return 0;
    
    // Try to find ID3v1.x
    fseek($fp, filesize($file)-128);
    $id3v1 = fread($fp, 128);
    if (substr($id3v1, 0, 3) == "TAG")
    { // Yay!
        $mp3[title] = trim(substr($id3v1, 3, 30));
        $mp3[artist] = trim(substr($id3v1, 33, 30));
        $mp3[album] = trim(substr($id3v1, 63, 30));
        $mp3[year] = trim(substr($id3v1, 93, 4));
        if (substr($id3v1, 125, 1) == "\0" && substr($id3v1, 126, 1) != "\0")
        { // we got a ID3v1.1 here
            $mp3[comment] = trim(substr($id3v1, 97, 29));
            $mp3[track] = ord(substr($id3v1, 126, 1));
        }
        else
        { // old ID3v1
            $mp3[comment] = trim(substr($id3v1, 97, 30));
        }
        $mp3[genre] = ord(substr($id3v1, 127, 1));
    }
    else $mp3 = 0;

    fclose($fp);

    return $mp3;
}  

?>
