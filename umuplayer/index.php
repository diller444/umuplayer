<?php
echo '<!DOCTYPE HTML><html>';
echo '<head><meta name="viewport" content="width=device-width, initial-scale=1"><link href="/css/main.css" rel="stylesheet"></head><body>';
echo '<div class=header>umuplayer<form action="/search"><center><input type=text id=poshuk name=q placeholder=Поиск><input type="submit" style="display:none;" value="пошук"></center></form></div><div class="content">';
$ch = curl_init('https://m.youtube.com/music');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 11; M2102J20SG) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$content = curl_exec($ch);
curl_close($ch);
preg_match('/ytInitialData = \'([\s\S]+?)\';/', $content, $p);
$json = json_decode(stripcslashes($p[1]));
$page = $json->contents->singleColumnBrowseResultsRenderer->tabs[0]->tabRenderer->content->sectionListRenderer->contents;
foreach($page as $item){
    echo '<h2>'.$item->shelfRenderer->title->runs[0]->text.'</h2><div class=category>';
    foreach($item->shelfRenderer->content->verticalListRenderer->items as $sub){
        echo '<div class="item">';
        echo '<a href="'.$sub->compactStationRenderer->navigationEndpoint->commandMetadata->webCommandMetadata->url.'">';
        echo '<img src="'.$sub->compactStationRenderer->thumbnail->thumbnails[0]->url.'"><br>';
        echo $sub->compactStationRenderer->title->runs[0]->text;
        echo '<br>';
        echo $sub->compactStationRenderer->videoCountText->runs[0]->text.$sub->compactStationRenderer->videoCountText->runs[1]->text;
        echo '<br>';
        echo '</a></div>';
    }
    echo '</div>';
}
echo '</div></body></html>';
?>
