<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\EmbassyTmp;

class CrawlEmbassyCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:embassy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = 'http://embassy.goabroad.com/all-embassies-located-in';
        $request = $this->client->get($url);
        $body = $request->getBody();
        preg_match_all('#<a href="(/embassies-in/[^"]+)">([^<]+)</a>#iU', $body, $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
            $model = new EmbassyTmp();
            $model->url = 'http://embassy.goabroad.com' . $match[1];
            $model->country = trim($match[2]);
            $request = $this->client->get('http://embassy.goabroad.com' . $match[1]);
            $body = $request->getBody();
            $model->source = $this->clean($body);
            $model->save();
        }
    }
}
