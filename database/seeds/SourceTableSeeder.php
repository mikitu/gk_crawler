<?php

use Illuminate\Database\Seeder;
use seeds\ds\Kaufland;
use seeds\ds\MetroAustria;
use seeds\ds\MetroBulgaria;
use seeds\ds\MetroChina;
use seeds\ds\MetroCroatia;
use seeds\ds\MetroCzechRepublic;
use seeds\ds\MetroFrance;
use seeds\ds\MetroGermany;
use seeds\ds\MetroHungary;
use seeds\ds\MetroIndia;
use seeds\ds\MetroItaly;
use seeds\ds\MetroJapan;
use seeds\ds\MetroKazakhstan;
use seeds\ds\MetroNetherlands;
use seeds\ds\MetroPakistan;
use seeds\ds\MetroPoland;
use seeds\ds\MetroPortugal;
use seeds\ds\MetroRomania;
use seeds\ds\MetroRussia;
use seeds\ds\MetroBelgium;
use seeds\ds\MetroSerbia;
use seeds\ds\MetroSlovakia;
use seeds\ds\MetroSpain;
use seeds\ds\MetroTurkey;
use seeds\ds\MetroUkraine;
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
//        $this->seed(new MetroRomania);
        $this->seed(new Spar());
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
