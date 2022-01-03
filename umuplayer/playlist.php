<?php
echo '<!DOCTYPE HTML><html>';
echo '<head><meta name="viewport" content="width=device-width, initial-scale=1"><link href="/css/main.css" rel="stylesheet"></head><body>';
echo '<div class=header>umuplayer<form action="/search"><center><input type=text id=poshuk name=q placeholder=Поиск><input type="submit" style="display:none;" value="пошук"></center></form></div><div class="content">';
if($_GET["listen"]==1){$json = json_decode(file_get_contents('https://pipedapi.kavin.rocks/playlists/'.$_GET["list"]));
echo '<center><img src="'.$json->thumbnailUrl.'">';
preg_match('/^Album – /',$json->name,$album);
if($album){
    echo '<h2>'.preg_replace('/^Album – /','',$json->name).'</h2><a style="text-decoration: underline;" href="?list='.$_GET["list"].'">Видео</a> | Треки';
}else{
    echo '<h2>'.$json->name.'</h2>';
}
echo '</center>';
$page = $json->relatedStreams;
foreach ($page as $sub){
echo '<div><a style="display:block;" href="'.$sub->url.'&list='.$_GET["list"].'">';
if($album){
    echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.$json->thumbnailUrl.'"></div>';
}else{
    echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.$sub->thumbnail.'"></div>';
}
echo '<div style="display:inline-block;padding:8px;vertical-align:middle;width:calc(100% - 120px);overflow-x:scroll;"><b>'.$sub->title.'</b><br>';
echo gmdate('i:s',$sub->duration);
echo '</div></a></div>';
}}else{
$ch = curl_init('https://music.youtube.com/playlist?list='.$_GET["list"]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 11; M2102J20SG) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//echo curl_exec($ch);
$content = curl_exec($ch);
curl_close($ch);
preg_match_all('/data: \'([\s\S]+?)\'}/', $content, $p);
$json = json_decode(stripcslashes($p[1][1]));
echo '<center><img src="'.preg_replace('/https:\/\/(.*?)\/(.*?)$/','https://pipedproxy-bom.kavin.rocks/$2?host=$1',$json->header->musicDetailHeaderRenderer->thumbnail->croppedSquareThumbnailRenderer->thumbnail->thumbnails[1]->url).'">';
echo '<h2>'.$json->header->musicDetailHeaderRenderer->title->runs[0]->text.'</h2>';
foreach($json->header->musicDetailHeaderRenderer->subtitle->runs as $text){
    echo $text->text;
}
$page = $json->contents->singleColumnBrowseResultsRenderer->tabs[0]->tabRenderer->content->sectionListRenderer->contents[0]->musicPlaylistShelfRenderer->contents;
if(!$page){$album=true;$page = $json->contents->singleColumnBrowseResultsRenderer->tabs[0]->tabRenderer->content->sectionListRenderer->contents[0]->musicShelfRenderer->contents;
}
if($album){echo '<br>Видео | <a style="text-decoration: underline;" href="?list='.$_GET["list"].'&listen=1">Треки</a>';}
echo '</center>';
foreach ($page as $sub){
echo '<div><a style="display:block;" href="/watch?v='.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->videoId.'&list='.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->playlistId.'">';
if($album){
    echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.preg_replace('/https:\/\/(.*?)\/(.*?)$/','https://pipedproxy-bom.kavin.rocks/$2?host=$1',$json->header->musicDetailHeaderRenderer->thumbnail->croppedSquareThumbnailRenderer->thumbnail->thumbnails[0]->url).'"></div>';
}else{echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.preg_replace('/https:\/\/(.*?)\/(.*?)$/','https://pipedproxy-bom.kavin.rocks/$2?host=$1',$sub->musicResponsiveListItemRenderer->thumbnail->musicThumbnailRenderer->thumbnail->thumbnails[0]->url).'"></div>';}
echo '<div style="display:inline-block;padding:8px;vertical-align:middle;width:calc(100% - 120px);overflow-x:scroll;"><b>'.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->text.'</b><br>';
if($album){
echo $sub->musicResponsiveListItemRenderer->flexColumns[2]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->text;
}else{foreach($sub->musicResponsiveListItemRenderer->flexColumns[1]->musicResponsiveListItemFlexColumnRenderer->text->runs as $text){
    echo $text->text;
}}
echo '</div></a></div>';}
}
echo '</div></body></html>';
?>
