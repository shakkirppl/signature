<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slaughter Schedule Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .container {
            width: 210mm;
            background: white;
            padding: 20px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        hr {
            border: 1px solid black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .no-print {
            text-align: center;
            margin-top: 20px;
        }
        @media print {
            .no-print { display: none; }
            body { background: white; margin: 0; }
            .container { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Slaughter Schedule</h2>
    <hr>
    <table>
        <tr>
            <th>Slaughter No</th>
            <td>{{ $schedule->slaughter_no }}</td>
            <th>Date</th>
            <td>{{ $schedule->date }}</td>
        </tr>
        <tr>
            <th>Start Date</th>
            <td>{{ $schedule->slaughter_date }}</td>
            <th>End Date</th>
            <td>{{ $schedule->slaughter_end_date }}</td>
        </tr>
        <tr>
            <th>Starting Time</th>
            <td>{{ $schedule->starting_time_of_slaughter }}</td>
            <th>Ending Time</th>
            <td>{{ $schedule->ending_time_of_slaughter }}</td>
        </tr>
        <tr>
            <th>Loading Start Date</th>
            <td>{{ $schedule->loading_start_date }}</td>
            <th>Loading End Date</th>
            <td>{{ $schedule->loading_end_date }}</td>
        </tr>
        <tr>
            <th>Loading Start Time</th>
            <td>{{ $schedule->loading_time }}</td>
            <th>Loading End Time</th>
            <td>{{ $schedule->loading_end_time }}</td>
        </tr>
        <tr>
            <th>Transportation Date</th>
            <td>{{ $schedule->transportation_date }}</td>
            <th>Transportation Time</th>
            <td>{{ $schedule->transportation_time }}</td>
        </tr>
        <tr>
            <th>Airport Cutoff Time</th>
            <td>{{ $schedule->airport_time }}</td>
            <th>Airline Name</th>
            <td>{{ $schedule->airline_name }}</td>
        </tr>
        <tr>
            <th>Flight Number</th>
            <td>{{ $schedule->airline_number }}</td>
            <th>Airline Date</th>
            <td>{{ $schedule->airline_date }}</td>
        </tr>
        <tr>
            <th>Airline Time</th>
            <td colspan="3">{{ $schedule->airline_time }}</td>
        </tr>
    </table>

    <h3>Product Details</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedule->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->products->product_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="no-print">
    <button onclick="captureAndDownload()" class="btn btn-success">Download </button>
    <button onclick="window.print()" class="btn btn-primary">Print</button>
    <button style=" font-size: 14px; cursor: pointer;">
        <a href="{{ url('slaughter-schedules-index') }}" class="backicon" style="text-decoration: none; color: inherit;">
            Back <i class="mdi mdi-backburger"></i>
        </a>
    </button>
</div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    function captureAndDownload() {
        let container = document.querySelector(".container");
        let buttons = document.querySelector(".no-print"); // Select buttons to hide

        if (!container) {
            alert("Error: Unable to find the content to capture.");
            return;
        }

        // Hide buttons before capturing
        if (buttons) buttons.style.visibility = "hidden";

        setTimeout(() => {
            html2canvas(container, { scale: 2 }).then(canvas => {
                let image = canvas.toDataURL("image/png");

                // Show buttons again after capturing
                if (buttons) buttons.style.visibility = "visible";

                // Create a download link
                let downloadLink = document.createElement("a");
                downloadLink.href = image;
                downloadLink.download = "slaughter_schedule.png";
                downloadLink.click();
            }).catch(error => {
                console.error("Capture Error:", error);
                alert("Failed to capture the image. Try again.");
                if (buttons) buttons.style.visibility = "visible"; // Show buttons on error
            });
        }, 500); // Small delay to ensure buttons are hidden
    }
</script>




</body>
</html>
