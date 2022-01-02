<?php
echo '<!DOCTYPE HTML><html>';
echo '<head><meta name="viewport" content="width=device-width, initial-scale=1"><link href="/css/main.css" rel="stylesheet"></head><body>';
echo '<div class=header>umuplayer<form action="search.php"><center><input type=text id=poshuk name=q placeholder=Поиск value="'.$_GET["q"].'"><input type="submit" style="display:none;" value="пошук"></center></form></div><div class="content">';

$ch = curl_init('https://music.youtube.com/search?q='.urlencode($_GET["q"]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 11; M2102J20SG) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$content = curl_exec($ch);
curl_close($ch);
preg_match_all('/data: \'([\s\S]+?)\'}/', $content, $p);
$json = json_decode(stripcslashes($p[1][1]));
$page = $json->contents->tabbedSearchResultsRenderer->tabs[0]->tabRenderer->content->sectionListRenderer->contents;
foreach ($page as $item){
    echo '<h2>'.$item->musicShelfRenderer->title->runs[0]->text.'</h2>';
    foreach ($item->musicShelfRenderer->contents as $sub){
if($sub->musicResponsiveListItemRenderer->navigationEndpoint->browseEndpoint->browseEndpointContextSupportedConfigs->browseEndpointContextMusicConfig->pageType==MUSIC_PAGE_TYPE_ARTIST){
        echo '<div class="artist"><a style="display:block;" href="/channel/'.$sub->musicResponsiveListItemRenderer->navigationEndpoint->browseEndpoint->browseId.'">';
}else if(($sub->musicResponsiveListItemRenderer->navigationEndpoint->browseEndpoint->browseEndpointContextSupportedConfigs->browseEndpointContextMusicConfig->pageType==MUSIC_PAGE_TYPE_PLAYLIST) || ($sub->musicResponsiveListItemRenderer->navigationEndpoint->browseEndpoint->browseEndpointContextSupportedConfigs->browseEndpointContextMusicConfig->pageType==MUSIC_PAGE_TYPE_ALBUM)){
        echo '<div><a style="display:block;" href="/playlist?list='.$sub->musicResponsiveListItemRenderer->overlay->musicItemThumbnailOverlayRenderer->content->musicPlayButtonRenderer->playNavigationEndpoint->watchPlaylistEndpoint->playlistId.'">';
}else{
        echo '<div><a style="display:block;" href="/watch?v='.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->videoId.'&list='.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->navigationEndpoint->watchEndpoint->playlistId.'">';
}
        echo '<div style=display:inline-block;padding:8px;vertical-align:middle;><img width=60 src="'.preg_replace('/https:\/\/(.*?)\/(.*?)$/','https://pipedproxy-bom.kavin.rocks/$2?host=$1',$sub->musicResponsiveListItemRenderer->thumbnail->musicThumbnailRenderer->thumbnail->thumbnails[0]->url).'"></div>';
        echo '<div style="display:inline-block;padding:8px;vertical-align:middle;width:calc(100% - 120px);overflow-x:scroll;"><b>'.$sub->musicResponsiveListItemRenderer->flexColumns[0]->musicResponsiveListItemFlexColumnRenderer->text->runs[0]->text.'</b><br>';
foreach($sub->musicResponsiveListItemRenderer->flexColumns[1]->musicResponsiveListItemFlexColumnRenderer->text->runs as $text){
    echo $text->text;
}
echo '</div></a></div>';
    }
}
echo '</div></body></html>';
?>
