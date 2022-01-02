<?php
echo '<!DOCTYPE HTML><html>';
echo '<head><meta name="viewport" content="width=device-width, initial-scale=1"><link href="/css/main.css" rel="stylesheet"></head><body>';
echo '<div class=header>umuplayer<form action="/search"><center><input type=text id=poshuk name=q placeholder=Поиск><input type="submit" style="display:none;" value="пошук"></center></form></div><div class="content">';
$ch = curl_init('https://music.youtube.com/channel/'.$_GET["channel"]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 11; M2102J20SG) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$content = curl_exec($ch);
curl_close($ch);
preg_match_all('/data: \'([\s\S]+?)\'}/', $content, $p);
$json = json_decode(stripcslashes($p[1][1]));
echo '<center><img style="max-width:100%;" src="'.preg_replace('/https:\/\/(.*?)\/(.*?)$/','https://pipedproxy-bom.kavin.rocks/$2?host=$1',$json->header->musicImmersiveHeaderRenderer->thumbnail->musicThumbnailRenderer->thumbnail->thumbnails[0]->url).'">';
echo '<h2>'.$json->header->musicImmersiveHeaderRenderer->title->runs[0]->text.'</h2>';
echo $json->header->musicImmersiveHeaderRenderer->description->runs[0]->text;
echo '</center>';
$page = $json->contents->singleColumnBrowseResultsRenderer->tabs[0]->tabRenderer->content->sectionListRenderer->contents;

foreach ($page as $sub){
    if($sub->musicShelfRenderer){
    echo '<h2>'.$sub->musicShelfRenderer->title->runs[0]->text.'</h2>';
foreach($sub->musicShelfRenderer->contents as $track){
echo '<div><a style="display:block;" href="/watch?v='.$track->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->videoId.'&list='.$track->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->playlistId.'">';
echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.preg_replace('/https:\/\/(.*?)\/(.*?)$/','https://pipedproxy-bom.kavin.rocks/$2?host=$1',$track->musicResponsiveListItemRenderer->thumbnail->musicThumbnailRenderer->thumbnail->thumbnails[0]->url).'"></div>';
echo '<div style="display:inline-block;padding:8px;vertical-align:middle;width:calc(100% - 120px);overflow-x:scroll;"><b>'.$track->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->text.'</b><br>';
foreach($track->musicResponsiveListItemRenderer->flexColumns[1]->musicResponsiveListItemFlexColumnRenderer->text->runs as $text){
    echo $text->text;
}
echo '</div></a></div>';
}
}else{
        echo '<h2>'.$sub->musicCarouselShelfRenderer->header->musicCarouselShelfBasicHeaderRenderer->title->runs[0]->text.'</h2>';
foreach($sub->musicCarouselShelfRenderer->contents as $track){
if ($track->musicTwoRowItemRenderer->navigationEndpoint->browseEndpoint->browseEndpointContextSupportedConfigs->browseEndpointContextMusicConfig->pageType==MUSIC_PAGE_TYPE_ALBUM||$track->musicTwoRowItemRenderer->navigationEndpoint->browseEndpoint->browseEndpointContextSupportedConfigs->browseEndpointContextMusicConfig->pageType==MUSIC_PAGE_TYPE_PLAYLIST){
echo '<div><a style="display:block;" href="/playlist?list='.$track->musicTwoRowItemRenderer->menu->menuRenderer->items[0]->menuNavigationItemRenderer->navigationEndpoint->watchPlaylistEndpoint->playlistId.'">';
}else if($track->musicTwoRowItemRenderer->navigationEndpoint->browseEndpoint->browseEndpointContextSupportedConfigs->browseEndpointContextMusicConfig->pageType==MUSIC_PAGE_TYPE_ARTIST){
echo '<div class="artist"><a style="display:block;" href="/channel/'.$track->musicTwoRowItemRenderer->navigationEndpoint->browseEndpoint->browseId.'">';
}else{
echo '<div><a style="display:block;" href="/watch?v='.$track->musicTwoRowItemRenderer->navigationEndpoint->watchEndpoint->videoId.'&list='.$track->musicTwoRowItemRenderer->navigationEndpoint->watchEndpoint->playlistId.'">';
    }
echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.preg_replace('/https:\/\/(.*?)\/(.*?)$/','https://pipedproxy-bom.kavin.rocks/$2?host=$1',$track->musicTwoRowItemRenderer->thumbnailRenderer->musicThumbnailRenderer->thumbnail->thumbnails[0]->url).'"></div>';
echo '<div style="display:inline-block;padding:8px;vertical-align:middle;width:calc(100% - 120px);overflow-x:scroll;"><b>'.$track->musicTwoRowItemRenderer->title->runs[0]->text.'</b><br>';
foreach($track->musicTwoRowItemRenderer->subtitle->runs as $text){
    echo $text->text;
}
echo '</div></a></div>';
}
}
}
echo '</div></body></html>';
?>
