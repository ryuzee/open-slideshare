<?php

$this->set('channelData', array(
    'title' => $title,
    'link' => $this->Html->url('/', true),
    'description' => $description,
    'language' => 'en-us'
));

foreach ($slides as $slide) {
    $slideTime = strtotime($slide['Slide']['created']);

    $slideLink = array(
        'controller' => 'slides',
        'action' => 'view',
        $slide['Slide']['id']
    );

    // Remove & escape any HTML to make sure the feed content will validate.
    $bodyText = h(strip_tags($slide['Slide']['description']));
    $bodyText = $this->Common->truncate($bodyText, 400);

    echo  $this->Rss->item(array(), array(
        'title' => $slide['Slide']['name'],
        'link' => $slideLink,
        'guid' => array('url' => $slideLink, 'isPermaLink' => 'true'),
        'description' => $bodyText,
        'pubDate' => $slide['Slide']['created']
    ));
    echo "\n";
}
