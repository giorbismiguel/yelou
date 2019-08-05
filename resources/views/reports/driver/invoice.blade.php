@extends('templates.pdf')

@section('page-title')
    Factura del Cliente
@endsection

@section('header')
    @include('partials.pdf.header')

    <div style="padding: 30px 55px;">
        <table>
            <tr>
                <td style="width: 70px;">
                    <strong>CLIENTE:</strong>
                </td>
                <td style="width: 400px;">
                    {{ $voucher->student->name }}
                </td>
                <td style="width: 100px;">
                    <strong>Documento #:</strong>
                </td>
                <td style="width: 100px; color:red;">
                    {{ $voucher->id }}
                </td>
            </tr>
            <tr>
                <td style="width: 70px;">
                    <strong>TELEFONO:</strong>
                </td>
                <td style="width: 400px;">
                    {{ $voucher->student->phone }}
                </td>
                <td style="width: 130px;">
                    <strong>Fecha :</strong>
                </td>
                <td style="width: 100px;">
                    {{ $today }}
                </td>
            </tr>
            <tr>
                <td style="width: 70px;">
                    <strong>DIRECCION:</strong>
                </td>
                <td style="width: 400px;">
                    {{ $voucher->student->adress }}
                </td>
            </tr>
            <tr>
                <td style="width: 70px;">
                    <strong>CORREO:</strong>
                </td>
                <td style="width: 400px;">
                    {{ $voucher->student->email }}
                </td>
            </tr>
        </table>
    </div>
@endsection

@section('content')
    <div style="padding: 30px 55px;">
        <table style="margin-top: 10px;">
            <tr>
                <td style="width: 150px;">
                    <strong>Forma de Pago:</strong>
                </td>
                <td style="width: 320px;">
                    {{ $voucher->method_payment }}
                </td>
                <td style="width: 130px; color: deepskyblue; font-size: 30px;">
                    TOTAL :
                </td>
                <td style="width: 100px; color:deepskyblue; font-size: 30px;">
                    $ {{ $voucher->value_total }}
                </td>
            </tr>
        </table>

        <table style="margin-top: 10px;" cellspacing="0" cellpadding="0">
            <thead style="background-color: lightslategray;">
            <tr style="background-color: #cccccc;">
                <th style="width: 50px;padding: 15px;">SL.</th>
                <th style="width: 400px;">Item Descripción</th>
                <th style="width: 70px;">Precio</th>
                <th style="width: 60px;">Qt.</th>
                <th style="width: 70px;">Total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="padding: 15px;">1</td>
                <td>
                    {{ $voucher->calendar->modalityCourse->school->name }}
                    <br/>
                    <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($voucher->calendar->date_start)->format('d/m/Y') }}
                    <br/>
                    <strong>Horario:</strong> {{ $voucher->calendar->schelude_in . ' - ' . $voucher->calendar->schelude_out  }}
                </td>
                <td>$ {{ $voucher->calendar->modalityCourse->price }}</td>
                <td>{{ $voucher->quantity }}</td>
                <td>{{ $voucher->balance_to_pay }}</td>
            </tr>
            </tbody>
        </table>

        <hr/>

        <table style="margin-top:10px;">
            <tr>
                <td style="width: 400px;">
                    <span style="font-size: 14px;"><strong>Universitas</strong></span>
                    <br/>
                    <strong>RUC:</strong> <span style="color:red;">1003712880001</span>
                    <br/>
                    <strong>Dirección:</strong> Av. América N29-23 y Las Casas
                    <br/>
                    <strong>Telefonos:</strong> (593)2901784 / 0992190334
                    <br/>
                    <strong>Skype:</strong> universitas.ecuador
                    <br>
                    <br>
                    <strong>Términos y Condiciones</strong>
                    <span>
                        <br>
                        Al firmar el presente documento usted acepta todos
                        <br/>
                        los terminos y condiciones impuestas por universitas
                        <br/>
                        en universitas.ec/terminos
                    </span>
                </td>
                <td style="vertical-align: top; width: 250px; text-align: center;">
                    <table style="width: 90%">
                        <tbody>
                        <tr>
                            <td style="text-align: left; margin-left: 20px;"><strong>Sub Total</strong>:</td>
                            <td>$ {{ $voucher->subtotal }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; margin-left: 20px;"><strong>IVA:</strong></td>
                            <td>$ {{ $voucher->iva ?? '0.00' }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; margin-left: 20px;"><strong>Descuento:</strong></td>
                            <td>$ {{ $voucher->discount ?? '0.00' }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; margin-left: 20px;"><strong>Abono:</strong></td>
                            <td>$ {{ $voucher->advancement ?? '0.00' }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <hr/>
                    <table class="full-width">
                        <tbody>
                        <tr>
                            <td><strong>Saldo</strong>:</td>
                            <td>$ {{ $voucher->subtotal }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <p style="color: red; margin-top: 10px;">Compromiso de Pago: 30/06/2019</p>
                </td>
            </tr>
        </table>

        <div style="padding: 50px 200px 0 200px;">
            <table>
                <tr>
                    <td style="width: 150px;">
                        <div style="margin-right: 20px;">
                            <hr/>
                        </div>
                    </td>
                    <td style="width: 150px;">
                        <div style="margin-left: 20px;">
                            <hr/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; margin-right: 20px">Cliente</td>
                    <td style="text-align: center; margin-left: 20px">Emisor</td>
                </tr>
            </table>
        </div>
    </div>

    <table class="full-width" cellpadding="0" cellspacing="0">
        <tr>
            <td class="w-100" style="height: 6px; background: deepskyblue;" colspan="2"></td>
        </tr>

        <tr>
            <td style="height: 20px; background-color:black; width: 40%; color: white;">
                <span style="margin-left: 50px;">www.universitas.ec</span>
            </td>

            <td style="height: 20px; background-color:black;">
                <div style="margin-right: 20px;">
                </div>
            </td>
        </tr>
    </table>
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
