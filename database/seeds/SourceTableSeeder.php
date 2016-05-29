<?php

use Illuminate\Database\Seeder;

class SourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'filtersettings' => '{"filtersettings":{"area":"58.08160399630535,89.67073949218752,27.534010082237103,-36.89176050781248"},"location":{"latitude":44.7829402295756,"longitude":26.38948949218752},"clienttime":"20160526210443"}',
            'loadStores' => 'true',
            'locale' => 'RO',
        ];
        $model = new GkCrawler\Model\Source();
        $model->name = 'Kauffland';
        $model->url='http://www.kaufland.ro/Storefinder/finder';
        $model->data = serialize($data);
        $model->method = 'POST';
        $model->save();
    }
}
