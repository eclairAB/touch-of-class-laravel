<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Service;
use App\Models\Combo;
use App\Models\Package;
use App\Models\Client;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Branch;
use App\Models\Discount;

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

        $this->create_roles();
        $this->create_branch();
        $this->create_user();
        $this->create_service();
        $this->create_combo();
        $this->create_package();
        $this->create_client();
        $this->create_discount();
    }

    function clear_all() {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Branch::truncate();
        Role::truncate();
        User::truncate();
        Service::truncate();
        Combo::truncate();
        Package::truncate();
        Client::truncate();
        Appointment::truncate();
        Payment::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    function create_roles() {
        $items = [
            [
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cashier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stylist',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nurse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Other Staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Role::insert($items);
    }

    function create_user() {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'contact_number' => '09999999999',
            'role_id' => 1,
            'assigned_branch_id' => null,
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'active_employee' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create([
            'first_name' => 'Cashier',
            'last_name' => 'Cashier',
            'contact_number' => '09999999999',
            'role_id' => 2,
            'assigned_branch_id' => 1,
            'email' => 'cashier@example.com',
            'password' => Hash::make('secret'),
            'active_employee' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create([
            'first_name' => 'Stylist',
            'last_name' => 'Stylist',
            'contact_number' => '09999999999',
            'role_id' => 3,
            'assigned_branch_id' => 1,
            'email' => 'stylist@example.com',
            'password' => Hash::make('secret'),
            'active_employee' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create([
            'first_name' => 'Nurse',
            'last_name' => 'Nurse',
            'contact_number' => '09999999999',
            'role_id' => 4,
            'assigned_branch_id' => 1,
            'email' => 'nurse@example.com',
            'password' => Hash::make('secret'),
            'active_employee' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    function create_branch() {
        $branch = ['name' => 'Bajada'];
        Branch::create($branch);
    }
    function create_service() {
        $items = [
            [
                'name'                  => 'Classic Manicure',
                'commission_percentage' => 7,
                'price'                 => 200,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                'name'                  => 'Classic Footspa',
                'commission_percentage' => 8,
                'price'                 => 600,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                'name'                  => 'Korean Basic Facial',
                'commission_percentage' => 9,
                'price'                 => 700,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
            [
                'name'                  => 'Korean Glass Skin',
                'commission_percentage' => 9,
                'price'                 => 2400,
                'created_at'             => now(),
                'updated_at'             => now(),
            ],
        ];
        Service::insert($items);
    }
    function create_combo() {
        $items = [
            [
                'name'          => 'Chocolate Combo',
                'price'         => 2999,
                'created_at'    => now(),
                'updated_at'    => now(),
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
                'name'          => 'Bouquet Combo',
                'price'         => 5699,
                'created_at'    => now(),
                'updated_at'    => now(),
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
                'name'                  => 'Eyebags',
                'sessions'              => 10,
                'commission_percentage' => 8,
                'price'                 => 999,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'name'                  => 'Double Chin',
                'sessions'              => 10,
                'commission_percentage' => 8,
                'price'                 => 999,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'name'                  => 'Cheeks',
                'sessions'              => 10,
                'commission_percentage' => 9,
                'price'                 => 999,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'name'                  => 'Arms',
                'sessions'              => 10,
                'commission_percentage' => 9,
                'price'                 => 2999,
                'created_at'            => now(),
                'updated_at'            => now(),
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
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name'     => 'Berlin',
                'last_name'      => 'Fonollosa',
                'email'          => 'berlin@gmail.com',
                'contact_number' => '091234567812',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name'     => 'Helsinki',
                'last_name'      => 'Dragić',
                'email'          => 'helsinki@gmail.com',
                'contact_number' => '091234567812',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'first_name'     => 'Manila',
                'last_name'      => 'Martinez',
                'email'          => 'manila@gmail.com',
                'contact_number' => '091234567812',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];
        Client::insert($items);
    }
    function create_discount() {
        $items = [
            [
                'discount_name' => 'PWD',
                'percentage' => true,
                'amount' => '20.00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'discount_name' => 'VIP',
                'percentage' => false,
                'amount' => '500.00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Discount::insert($items);
    }
}
