<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CommissionHistory;
use App\Models\Expense;
class PdfController extends Controller
{
    public function branch_report($branch_id, Request $req)
    {
        $month = $req->month;
        $year = $req->year;

        $branch = Branch::find($branch_id);
        $monthName = Carbon::createFromFormat('m', $month)->format('F');
        $payments = Payment::where('branch_id', $branch_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
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
        $commissions = CommissionHistory::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->whereHas('stylist', function ($query) use ($branch_id) {
            $query->where('assigned_branch_id', $branch_id);
        })
        ->get();
        $expense = Expense::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->whereHas('cashier', function ($query) use ($branch_id) {
            $query->where('assigned_branch_id', $branch_id);
        })
        ->get();
        $totalCommission = $commissions->sum('commission_amount');
        $totalgross = $payments->sum('amount_paid');
        $totalexpense = $expense->sum('amount');
        $totalNet = $totalCommission + $totalexpense;
        $netsales = $totalgross - $totalNet;
        $data = [
            'title' => "Report for $branch->name branch",
            'date' => " $monthName - $year",
            'content' => 'This is a sample PDF generated using Laravel and dompdf.',
            'payments' => $payments,
            'totalCommission' => $totalCommission,
            'totalgross' => $totalgross,
            'totalexpense'  => $totalexpense,
            'expenses' => $expense,
            'commissions' => $commissions,
            'totalNet' => $totalNet,
            'netsales' => $netsales,
        ];

        // Load the view and pass in the data
        $pdf = Pdf::loadView('generated.reports.branch', $data);

        // Return PDF as download
        return $pdf->download('example.pdf');
    }

    public function staff_report($staff_id, Request $req)
    {
        $startDate = Carbon::parse($req->input('start_date'))->format('Y-n-j');
        $endDate = Carbon::parse($req->input('end_date'))->format('Y-n-j');
        $comq = CommissionHistory::where('stylist_id', $staff_id);
        $staff = User::with('role')->find($staff_id);
        if ($startDate) {
            $comq->whereDate('created_at', '>=', $startDate);
        }
    
        if ($endDate) {
            $comq->whereDate('created_at', '<=', $endDate);
        }
    
        $commissions = $comq->orderBy('id','desc')->get();
        $data = [];
        $totcom = 0;
        $totgross = 0;
        foreach ($commissions as $com) {
            $comamount = $com->commission_amount;
            $gross_sale = $com->gross_sale;
            $totgross += $gross_sale; 
            $totcom += $comamount;
            if ($com->appointment_package_redeem) {
                $cashier = $com->appointment_package_redeem->cashier->first_name . ' ' . $com->appointment_package_redeem->cashier->last_name;
            } elseif ($com->appointment_combo_redeem) {
                $cashier = $com->appointment_combo_redeem->cashier->first_name . ' ' . $com->appointment_combo_redeem->cashier->last_name;
            } elseif ($com->appointment_service_redeem) {
                $cashier = $com->appointment_service_redeem->cashier->first_name . ' ' . $com->appointment_service_redeem->cashier->last_name;
            } else {
                $cashier = 'N/A';
            }
            $data[] = [
                'commission_amount' => $com->commission_amount,
                'client' => $com->client->first_name . ' ' . $com->client->last_name,
                'package' => $com->appointment_package_redeem?->appointment_package?->package?->name ?? 'N/A',
                'combo' => $com->appointment_combo_redeem?->appointment_combo?->combo?->name ?? 'N/A',
                'services' => $com->appointment_service_redeem?->appointment_service?->service?->name ?? 'N/A',
                'cashier' => $cashier,
                'gross_sale' => $com->gross_sale,
                'created_at' => Carbon::parse($com->created_at)->format('F d, Y h:i A'),
            ];
        }
        $res = [
            'staff' => $staff,
            'data' => $data,
            'date_start' => $req->input('start_date'),
            'date_end' => $req->input('end_date'),
            'total_commission' => number_format($totcom, 2),
            'total_gross' => number_format($totgross, 2)
        ];

        // Load the view and pass in the data
        $pdf = Pdf::loadView('generated.reports.staff', $res);

        // Return PDF as download
        return $pdf->download('example.pdf');
    }
}
