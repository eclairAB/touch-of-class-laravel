<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;
use App\Models\Combo;
use App\Models\Package;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Branch;

class FillDummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill Using Test Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->clear_all();

        $this->create_user();
        $this->create_branch();
        $this->create_service();
        $this->create_combo();
        $this->create_package();
        $this->create_client();
    }

    function clear_all() {
        User::truncate();
        Service::truncate();
        Combo::truncate();
        Package::truncate();
        Client::truncate();
        Appointment::truncate();
        Payment::truncate();
    }

    function create_user() {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('secret'),
        ]);
    }
    function create_branch() {
        $branch = ['name' => 'Bajada'];
        Branch::create($branch);
    }
    function create_service() {
        $items = [
            [
                'name'                  => 'Red Service',
                'commission_percentage' => 7,
                'price'                 => 599,
            ],
            [
                'name'                  => 'Blue Service',
                'commission_percentage' => 8,
                'price'                 => 2499,
            ],
            [
                'name'                  => 'Gold Service',
                'commission_percentage' => 9,
                'price'                 => 3000,
            ],
        ];
        Service::insert($items);
    }
    function create_combo() {
        $items = [
            [
                'name'     => 'Chocolate Combo',
                'price'    => 2999,
                'services' => [
                    [
                        'service_id' => 1
                    ],
                    [
                        'service_id' => 2
                    ],
                ],
            ],
            [
                'name'     => 'Bouquet Combo',
                'price'    => 5699,
                'services' => [
                    [
                        'service_id' => 1
                    ],
                    [
                        'service_id' => 2
                    ],
                    [
                        'service_id' => 3
                    ],
                ],
            ],
        ];
        foreach($items as $item) {
            Combo::create($item)->combo_services()->createMany($item['services']);
        }
    }
    function create_package() {
        $items = [
            [
                'name'                  => 'Amethyst Package',
                'sessions'              => 5,
                'commission_percentage' => 8,
                'price'                 => 9999,
            ],
            [
                'name'                  => 'Emerald Package',
                'sessions'              => 3,
                'commission_percentage' => 8,
                'price'                 => 5999,
            ],
            [
                'name'                  => 'Sapphire Package',
                'sessions'              => 8,
                'commission_percentage' => 9,
                'price'                 => 14599,
            ],
        ];
        Package::insert($items);
    }
    function create_client() {
        $items = [
            [
                'first_name'     => 'Tokyo',
                'last_name'      => 'Olivera',
                'email'          => 'tokyo@gmail.com',
                'contact_number' => '091234567812',
            ],
            [
                'first_name'     => 'Berlin',
                'last_name'      => 'Fonollosa',
                'email'          => 'berlin@gmail.com',
                'contact_number' => '091234567812',
            ],
            [
                'first_name'     => 'Helsinki',
                'last_name'      => 'Dragić',
                'email'          => 'helsinki@gmail.com',
                'contact_number' => '091234567812',
            ],
            [
                'first_name'     => 'Manila',
                'last_name'      => 'Martinez',
                'email'          => 'manila@gmail.com',
                'contact_number' => '091234567812',
            ],
        ];
        Client::insert($items);
    }
}
