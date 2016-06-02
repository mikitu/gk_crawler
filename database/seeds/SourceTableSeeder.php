<?php

use Illuminate\Database\Seeder;
use seeds\ds\Carrefour;
use seeds\ds\CarrefourPoland;
use seeds\ds\CarrefourRomania;
use seeds\ds\Kaufland;
use seeds\ds\Metro;
use seeds\ds\Spar;

class SourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->seed(new Kaufland);
//        $metro = new Metro;
//        foreach ($metro->urls as $country_code => $url) {
//            $ds = new Metro();
//            $ds->country_code = $country_code;
//            $ds->url = $url;
//            $this->seed($ds);
//        }
//        $this->seed(new Spar());
//        $carrefour = new Carrefour;
//        foreach ($carrefour->urls as $country_code => $url) {
//            $ds = new Carrefour();
//            $ds->country_code = $country_code;
//            $ds->url = $url;
//            $this->seed($ds);
//        }
//        $this->seed(new CarrefourPoland());
        $this->seed(new CarrefourRomania());
    }

    protected function seed($ds)
    {
        $model = (new GkCrawler\Model\Source())->firstOrNew(['name' => $ds->name, 'country_code' => $ds->country_code]);
        $model->url = $ds->url;
        if ($ds->data) {
            $model->data = serialize($ds->data);
        }
        if ($ds->headers) {
            $model->headers = serialize($ds->headers);
        }
        if ($ds->country_code) {
            $model->country_code = $ds->country_code;
        }
        $model->method = $ds->method;
        $model->save();
    }
}
