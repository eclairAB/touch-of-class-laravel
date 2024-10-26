<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function branch_report($branch_id)
    {
        $branch = Branch::find($branch_id);

        $payments = Payment::where('branch_id', $branch_id)
            ->with(
                'branch',
                'cashier',
                'appointment_package.appointment.client',
                'appointment_package.package',
                'appointment_combo.appointment.client',
                'appointment_combo.combo',
                'appointment_service.appointment.client',
                'appointment_service.service',
            )
            ->get();

        $data = [
            'title' => "Report for $branch->name branch",
            'date' => date('m/d/Y'),
            'content' => 'This is a sample PDF generated using Laravel and dompdf.',
            'payments' => $payments,
        ];

        // Load the view and pass in the data
        $pdf = Pdf::loadView('generated.reports.branch', $data);

        // Return PDF as download
        return $pdf->download('example.pdf');
    }

    public function staff_report($staff_id)
    {
        $staff = User::find($staff_id);

        $payments = Payment::where('cashier_id', $staff_id)
            ->with(
                'branch',
                'cashier',
                'appointment_package.appointment.client',
                'appointment_package.package',
                'appointment_combo.appointment.client',
                'appointment_combo.combo',
                'appointment_service.appointment.client',
                'appointment_service.service',
            )
            ->get();

        $data = [
            'title' => "Report for $staff->first_name $staff->last_name",
            'date' => date('m/d/Y'),
            'content' => 'This is a sample PDF generated using Laravel and dompdf.',
            'payments' => $payments,
        ];

        // Load the view and pass in the data
        $pdf = Pdf::loadView('generated.reports.staff', $data);

        // Return PDF as download
        return $pdf->download('example.pdf');
    }
}
