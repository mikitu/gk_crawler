<?php

use seeds\ds\AlphaBeta;
use Illuminate\Database\Seeder;
use seeds\ds\AholdBelgium;
use seeds\ds\AholdNetherlands;
use seeds\ds\AuchanFrance;
use seeds\ds\AuchanHungary;
use seeds\ds\AuchanItaly;
use seeds\ds\AuchanPoland;
use seeds\ds\AuchanRomania;
use seeds\ds\BillaAustria;
use seeds\ds\Carrefour;
use seeds\ds\CarrefourBrasil;
use seeds\ds\CarrefourIndonesia;
use seeds\ds\CarrefourPoland;
use seeds\ds\CarrefourRomania;
use seeds\ds\CarrefourSpain;
use seeds\ds\CarrefourTaiwan;
use seeds\ds\CarrefourTunisia;
use seeds\ds\CarrefourTurkey;
use seeds\ds\Coop;
use seeds\ds\DelhaizeBelgium;
use seeds\ds\EnaFood;
use seeds\ds\EtosNetherlands;
use seeds\ds\FoodLion;
use seeds\ds\Hannaford;
use seeds\ds\Kaufland;
use seeds\ds\Lidl;
use seeds\ds\LidlGermany;
use seeds\ds\MegaImage;
use seeds\ds\Metro;
use seeds\ds\Penny;
use seeds\ds\Sainsburys;
use seeds\ds\SevenEleven;
use seeds\ds\Spar;
use seeds\ds\SparGetmany;
use seeds\ds\SparItaly;
use seeds\ds\Tempo;

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
//        $this->seed(new CarrefourIndonesia());
        $this->seed(new CarrefourTaiwan());
        $this->seed(new CarrefourTunisia());
        $this->seed(new AuchanFrance());
        $this->seed(new AuchanRomania());
        $this->seed(new Sainsburys());
        $this->seed(new AuchanItaly());
        $this->seed(new AuchanHungary());
//        $this->seed(new AuchanPoland());
        $lidl = new Lidl;
        foreach ($lidl->urls as $country_code => $url) {
            $ds = new Lidl();
            $ds->country_code = $country_code;
            if (is_array($url)) {
                $ds->url = implode('*', $url);
            } else {
                $ds->url = $url;
            }
            $this->seed($ds);
        }
        $this->seed(new LidlGermany());
        $this->seed(new BillaAustria());
        $this->seed(new SparItaly());
        $this->seed(new SparGetmany());
        $this->seed(new SevenEleven());
        $this->seed(new Coop());
        $this->seed(new AholdNetherlands());
        $this->seed(new EtosNetherlands());
        $this->seed(new AholdBelgium());
        $this->seed(new DelhaizeBelgium());
        $this->seed(new FoodLion());
        $this->seed(new Hannaford());
        $this->seed(new AlphaBeta());
        $this->seed(new EnaFood());
        $this->seed(new MegaImage());
        $this->seed(new Penny());

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
