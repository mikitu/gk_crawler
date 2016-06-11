<?php

use Illuminate\Database\Seeder;
use seeds\ds\AuchanFrance;
use seeds\ds\AuchanRomania;
use seeds\ds\Carrefour;
use seeds\ds\CarrefourBrasil;
use seeds\ds\CarrefourIndonesia;
use seeds\ds\CarrefourPoland;
use seeds\ds\CarrefourRomania;
use seeds\ds\CarrefourSpain;
use seeds\ds\CarrefourTaiwan;
use seeds\ds\CarrefourTunisia;
use seeds\ds\CarrefourTurkey;
use seeds\ds\Kaufland;
use seeds\ds\Metro;
use seeds\ds\Sainsburys;
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
        $this->seed(new Kaufland);
        $metro = new Metro;
        foreach ($metro->urls as $country_code => $url) {
            $ds = new Metro();
            $ds->country_code = $country_code;
            $ds->url = $url;
            $this->seed($ds);
        }
        $this->seed(new Spar());
        $carrefour = new Carrefour;
        foreach ($carrefour->urls as $country_code => $url) {
            $ds = new Carrefour();
            $ds->country_code = $country_code;
            $ds->url = $url;
            $this->seed($ds);
        }
        $this->seed(new CarrefourPoland());
        $this->seed(new CarrefourRomania());
        $this->seed(new CarrefourSpain());
        $this->seed(new CarrefourTurkey());
        $this->seed(new CarrefourBrasil());
        $this->seed(new CarrefourIndonesia());
        $this->seed(new CarrefourTaiwan());
//        $this->seed(new CarrefourTunisia());
        $this->seed(new AuchanFrance());
        $this->seed(new AuchanRomania());
        $this->seed(new Sainsburys());
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
