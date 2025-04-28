<!DOCTYPE html>
<html>
<head>
    <title>AnteMortem Report</title>
    <style>
     
    @media print {
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        .print-button {
            display: none;
        }
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 14px; /* Increased from 12px */
        margin: 0 auto;
        width: 210mm;
        background: white;
        padding: 20px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 15px;
    }

    th, td {
        border: 1px solid #000;
        padding: 10px; /* Increased padding for more height */
        text-align: left;
        font-size: 14px; /* Increased font size */
    }

    .no-border td, .no-border th {
        border: none !important;
    }

    .print-button {
        text-align: right;
        margin-bottom: 10px;
    }

    .logo {
        height: 60px;
    }

    h3, h4, p {
        margin: 5px 0;
        font-size: 16px; /* Slightly larger for headings */
    }

    .header-table {
        width: 100%;
        margin-bottom: 20px;
    }

    .text-center {
        text-align: center;
    }
</style>

   
</head>
<body>

    <div class="print-button">
        <button onclick="window.print()">🖨️ Print</button>
        <button style=" font-size: 14px; cursor: pointer; text-align" class="back">
        <a href="{{ url('antemortem-report-index') }}" class="backicon" style="text-decoration: none; color: inherit;">
            Back <i class="mdi mdi-backburger"></i>
        </a>
    </button>
    </div>

    <table class="header-table no-border">
        <tr>
            <td><img src="{{ asset('public/image/signature-logo.png') }}" class="logo"></td>
            <td class="text-center">
                <h3>SIGNATURE TRADING LIMITED</h3>
                <p>SIGNATURE TRADING ABATTOIR<br>Makuyuni, Monduli Arusha - Tanzania</p>
            </td>
            <td class="text-center"><strong>IR 01</strong></td>
        </tr>
    </table>

    <h4>Ante-mortem Information (List reasons for hold or condemnations in Comments section):</h4>
    <table>
        <thead>
            <tr>
                <th>Animals Types Inspected</th>
                <th>Quantity Pass</th>
                <th>Quantity Held</th>
                <th>Qty. Condemned</th>
                <th>Vet Contacted</th>
                <th>Manager Contacted</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report->animal as $animal)
                <tr>
                    <td>{{ $animal->animal_type }}</td>
                    <td>{{ $animal->quantity_pass }}</td>
                    <td>{{ $animal->quantity_held }}</td>
                    <td>{{ $animal->quantity_condemned }}</td>
                    <td>{{ $animal->vet_contacted }}</td>
                    <td>{{ $animal->manager_contacted }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>General Conditions:</h4>
    <table>
        <thead>
            <tr>
                <th>Condition</th>
                <th>Suspect</th>
                <th>Not Suspect</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($generalConditions as $condition)
                <tr>
                    <td>{{ $condition->condition_type }}</td>
                    <td>{{ $condition->suspect }}</td>
                    <td>{{ $condition->not_suspect }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Sample Submissions:</h4>
    <table>
        <thead>
            <tr>
                <th>Sample Identification</th>
                <th>Sample Location</th>
                <th>Hold Tag</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report->sampleType as $sample)
                <tr>
                    <td>{{ $sample->sample_identification_type }}</td>
                    <td>{{ $sample->sample_location }}</td>
                    <td>{{ $sample->hold_tag }}</td>
                    <td>{{ $sample->date_submitted }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Comments:</h4>
    <table>
        <tbody>
            @foreach ($report->comment as $com)
                <tr>
                    <td>{{ $com->comment_text }}</td>
                    
                </tr>
                
            @endforeach
        </tbody>
    </table>

    <br><br>
    <table class="no-border">
        <tr>
            <td><strong>Inspector's Signature:</strong> ________________________</td>
            <td class="text-right"><strong>Date of Inspection:</strong> ________________________</td>
        </tr>
    </table>

</body>
</html>
