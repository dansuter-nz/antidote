<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

define('root_dir',"/var/www/html/antidote_apache");
require(root_dir.'/admin/functions.php');
// Load the absolute server path to the directory the script is running in
//echo  $_SERVER["DOCUMENT_ROOT"]."/admin/connection.php</br>";
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]."/admin");
require(PROJECT_ROOT.'/connection.php');


$fileDir = dirname(__FILE__);

// Make sure we end with a slash
if (substr($fileDir, -1) != '/') {
    $fileDir .= '/';
}

// Load the absolute server path to the document root
$docRoot = $_SERVER['DOCUMENT_ROOT'];
echo "Doc Root " . $docRoot . " </br>";
// Make sure we end with a slash
if (substr($docRoot, -1) != '/') {
    $docRoot .= '/';
}

// Remove docRoot string from fileDir string as subPath string
$subPath = preg_replace('~' . $docRoot . '~i', '', $fileDir);

// Add a slash to the beginning of subPath string
$subPath = '/' . $subPath;          

// Test subPath string to determine if we are in the web root or not
if ($subPath == '/') {
    // if subPath = single slash, docRoot and fileDir strings were the same
    echo "We are running in the web foot folder of http://" . $_SERVER['SERVER_NAME'];
} else {
    // Anyting else means the file is running in a subdirectory
    echo "We are running in the '" . $subPath . "' subdirectory of http://" . $_SERVER['SERVER_NAME'];
}

//error log reader starts here
$file="/var/log/apache2/error.log";
echo "reading from ".$file."<br><br>";
$fh = fopen($file, "r");
$pageText = fread($fh,filesize($file));
fclose($fh);

//$pageText=str_replace("/var/www/html/","rootDir",$pageText);
$string = 'The quick brown fox jumps over the lazy dog.';
$patterns = array();
$patterns[0] = '/quick/';
$patterns[1] = '/brown/';
$patterns[2] = '/fox/';
$replacements = array();
$replacements[0] = 'slow';
$replacements[1] = 'black';
$replacements[2] = 'bear';
ksort($patterns);
ksort($replacements);
//$pageText=preg_replace($patterns, $replacements, $pageText);
$pageText = preg_replace("/\/var\/www\/html\/[a-zA-Z]\//", '<span style="color:blue">/var/www/html/0/</span>', $pageText);
$pageText = preg_replace("/\/var\/www\/html\/alchemy\//", '<span style="#aaa;color:grey">/var/www/html/antidote_apache/</span>', $pageText);
$pageText = preg_replace("/isset/", '<span style="background:yellow">isset</span>', $pageText);
$pageText = preg_replace("/isset/", '<span style="background:yellow">isset</span>', $pageText);
$pageText = preg_replace("/isset/", '<span style="background:yellow">isset</span>', $pageText);
$pageText = preg_replace("/isset/", '<span style="background:yellow">isset</span>', $pageText);
echo nl2br($pageText);
exit;
$text=$pageText;
//echo nl2br($pageText);


// The Regular Expression filter
$reg_exUrl = "/\/var\/www\/html\/[a-zA-Z0-9\-\.]\.php/";


// Check if there is a url in the text
if(preg_match($reg_exUrl, $text, $url)) {
    // make the urls hyper links
    echo preg_replace($reg_exUrl, "<a href=\"{$url[0]}\">{$url[0]}</a> ", $text);
} else {
    // if no urls in the text just return the text
    echo nl2br($pageText);
}









$file = "/var/log/apache2/error.log";

//echo fileperms ( $file )."<br>"; 
//echo fileowner ( $file )."<br>"; // Get Owner
//echo posix_getuid ()."<br>"; // Get User

//if (is_file ( $file )) {
  //  echo "is_file", PHP_EOL;
    //;
//}

//if (is_readable ( $file )) {
  //  echo "is_readable", PHP_EOL;
    //;
//}

//if (is_writable ( $file )) {
  //  echo "is_readable", PHP_EOL;
//}

//fopen ( $file, "w" );


function flushLog()
{}


 
function FnFindText($sSearchText,$sFindText,$sFindOffset,$sEndText,$sEndOffset,$sRegExp) {
//sPg is textString to search
//sFindText is Text to find
//sFindOffset is offset from Start text string can be - or +
//sEndText is the text where searchtext ends
//sEndOffset is the offset text where searchtext ends
//sRegExp=Numeric or Text, (Optional)
if ($sFindText=="" || strlen($sFindText)==0) { 
    $sText=$sSearchText;
} else {
    $iSt=0;
    $iSt=strpos($sSearchText,$sFindText,$iSt)+$sFindOffset;
    if ($iSt+$sFindOffset<=0) {
         return "Error: Start minus offset error in FnFindText routine: functions.asp line 3588.";
         exit();
     }
     //x=ifa(iSt&":"&sSearchText&","&sEndText)
    $iFn=strpos($sSearchText,$sEndText,$iSt+1)+$sEndOffset;
    if ($sEndText=="" || $sEndText=="vbcr") {
        //note this works for any vbcr encoded files but may not work well for large files where you must enter next text
        $iFn=strlen($sSearchText);
    }
    if ($iFn<$iSt) { 
        return "Error: Finish Location is lower value than start FN:".$iFn." ST:".$iSt." error in FnFindText routine: Searching '".$sSearchText."' for '".$sFindText."'";
        exit();
    }
    if ($iFn==$iSt) { 
        if (!$iFn && $iSt==0) {
            //in the case where iFn and iSt are the same asumption is the text is the same
            //change the finish to be be from the after where the first start varible was found.
            $iFn=instr($iSt+1,$sSearchText,$sEndText)+$sEndOffset;
            $sText=substr($sSearchText,$iSt,$iFn-$iSt);
            return $sSearchText;
            exit();
        }
    }
    $sText=substr($sSearchText,$iSt,$iFn-$iSt);
    if (strlen($sRegExp)>0) {
        //$sText=$RegRip[$sText,$sRegExp);
    }
}
return $sText;
}


function TrimPage($sPage,$sFindText,$bEndofText) {
if (strpos($sPage,$sFindText,0)>0) 
    {
        if ($bEndofText==1) {
            $iLenFindText=strlen($sFindText)-1;
        } else {
            $iLenFindText=-1;
        }
        $sPage=right($sPage,strlen($sPage)-strpos($sPage,$sFindText)-$iLenFindText);
    } 
else 
    {
        $sPage=$sPage;
    }
return $sPage;
}



?>