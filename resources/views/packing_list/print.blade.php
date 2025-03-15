<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .a4-container {
    width: 230mm; /* Increased width */
    height: 297mm;
    margin: auto;
    padding: 20px;  /* Increased padding for better spacing */
    box-sizing: border-box;
    border: 1px solid black;
    background-color: white;
}

.packing-list {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid black;
    margin: 10px auto;
}
        .packing-list td, .packing-list th {
            border: 1px solid black;
            padding: 20px; /* Increased padding */
        }

        .header {
            background-color: #f8f2cc;
            font-size: 26px; /* Slightly larger font */
            font-weight: bold;
            text-align: center;
        }

        .highlight {
            font-weight: bold;
        }

        .invoice {
            color: red;
            font-weight: bold;
        }

        .contact {
            font-size: 14px;
        }

        .invoice-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .row-invoice {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
        }

        .row strong {
            min-width: 100px;
        }

        .invoice-date {
            margin-left: 50px; /* Add spacing between Invoice and Invoice Date */
        }
    </style>
</head>
<body>
    <div class="a4-container">
        
        <table class="packing-list">
            <tr>
                <td class="header" colspan="2">PACKING LIST</td>
                <td>
                    <div class="invoice-container">
                        <div class="row-invoice">
                            <strong>Invoice:</strong> <span class="invoice">10055</span> 
                            <strong class="invoice-date">Invoice Date:</strong> 07/03/2025
                        </div>
                        <div class="row">
                            <strong>Customer Id:</strong> 15799
                        </div>
                        <div class="row">
                            <strong>Page:</strong> 1.00
                        </div>
                    </div>
                </td>
            </tr>
           
            <tr>
                <td colspan="2">
                    <strong>SIGNATURE TRADING LIMITED</strong><br>
                    P.O Box 1506, Plot no.22, Block 7, Condo building<br>
                    Arusha, Tanzania<br>
                    <span class="contact">Tel. +255 272 97 97 97 / 69 666 6606</span><br>
                    Email: <strong>sales@signaturetz.com</strong>
                </td>
                <td colspan="2">
                    <strong>TAYBAT ALBAHR</strong><br>
                    UMM AL DOOM STREET<br>
                    MUAITHER<br>
                    P.O BOX: 96393, DOHA, QATAR<br>
                    <span class="contact">Tel. +974 31075459</span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
