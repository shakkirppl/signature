<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postmortem Report</title>
    <style>
   
<style>
    body {
        font-family: Arial, sans-serif;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }
    .no-border td, .no-border th {
        border: none !important;
    }

  
     
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
         height: 20px;
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
<body>
    
<div class="print-button">
        <button onclick="window.print()">🖨️ Print</button>
        <button style=" font-size: 14px; cursor: pointer; text-align" class="back">
        <a href="{{ url('postmortem-report-index') }}" class="backicon" style="text-decoration: none; color: inherit;">
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
            <td class="text-center"><strong>IR 02</strong></td>
        </tr>
    </table>
<div class="container">
    <!-- <h2 style="text-align: center;">PostMortem Report</h2> -->
    <p><strong>Postmortem No:</strong> {{ $postmortem->postmortem_no }}</p>
    <p><strong>Inspection Date:</strong> {{ $postmortem->inspection_date }}</p>

    <h3>Postmortem Information (List reasons for hold or condemnations in Comments section):</h3>
    <table>
        <thead>
            <tr>
                <th>Animals Types Inspected</th>
                <th>Carcasses Approved</th>
                <th>Carcasses Held</th>
                <th>Carcasses Condemned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($postmortem->animals as $animal)
                <tr>
                    <td>{{ $animal->animal_type }}</td>
                    <td>{{ $animal->carcasses_approved }}</td>
                    <td>{{ $animal->carcasses_held }}</td>
                    <td>{{ $animal->carcasses_condemned }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Organ Inspection</h3>
    <table>
        <thead>
            <tr>
                <th>Organ Types Inspected</th>
                <th>Organs Approved</th>
                <th>Organs Held</th>
                <th>Organs Condemned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($postmortem->organs as $organ)
                <tr>
                    <td>{{ $organ->organ_type }}</td>
                    <td>{{ $organ->organs_approved }}</td>
                    <td>{{ $organ->organs_held }}</td>
                    <td>{{ $organ->organs_condemned }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Sample Submission</h3>
    <table>
        <thead>
            <tr>
                <th>Sample Identification Type</th>
                <th>Sample Location</th>
                <th>Hold Tag</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($postmortem->samples as $sample)
                <tr>
                    <td>{{ $sample->sample_identification_type }}</td>
                    <td>{{ $sample->sample_location }}</td>
                    <td>{{ $sample->hold_tag }}</td>
                    <td>{{ $sample->date_submitted }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Comments</h3>
    <table>
    <tbody>
    @foreach ($postmortem->comments as $comment)
        <tr>
            <td>{{ $comment->comment }}</td>
        </tr>
    @endforeach

    {{-- Add 3 empty rows after comments --}}
    @for ($i = 0; $i < 3; $i++)
        <tr>
            <td>&nbsp;</td> {{-- Non-breaking space so the cell is not completely empty --}}
        </tr>
    @endfor
</tbody>

    </table>
    <br><br>
    <table class="no-border">
        <tr>
            <td><strong>Inspector's Signature:</strong> ________________________</td>
            <td class="text-right"><strong>Date of Inspection:</strong> ________________________</td>
        </tr>
    </table>
    <script>
        window.print();
    </script>
</div>
</body>
</html>
