<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>REPORTE DE VALE</title>
    <link rel="icon" type="image/png" sizes="18x18" href="">
    <style>
        *{
            font-family: sans-serif; !important;
        }
        @page {
            margin: 0cm 0cm;
            font-family: sans-serif;
        }

        .main-table {
            width: 100%;
            font-size: 15px;
            border-collapse: collapse;
            border: 1px solid black;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .main-table td:nth-child(2) {
            text-align: left;
        }
        .main-table thead th {
            background:rgb(208, 208, 235);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            padding: 0;
            margin: 0;
        }

        body {
            margin-top: 6cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 2.6cm;
        }

        header {
            position: fixed;
            font-weight: 800;
            height: 170px;
            top: 1cm;
            left: 1cm;
            right:1cm;
        }
        footer {
            position: fixed;
            bottom: 1cm;
            left: 1cm;
            right: 1cm;
        }
    </style>
</head>
<body>
    <header>
        <table style="width: 100%;">
            <tr>
                <td style="width:22%;text-align: left;">
                    <h2 style="font-weight: bold;">VICENTE MONCHO</h2>
                    <h3 style="text-align: center;">CONSTRUCCIONES</h3>
                    <h3 style="text-align: center;">S.R.L.</h3>
                </td>
                <td align="right">
                    <h1 style="font-size:45px;">VALE <span style="font-weight:normal; font-size:35px;">N° {{ $valet->id }} </span></h1>
                </td>
            </tr>
        </table>
        <table style="margin-top: 1cm;">
            <tr>
                <td>
                    <h4 style="padding: 7px 0;">A FAVOR DE: {{ $valet->nombre }}</h4>
                </td>
            </tr>
            <tr>
                <td>
                    <h4>CON CARGO A: {{ $valet->proveedor->nombre }}</h4>
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table width="100%">
            <tr style="vertical-align:bottom">
                <td style="width: 70%; text-align: left; vertical-align:bottom ">
                    FECHA: {{ date('d/m/Y') }}
                </td>
                <td style="width: 30%; vertical-align:bottom ">
                    AUTORIZÓ:
                </td>
            </tr>
        </table>
    </footer>
    <main>
        @for ($i = 0; $i < 3; $i++)
        <table class="main-table align-top" style="width: 100%;">
            <thead>
                <tr>
                    <th style="padding: 10px 0;">CANTIDAD</th>
                    <th style="padding: 10px 0;">DETALLE</th>
                    <th style="padding: 10px 0;">IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($valet->items as $item)
                <tr>
                    <td width="15%" align="center">{{ $item->cantidad }}</td>
                    <td width="70%">{{ $item->descripcion }}</td>
                    <td width="15%" align="center">{{ $item->importe }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if ($i < 2)
        <div style="page-break-after:always;"></div>
        @endif
        @endfor
    </main>
</body>

</html>
