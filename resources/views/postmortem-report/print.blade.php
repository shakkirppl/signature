
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
</style>

<div class="container">
    <h2 style="text-align: center;">PostMortem Report</h2>
    <p><strong>Postmortem No:</strong> {{ $postmortem->postmortem_no }}</p>
    <p><strong>Inspection Date:</strong> {{ $postmortem->inspection_date }}</p>

    <h3>Postmortem Information</h3>
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
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</div>

