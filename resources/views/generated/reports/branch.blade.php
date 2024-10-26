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
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Date: {{ $date }}</p>

    <!-- User Table -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Gross Sale</th>
                <th>Net Sale</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('m/d/Y') }}</td>
                <td>₱{{ $payment->amount_paid }}</td>
                <td>₱{{ $payment->amount_paid }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
