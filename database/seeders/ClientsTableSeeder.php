<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients=['ali','alaa'];
        foreach ($clients as $client) {
            Client::create([
                'name'=>$client,
                'phone'=>'0112233445',
                'address'=>'almarg'
            ]);
        }
    }
}
