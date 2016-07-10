<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\Hospital;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlProcessHospitalDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:process:hospital:details';

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
        $hospitals = Hospital::all();
        $results = [];
        $keys = [];
        foreach($hospitals as $model) {
            if (empty($model->raw_data)) continue;
            $data = json_decode($model->raw_data, true);
            $this->updateModel($model, $data);
            foreach($data as $val) {
                $keys = array_merge($keys, $val);
            }
        }
//        array_unique($keys);
        print_r(array_keys($keys));
    }

    private function updateModel($model, $data)
    {
        foreach($data as $val) {
            if (isset($val['Name'])) {
                $model->name = $val['Name'];
            }
            if (isset($val['Telephone']) && $val['Telephone'] != 'Not available') {
                $model->phone = $val['Telephone'];
            }
            if (isset($val['Adress'])) {
                $model->address = $val['Adress'];
            }
            if (isset($val['Code'])) {
                $model->zipcode = $val['Code'];
            }
            $model->save();
        }
        
    }
}
