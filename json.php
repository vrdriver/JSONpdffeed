<?php 
header('Content-Type: application/json');

/*
 * A JSON Feed to display PDF files.
 * Updated by Stephen Monro
 * bluetomatomedia.com
 * 
 * Original work by Rob W of http://www.podcast411.com/
 * Updated by Aaron Snoswell (aaronsnoswell@gmail.com)
 * 
 *
 * Save this .php file wherever you like on your server. The URL for this .php
*/ 

/* 
 * CONFIGURATION VARIABLES:
 * For more info on these settings
 *
 * JSON SCHEMA: https://jsonfeed.org/version/1
 
 */
// ============================================
// General Configuration Options
// Location of PDF's on server. TRAILING SLASH REQ'D.
$files_dir = "/home/yoursite.com/apps/pdf/";
$files_dir = getcwd().'/';
// Corresponding url for accessing the above directory. TRAILING SLASH REQ'D.
$files_url = "https://resources.yoursite.com/apps/pdf/";
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $protocol = 'http://';
} else {
    $protocol = 'https://';
}
$base_url = $protocol . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);
$files_url = $base_url.'/';
  
// ====================================================== Generic feed options
// Your feed's title
$feed_title = "Your PDF json list";
// 'More info' link for your feed
$feed_link = "https://resources.yoursite.com/apps/pdf/";
// homepage
$home_page = "https://yoursites.com";
// Brief description
$feed_description = "";
// You, or your organisation's name
$feed_author = "You"; 
// END OF CONFIGURATION VARIABLES
  
?>
{
    "version": "https://jsonfeed.org/version/1",
    "user_comment": "<? echo $feed_description; ?>",
    "title": "<? echo $feed_title; ?>",
    "home_page_url": "<?php echo $home_page; ?>",
    "feed_url": "<? echo $feed_link; ?>",
    "items":
    [
        <?php
	// <!-- The file listings -->
        
	$tooutput = "";
    $files = scandir($files_dir);
    rsort($files); // sort files in descending order.
    
    foreach ($files as $file) 
	{
        $file_path = $files_dir . (string)$file;
        // not . or .., ends in .mp3
	    
            if(is_file($file_path) && strrchr($file_path, '.') == ".pdf")
	    {
            // Initialise file details to sensible defaults
            $file_title = $file;
            $file_url = $files_url . $file;
            $file_author = $feed_author;
            $file_duration = "";
            $file_description = "";
            $file_date = date(DateTime::RFC2822, filemtime($file_path));
            $file_size = filesize($file_path);
            
            $basename = basename($file_path); 
		    echo $tooutput . "\n";
	?>
        {
            "id": "",
            "date_published": "<? echo $file_date; ?>",
            "url": "<? echo $file_url; ?>", 
            "size_in_bytes": <? echo $file_size; ?>,
            "filename": "<? echo str_replace(".pdf", "", str_replace("_", " ", $basename)); ?>"
        }
        <?php
	$tooutput = ","; 
            }
        }
        ?> 
	]
}
