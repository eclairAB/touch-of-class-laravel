<!DOCTYPE html>
<html>
<head>
    {{-- <img src="{{ asset('images/logo.JPG') }}" alt="profile Pic" height="200" width="200"> --}}
    <title>{{ $title }}</title>
    <style>
        /* Styling for the table */
        td {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Alternating row colors */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:nth-child(odd) {
            background-color: #e9e9e9;
        }
        td.amount {
            text-align: right;
        }
        .summary-table td {
            border: none;
            padding: 5px 0;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Date: {{ $date }}</p>
    <br>
    <p>Payments</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Gross Sale</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('m/d/Y') }}</td>
                <td class="amount">₱{{ $payment->amount_paid }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <p>Expenses</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Cashier</th>
                <th>Expense Name</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $expense)
            <tr>
                <td>{{ $expense->created_at->format('m/d/Y') }}</td>
                <td>{{ $expense->cashier->first_name }} {{ $expense->cashier->last_name }}</td>
                <td>{{ $expense->expense_name }}</td>
                <td class="amount">₱{{ $expense->amount }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <p>Staff Salary</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Commission Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commissions as $commission)
            <tr>
                <td>{{ $commission->created_at->format('m/d/Y') }}</td>
                <td>{{ $commission->stylist->first_name }} {{ $commission->stylist->last_name }}</td>
                <td class="amount">₱{{ $commission->commission_amount }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table class="summary-table">
        <tbody>
            <tr>
                <td><b>Total Gross:</b></td>
                <td class="amount"><b>₱{{ number_format($totalgross,2) }}</b></td>
            </tr>
            <tr>
                <td><b>Total Expenses:</b></td>
                <td class="amount"><b>₱{{ number_format($totalNet,2) }}</b></td>
            </tr>
            <tr>
                <td><b>Net Sales:</b></td>
                <td class="amount"><b>₱{{ number_format($netsales,2) }}</b></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
