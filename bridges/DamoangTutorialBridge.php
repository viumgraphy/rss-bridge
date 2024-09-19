<?php

class DamoangTutorialBridge extends BridgeAbstract
{
    const NAME = 'Damoang Tutorial Bridge';
    const URI = 'https://damoang.net/tutorial';
    const DESCRIPTION = '다모앙 사용기';
    const MAINTAINER = 'viumgraphy';

    const PARAMETERS = [
        [
            'topic' => [
                'type' => 'list',
                ],
            ]
        ]
    ];

    public function collectData()
    {
        $url = sprintf('https://damoang.net/tutorial', $this->getInput('topic'));
        $dom = getSimpleHTMLDOM($url);
        $dom = $dom->find('div[data-component="PaginationList"]', 0);
        if (!$dom) {
            throw new \Exception(sprintf('Unable to find css selector on `%s`', $url));
        }
        $dom = defaultLinkTo($dom, $this->getURI());
        foreach ($dom->find('article[data-component="DetailCard"]') as $article) {
            $a = $article->find('a', 0);
            $this->items[] = [
                'title' => $a->plaintext,
                'uri' => $a->href,
                'content' => $article->find('p', 0)->plaintext,
                'timestamp' => strtotime($article->find('time', 0)->datetime),
            ];
        }
    }
}
