<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $res = $client->request('POST', 'http://www.kaufland.ro/Storefinder/finder', [
            'form_params' => [
                'filtersettings' => '{"filtersettings":{"area":"58.08160399630535,89.67073949218752,27.534010082237103,-36.89176050781248"},"location":{"latitude":44.7829402295756,"longitude":26.38948949218752},"clienttime":"20160526210443"}',
                'loadStores' => 'true',
                'locale' => 'RO'
            ]]);
        echo $res->getStatusCode();
// 200
        echo $res->getBody();

// {"type":"User"...'

// Send an asynchronous request.
//        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
//        $promise = $client->sendAsync($request)->then(function ($response) {
//            echo 'I completed! ' . $response->getBody();
//        });
//        $promise->wait();
    }
}
