<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Support\Carbon;
use App\Models\AppointmentPackageRedeem;
use App\Models\AppointmentComboRedeem;
use App\Models\AppointmentServiceRedeem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    function appointment_eloquent() {
        return [
            'client',
            // 'payments',
            'appointment_packages.package_redeems',
            'appointment_packages.package',
            'appointment_combos.combo_redeems.service',
            'appointment_combos.combo.combo_services.service',
            'appointment_services.service_redeems',
            'appointment_services.service',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with($this->appointment_eloquent())->get();
        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = $request->toArray();
        // $payment = [
        //     'amount_paid' => $request->amount_paid,
        //     'branch_id' => $request->branch_id,
        // ];
        if($request->amount_paid >= $request->amount_payable) {
            $payload['fully_paid'] = true;
        }

        $payload = $this->handle_payment($payload);

        $appointment = Appointment::create($payload);
        if(isset($payload['packages'])) {
            foreach ($payload['packages'] as $key => $value) {
                $payload['packages'][$key]['package_id'] = $value['id'];
            }
            $appointment_packages = $appointment->appointment_packages()->createMany($payload['packages']);
            $this->map_package_redeems($appointment_packages, $payload);
        }
        if(isset($payload['combos'])) {
            foreach ($payload['combos'] as $key => $value) {
                $payload['combos'][$key]['combo_id'] = $value['id'];
            }
            $appointment_combos = $appointment->appointment_combos()->createMany($payload['combos']);
            $this->map_combo_redeems($appointment_combos, $payload);
        }
        if(isset($payload['services'])) {
            foreach ($payload['services'] as $key => $value) {
                $payload['services'][$key]['service_id'] = $value['id'];
            }
            $appointment_services = $appointment->appointment_services()->createMany($payload['services']);
            $this->map_service_redeems($appointment_services, $payload);
        }
        // $appointment->payments()->create($payment);

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::with($this->appointment_eloquent())->where('client_id', $id)->get();
        return response()->json($appointment);
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     $appointment = Appointment::findOrFail($id);
    //     $appointment->update($request->toArray());
    //     return response()->json(['message' => 'Appointment updated successfully', 'appointment' => $appointment], 200);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Appointment::where('id', $id)->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }


        function map_package_redeems($appointment_package, $payload) {
            # expects \Appointment, $payload->sessions

            foreach($payload['packages'] as $key => $item) {
                for ($i=0; $i < $item['sessions']; $i++) {
                    $package_redeem[$key][] = [
                        'appointment_package_id'    => $appointment_package[$key]['id'],
                        'branch_id'                 => null,
                        'stylist_id'                => null,
                        'session_no'                => 1 + $i,
                        'paid'                      => (1 + $i) <= $item['redeems_paid'],
                    ];
                }

                $this->save_payment_record('appointment_package_id', $appointment_package[$key]['id'], $key, $payload);
                AppointmentPackageRedeem::insert($package_redeem[$key]);
            }
        }
        function map_combo_redeems($appointment_combo, $payload) {
            # expects \Appointment, $payload->sessions

            foreach($payload['combos'] as $key => $item) {
                for ($i=0; $i < count($item['combo_services']); $i++) {
                    $combo_redeem[$key][] = [
                        'appointment_combo_id'  => $appointment_combo[$key]['id'],
                        'branch_id'             => null,
                        'stylist_id'            => null,
                        'service_id'            => $item['combo_services'][$i]['service_id'],
                        'paid'                  => (1 + $i) <= $item['redeems_paid'],
                    ];
                }

                $this->save_payment_record('appointment_combo_id', $appointment_combo[$key]['id'], $key, $payload);
                AppointmentComboRedeem::insert($combo_redeem[$key]);
            }
        }
        function map_service_redeems($appointment_service, $payload) {
            # expects \Appointment, $payload->sessions

            foreach($payload['services'] as $key => $item) {
                    $service_redeems[$key][] = [
                        'appointment_service_id'    => $appointment_service[$key]['id'],
                        'branch_id'                 => null,
                        'stylist_id'                => null,
                        'paid'                      => $item['redeems_paid'],
                    ];

                $this->save_payment_record('appointment_service_id', $appointment_service[$key]['id'], $key, $payload);
                AppointmentServiceRedeem::insert($service_redeems[$key]);
            }
        }
        function handle_payment($payload) {
            $total_products = 0;
            if(isset($payload['packages'])) {
                $total_products += count($payload['packages']);
            }
            if(isset($payload['combos'])) {
                $total_products += count($payload['combos']);
            }
            if(isset($payload['services'])) {
                $total_products += count($payload['services']);
            }

            foreach ($payload as $key => $value) {
                if($key == 'packages') {
                    foreach ($value as $product_key => $product) {
                        $values = $this->payment_calc($product, $payload);

                        $payload['packages'][$product_key]['payment_share'] = $values->payment_share;
                        $payload['packages'][$product_key]['balance'] = $values->balance;
                        $payload['packages'][$product_key]['redeems_paid'] = $values->redeems_paid;
                    }
                }
                if($key == 'combos') {
                    foreach ($value as $product_key => $product) {
                        $values = $this->payment_calc($product, $payload);

                        $payload['combos'][$product_key]['payment_share'] = $values->payment_share;
                        $payload['combos'][$product_key]['balance'] = $values->balance;
                        $payload['combos'][$product_key]['redeems_paid'] = $values->redeems_paid;
                    }
                }
                if($key == 'services') {
                    foreach ($value as $product_key => $product) {
                        $values = $this->payment_calc($product, $payload);

                        $payload['services'][$product_key]['payment_share'] = $values->payment_share;
                        $payload['services'][$product_key]['balance'] = $values->balance;
                        $payload['services'][$product_key]['redeems_paid'] = $values->redeems_paid;
                    }
                }
            }

            return $payload;
        }

        function payment_calc($product, $payload) {
            $values = (object)[];
            $values->percentage     = round(($product['price'] / $payload['amount_payable']), 2);

            $values->payment_share  = $payload['amount_paid'] * $values->percentage;
            $values->balance        = $product['price'] - $values->payment_share;
            $values->balance        = $payload['amount_paid'] == $payload['amount_payable'] ? 0 : $values->balance;

            if(isset($product['sessions'])) {
                $values->redeems_paid = floor($values->payment_share / ($product['price'] / $product['sessions']));
            }
            elseif(isset($product['combo_services'])) {
                $values->redeems_paid = floor($values->payment_share / ($product['price'] / count($product['combo_services'])));
            }
            else {
                $values->redeems_paid = $values->balance == 0;
            }
            return $values;
        }

        function save_payment_record($product_column, $appointment_product_id, $nth_service, $payload) {
            switch ($product_column) {
                case 'appointment_package_id':
                    $product_type = 'packages';
                    break;
                case 'appointment_combo_id':
                    $product_type = 'combos';
                    break;
                case 'appointment_service_id':
                    $product_type = 'services';
                    break;
            }

            $payload[$product_column]       = $appointment_product_id;
            $payload['cashier_id']          = Auth::user()->id;
            $payload['amount_paid']         = $payload[$product_type][$nth_service]['payment_share'];
            $payload['payment_method']      = $payload['payment_type'];
            $payload['reference_no']        = isset($payload['reference']) ? $payload['reference'] : null;
            $payload['biller_name']         = isset($payload['biller']) ? $payload['biller'] : null;
            $payload['payment_milestone']   = $payload['amount_paid'] >= $payload['amount_payable'] ? 'Full Payment' : 'Downpayment';

            Payment::create($payload);
        }

    public function upload_loyalty_cards (Request $request) {

        $files = $request->file('images');
        $savedFiles = [];

        foreach ($files as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = Storage::disk('public')->put('uploads/client/loyalty/appointment/' . $request->appointment_id, $file, 'public'); // Save in 'storage/app/public/uploads'

            $savedFiles[] = $filePath;
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'files' => $savedFiles
        ], 200);
    }

    public function fetch_loyalty_card($client_id) {
        $appointment_ids = Appointment::where('client_id', $client_id)->pluck('id');

        $files = [];
        foreach ($appointment_ids as $id) {
            $appointment_files = Storage::disk('public')->allFiles("uploads/client/loyalty/appointment/$id/");
            if (count($appointment_files) > 0) {
                $files = array_merge($files, $appointment_files);
            }
        }

        $images = array_filter($files, function($file) {
            return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
        });

        $imageDetails = [];
        foreach ($images as $image) {
            $imageDetails[] = [
                'url' => env('APP_URL') . '/storage/' . $image,
                'upload_date' => Carbon::createFromTimestamp(Storage::disk('public')->lastModified($image))->format('M j, Y'),
            ];
        }

        return response()->json($imageDetails);
    }

    function redeems(Request $request) {
        // $columns = ['id', 'branch_id', 'cashier_id', 'stylist_id'];

        // $salads = AppointmentPackageRedeem::select($columns)->with('stylist','cashier','branch');
        // $mains = AppointmentComboRedeem::select($columns)->with('stylist','cashier','branch');
        // $drinks = AppointmentServiceRedeem::select($columns)->with('stylist','cashier','branch');
        // $merged = $salads->union($mains)->union($drinks);
        // $results = $merged->whereNotNull('branch_id')->get();

        // return response()->json($results);



        $appointments = Appointment::whereHas('appointment_packages.package_redeems', function($q) {
                            $q->whereNotNull('branch_id');
                        })
                        ->orWhereHas('appointment_combos.combo_redeems', function($q) {
                            $q->whereNotNull('branch_id');
                        })
                        ->orWhereHas('appointment_services.service_redeems', function($q) {
                            $q->whereNotNull('branch_id');
                        })
                        ->with(
                            'branch',
                            'appointment_packages.package_redeems',
                            'appointment_combos.combo_redeems',
                            'appointment_services.service_redeems'
                        )
                        ->get();

        return response()->json($appointments);
    }
}
