# JSONpdffeed
Get a JSON list of the PDF files in a directory


Just drop this file on your webserver with PDF files in it, configure this file with the correct locations, and it should produce a JSON file like the following:



{
    "version": "https://jsonfeed.org/version/1",
    "user_comment": "",
    "title": "C4I Australia Newspaper?",
    "home_page_url": "https://c4israel.org",
    "feed_url": "https://resources.yoursite.com/apps/pdf/newspaper/",
    "items":
    [
        
        {
            "id": "",
            "date_published": "Sun, 27 May 2018 09:33:50 +0000",
            "url": "https://resources.yoursite.com/apps/pdf/newspaper/Apr_2018.pdf", 
            "size_in_bytes": 6886405,
            "filename": "Apr 2018"
        }
        ,
        {
            "id": "",
            "date_published": "Sun, 27 May 2018 09:33:27 +0000",
            "url": "https://resources.yoursite.com/apps/pdf/newspaper/Feb_2018.pdf", 
            "size_in_bytes": 5855151,
            "filename": "Feb 2018"
        }
         
	]
}
