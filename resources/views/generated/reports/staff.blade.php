<!DOCTYPE html>
<html>

<head>
<title>{{ "Report for $staff->first_name $staff->last_name" }}</title>
{{-- <img src="{{ asset('images/logo.JPG') }}" alt="profile Pic" height="200" width="200"> --}}
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
    
    th,
    td {
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
    <h1>{{ "Report for $staff->first_name $staff->last_name" }}</h1>
        <p>Date: {{ $date_start }} - {{ $date_end }}</p>
    
    <!-- User Table -->
    
    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Package</th>
                <th>Combo</th>
                <th>Service</th>
                <th>Cashier</th>
                <th>Date and Time</th>
                <th>Commission</th>
                <th>Gross Sale</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{$d['client']}}</td>
                <td>{{$d['package']}}</td>
                <td>{{$d['combo']}}</td>
                <td>{{$d['services']}}</td>
                <td>{{$d['cashier']}}</td>
                <td>{{$d['created_at']}}</td>
                <td>{{$d['commission_amount']}}</td>
                <td>{{$d['gross_sale']}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="7">Total Commission</td>
                <td>₱{{ $total_commission }}</td>
            </tr>
            <tr>
                <td colspan="7">Total Gross Sale</td>
                <td>₱{{ $total_gross }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
