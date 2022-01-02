<?php
echo '<!DOCTYPE HTML><html>';
echo '<head><meta name="viewport" content="width=device-width, initial-scale=1"><link href="/css/main.css" rel="stylesheet"></head><body>';
echo '<div class=header>umuplayer<form action="/search"><center><input type=text id=poshuk name=q placeholder=Поиск><input type="submit" style="display:none;" value="пошук"></center></form></div><div class="content">';
$ch = curl_init('https://music.youtube.com/playlist?list='.$_GET["list"]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 11; M2102J20SG) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$content = curl_exec($ch);
curl_close($ch);
preg_match_all('/data: \'([\s\S]+?)\'}/', $content, $p);
$json = json_decode(stripcslashes($p[1][1]));
echo '<center><img src="'.$json->header->musicDetailHeaderRenderer->thumbnail->croppedSquareThumbnailRenderer->thumbnail->thumbnails[1]->url.'">';
echo '<h2>'.$json->header->musicDetailHeaderRenderer->title->runs[0]->text.'</h2>';
foreach($json->header->musicDetailHeaderRenderer->subtitle->runs as $text){
    echo $text->text;
}
echo '</center>';
$page = $json->contents->singleColumnBrowseResultsRenderer->tabs[0]->tabRenderer->content->sectionListRenderer->contents[0]->musicPlaylistShelfRenderer->contents;
if(!$page){$album=true;$page = $json->contents->singleColumnBrowseResultsRenderer->tabs[0]->tabRenderer->content->sectionListRenderer->contents[0]->musicShelfRenderer->contents;
}
foreach ($page as $sub){
echo '<div><a style="display:block;" href="/watch?v='.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->videoId.'&list='.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->playlistId.'">';
if($album){
    echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.$json->header->musicDetailHeaderRenderer->thumbnail->croppedSquareThumbnailRenderer->thumbnail->thumbnails[0]->url.'"></div>';
}else{echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.$sub->musicResponsiveListItemRenderer->thumbnail->musicThumbnailRenderer->thumbnail->thumbnails[0]->url.'"></div>';}
echo '<div style="display:inline-block;padding:8px;vertical-align:middle;width:calc(100% - 120px);overflow-x:scroll;"><b>'.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->text.'</b><br>';
if($album){
echo $sub->musicResponsiveListItemRenderer->flexColumns[2]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->text;
}else{foreach($sub->musicResponsiveListItemRenderer->flexColumns[1]->musicResponsiveListItemFlexColumnRenderer->text->runs as $text){
    echo $text->text;
}}
echo '</div></a></div>';
}
echo '</div></body></html>';
?>
