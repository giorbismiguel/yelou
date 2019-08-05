@extends('templates.pdf-invoice')

@section('page-title')
    Factura
@endsection

@section('header')
    <div style="padding: 30px 55px;">
        <table style="margin-bottom: 20px;">
            <tr>
                <td style="width: 500px;font-size: 24px; margin-bottom: 30px;">
                    <strong>UNIVERSITAS ECUADOR</strong>
                </td>

                <td style="width: 115px;">
                    <strong>Número de factura:</strong>
                </td>

                <td style="width: 255px; color:red;">
                    {{ $invoice->invoice_number }}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width: 500px;">
                    <strong>Dirección:</strong> Av. América N29-23 y Las Casas
                    <br/>
                    Edificio Andrade, 2 Piso Of: 6
                    <br/>
                    <strong>Telefonos:</strong> (02) 2901-784 <strong>* Celular:</strong> 0992 190 334
                    <br/>
                    universitas.ecuador <strong>*</strong> cursos@universitas.ec
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                </td>
                <td style="width: 300px;">
                    <span><strong>Dia:</strong></span> {{ $today }}
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('content')
    <div style="padding: 30px 55px;">
        <table style="margin-top: 30px;">
            <tr>
                <td style="width: 60px;">
                    <strong>NOMBRE:</strong>
                </td>
                <td style="width: 440px;border-bottom: 1px dotted;">
                    {{ $invoice->student->name . ' ' . $invoice->student->lastname }}
                </td>
                <td style="width: 60px;">
                    <strong>TELÉFONO:</strong>
                </td>
                <td style="width: 100px;border-bottom: 1px dotted;">
                    {{ $invoice->student->phone }}
                </td>
            </tr>
        </table>

        <table style="margin-top: 10px;">
            <tr>
                <td style="width: 60px;">
                    <strong>DIRECCIÓN:</strong>
                </td>
                <td style="width: 440px;border-bottom: 1px dotted;">
                    {{ $invoice->student->adress }}
                </td>
                <td style="width: 60px;">
                    <strong>CI O RUC:</strong>
                </td>
                <td style="width: 100px;border-bottom: 1px dotted;">
                    {{ $invoice->student->dni }}
                </td>
            </tr>
        </table>

        <table style="margin-top: 50px;" cellspacing="0" cellpadding="0">
            <thead style="background-color: lightslategray;">
            <tr style="background-color: #cccccc;">
                <th style="width: 350px; padding: 15px;">DESCRIPCIÓN</th>
                <th style="width: 100px;">CANTIDAD</th>
                <th style="width: 100px;">P. UNIT</th>
                <th style="width: 100px;">VALOR TOTAL</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="padding: 15px;">{{ $invoice->calendar->modalityCourse->school->name }}</td>
                <td>{{ $invoice->quantity }}</td>
                <td>{{ $invoice->value_total / $invoice->quantity }}</td>
                <td>{{ $invoice->value_total }}</td>
            </tr>
            </tbody>
        </table>

        <hr/>

        <table style="margin-top:10px;">
            <tr>
                <td style="width: 500px;">
                    <span style="font-size: 14px;"><strong>Forma de pago:</strong></span> {{ $invoice->method_payment }}
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
                <td style="vertical-align: top; width: 200px; text-align: center;">
                    <table style="width: 80%">
                        <tbody>
                        <tr>
                            <td style="width: 60%;"><strong>+ IVA 12%:</strong></td>
                            <td style="width: 40%;">$ {{ $invoice->iva ?? '0.00' }}</td>
                        </tr>
                        <tr>
                            <td><strong>SUB TOTAL:</strong></td>
                            <td>$ {{ $invoice->subtotal ?? '0.00' }}</td>
                        </tr>
                        <tr>
                            <td><strong>DESCUENTO:</strong></td>
                            <td>$ {{ $invoice->discount ?? '0.00' }}</td>
                        </tr>
                        <tr>
                            <td><strong>ABONO:</strong></td>
                            <td>$ {{ $invoice->advancement ?? '0.00' }}</td>
                        </tr>
                        </tbody>
                    </table>

                    <table style="width: 80%; margin-top: 20px;">
                        <tbody>
                        <tr>
                            <td style="width: 60%;"><strong>VALOR TOTAL:</strong></td>
                            <td style="width: 40%;">$ {{ $invoice->balance_to_pay }}</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <div style="padding: 100px 200px 0 100px;">
            <table>
                <tr>
                    <td style="width:250px;">
                        <div style="margin-right: 20px; border-bottom: 1px dotted;"></div>
                    </td>
                    <td style="width: 250px;">
                        <div style="margin-left: 20px; border-bottom: 1px dotted;"></div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; margin-right: 20px">RECIBÍ CONFORME</td>
                    <td style="text-align: center; margin-left: 20px">FIRMA AUTORIZADA</td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body {
            margin-top: 4.5cm;
            margin-bottom: 0.5cm;
        }

        header {
            height: 4.5cm;
        }

        footer {
            height: 0.5cm;
        }
    </style>
@endpush
