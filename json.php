<?php 

error_reporting(E_ALL); 
ini_set('display_errors', '1');

/*
 * A JSON Feed to display PDF files.
 * Updated by Stephen Monro
 * bluetomatomedia.com
 *
 * NOPE ---- iTunes-Compatible RSS 2.0 MP3 subscription feed script ----
 * Original work by Rob W of http://www.podcast411.com/
 * Updated by Aaron Snoswell (aaronsnoswell@gmail.com)
 *
 * Recurses a given directory, reading MP3 ID3 tags and generating an itunes
 * compatible RSS podcast feed.
 *
 * Save this .php file wherever you like on your server. The URL for this .php
*/

//error_reporting(E_ALL); 
//ini_set('display_errors', '1');

/* 
 * CONFIGURATION VARIABLES:
 * For more info on these settings
 *
 * JSON SCHEMA: https://jsonfeed.org/version/1
 
 */
// ============================================
// General Configuration Options
// Location of MP3's on server. TRAILING SLASH REQ'D.
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
// Location of getid3 folder, leave blank to disable. TRAILING SLASH REQ'D.
$getid3_dir = "getid3/";
$getid3_dir = "";


// ====================================================== Generic feed options
// Your feed's title
$feed_title = "Your Newspaper json list";
// 'More info' link for your feed
$feed_link = "https://resources.yoursite.com/apps/pdf/";
// homepage
$home_page = "https://c4israel.org";
// Brief description
$feed_description = "";
// Copyright / license information
$feed_copyright = "All content &#0169; You" . date("Y");
// How often feed readers check for new material (in seconds) -- mostly ignored by readers
$feed_ttl = 60 * 60 * 24;
// Language locale of your feed, eg en-us, de, fr etc. See http://www.rssboard.org/rss-language-codes
$feed_lang = "en-au";
// ============================================== iTunes-specific feed options
// You, or your organisation's name
$feed_author = "You";
// Feed author's contact email address
$feed_email="";
// Url of a 170x170 .png image to be used on the iTunes page
$feed_image = "";
// If your feed contains explicit material or not (yes, no, clean)
$feed_explicit = "clean";
// iTunes major category of your feed
$feed_category = "News";
// iTunes minor category of your feed
$feed_subcategory = "news";
// END OF CONFIGURATION VARIABLES
// If getid3 was requested, attempt to initialise the ID3 engine
$getid3_engine = NULL;

if(strlen($getid3_dir) != 0) {
    require_once($getid3_dir . 'getid3.php');
    $getid3_engine = new getID3;
}
/*
// Write XML heading
 echo '<?xml version="1.0" encoding="utf-8" ?>';


<!-- generator="awesome-sauce/1.1" -->

<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">

    <channel>
        <title><? echo $feed_title; ?></title>
        <link><? echo $feed_link; ?></link>

        <!-- iTunes-specific metadata -->
        <itunes:author><? echo $feed_author; ?></itunes:author>
        <itunes:owner>
            <itunes:name><? echo $feed_author; ?></itunes:name>
            <itunes:email><? echo $feed_email; ?></itunes:email>
        </itunes:owner>

        <itunes:image href="<? echo $feed_image; ?>" />
        <itunes:explicit><? echo $feed_explicit; ?></itunes:explicit>
        <itunes:category text="<? echo $feed_category; ?>">
            <itunes:category text="<? echo $feed_subcategory; ?>" />
        </itunes:category>

        <itunes:summary><? echo $feed_description; ?></itunes:summary>

        <!-- Non-iTunes metadata -->
        <category>Music</category>
        <description><? echo $feed_description; ?></description>
        
        <language><? echo $feed_lang; ?></language>
        <copyright><? echo $feed_copyright; ?></copyright>
        <ttl><? echo $feed_ttl; ?></ttl>
*/
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
    sort($files); 
    
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
                
                // Read file metadata from the ID3 tags
                if(!is_null($getid3_engine))
		{
                    $id3_info = $getid3_engine->analyze($file_path);
                    getid3_lib::CopyTagsToComments($id3_info);
                    
                    
                    if(isset($id3_info["comments_html"]["title"][0]))
                    {
                    $file_title = $id3_info["comments_html"]["title"][0];
                    } else  $file_title  = "";
                    
                    
			if(isset($id3_info["comments_html"]["artist"][0]))
			{
				$file_author = $id3_info["comments_html"]["artist"][0];
			} else  $file_title  = "";
			
			if(isset($id3_info["playtime_string"]))
			{
				$file_duration = $id3_info["playtime_string"];
			} else  $file_title  = "";
	}
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