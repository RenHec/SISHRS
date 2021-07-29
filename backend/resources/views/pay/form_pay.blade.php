<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('img/ico.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pago - {{ $comercio_name }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/iofrm-style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/iofrm-theme14.css') }}">
</head>
<body>
<div class="form-body">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <div class="website-logo-inside" style="text-align: center;">
                        <a href="/#">
                            <div class="logo">
                                <img class="" src="https://app.centraldepago.com/images/logo.svg" alt="centraldepago">
                            </div>
                        </a>
                    </div>
                    <label for="total"><strong>Total a Pagar:</strong></label>
                    <input name="hash" id="hash" type="hidden" class="form-control sm-content" value="{{ $hash }}" style="display: inline;margin-left: 15px;" disabled>
                    <input name="total" type="text" class="form-control sm-content" value="{{ $totalNormal }}" style="display: inline;margin-left: 15px;" disabled>
                    <form id="frmPago" name="frmHtmlCheckout" method="POST" action="https://marlin.firstatlanticcommerce.com/SENTRY/PaymentGateway/Application/DirectAuthLink.aspx">
                        <div class="tab-content" id="stepsTabContent">
                            <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                                <div class="inline-el-holder">
                                    <div class="inline-el">
                                        <label>No. Reservación: #{{$no_orden}}</label>
                                    </div>
                                    <div class="inline-el">
                                        <label>Página Web:  {{ $comercio_name }}</label>
                                    </div>
                                    <div class="">
                                        <label for="full_name">Reservado por:</label>
                                        <input name="full_name" type="text" class="form-control " value="{{ $first_name }} {{ $last_name }}" style="display: inline;" disabled>
                                    </div>
                                </div>
                                <div>
                                    <INPUT TYPE="hidden" NAME="Version" ID="Version" VALUE="1.0.0">
                                    <INPUT TYPE="hidden" NAME="AcqID" ID="AcqID" VALUE="{{ $acquiredId }}">
                                    <INPUT TYPE="hidden" NAME="MerID" ID="MerID" VALUE="{{ $facId }}">
                                    <INPUT TYPE="hidden" NAME="PurchaseAmt" ID="PurchaseAmt" VALUE="{{ $total }}">
                                    <INPUT TYPE="hidden" NAME="PurchaseCurrency" ID="PurchaseCurrency" VALUE="320">
                                    <INPUT TYPE="hidden" NAME="PurchaseCurrencyExponent" ID="PurchaseCurrencyExponent" VALUE="2">
                                    <INPUT TYPE="hidden" NAME="OrderID" ID="OrderID" VALUE="{{$no_orden}}">
                                    <INPUT TYPE="hidden" NAME="CustomerIP" ID="CustomerIP" VALUE="{{ $ip }}">
                                    <INPUT TYPE="hidden" NAME="SignatureMethod" ID="SignatureMethod" VALUE="SHA1">
                                    <INPUT TYPE="hidden" NAME="Signature" ID="Signature" VALUE="{{ $signature }}">
                                    <INPUT TYPE="hidden" NAME="CaptureFlag" ID="CaptureFlag" VALUE="A">
                                    <INPUT TYPE="hidden" NAME="MerRespURL" ID="MerRespURL" VALUE="{{ $merRespURL }}">
                                    <INPUT TYPE="hidden" NAME="CustomData" ID="CustomData" VALUE="">
                                    <INPUT TYPE="hidden" NAME="InterfaceCode" ID="InterfaceCode" VALUE="FACPG2.WCF">
                                    <INPUT TYPE="hidden" NAME="BillToAddress1" ID="BillToAddress1" VALUE="{{ $address_l1 }}">
                                    <INPUT TYPE="hidden" NAME="BillToAddress2" ID="BillToAddress2" VALUE="{{ $address_l2 }}">
                                    <INPUT TYPE="hidden" NAME="BillToPostCode" ID="BillToPostCode" VALUE="{{ $codigo_postal }}">
                                    <INPUT TYPE="hidden" NAME="BillToFirstName" ID="BillToFirstName" VALUE="{{ $first_name }}">
                                    <INPUT TYPE="hidden" NAME="BillToLastName" ID="BillToLastName" VALUE="{{ $last_name }}">
                                    <INPUT TYPE="hidden" NAME="BillToCity" ID="BillToCity" VALUE="{{ $municipio }}">
                                    <INPUT TYPE="hidden" NAME="BillToState" ID="BillToState" VALUE="GTC">
                                    <INPUT TYPE="hidden" NAME="BillToCountry" ID="BillToCountry" VALUE="320">
                                    <INPUT TYPE="hidden" NAME="BillToEmail" ID="BillToEmail" VALUE="{{ $email }}">
                                    <INPUT TYPE="hidden" NAME="BillToTelephone" ID="BillToTelephone" VALUE="{{ $phone }}">
                                </div>
                                <div class="separator"></div>
                                @if(!$status)
                                    <div class="form-sent show-it" style="display: contents">
                                        @if($display != "none")
                                            <div id="error">
                                                <div class="tick-holder">
                                                    <img src="https://app.centraldepago.com/images/error.png" style="width: 20px">
                                                </div>
                                                <h3>Error al procesar pago</h3>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-sent show-it" style="display: contents">
                                        <div id="error-2" style="display:none">
                                            <div class="tick-holder">
                                                <img src="https://app.centraldepago.com/images/error.png" style="width: 20px">
                                            </div>
                                            <h3>Error al procesar pago</h3>
                                        </div>
                                    </div>
                                    <div id="pago">
                                        <label>Número de Tarjeta</label>
                                        <img src="https://app.centraldepago.com/images/credit-card-logos.png" width="220px" "="" style="width: 180px;top: 10px;">
                                        <div class="input-with-ccicon" style="margin-top: 10px;">
                                            <input name="CardNo" id="cc" type="number" class="form-control input-credit-card" placeholder="4321 1234 4321 1234" required="required">
                                            <i id="ccicon"></i>
                                        </div>
                                        <div class="inline-el-holder">
                                            <div class="inline-el">
                                                <label>Fecha de expiración</label>
                                                <select id="t1" required="required" class="form-control sm-content">
                                                    <option value="">Mes</option>
                                                    <option value="01">01</option>
                                                    <option value="02">02</option>
                                                    <option value="03">03</option>
                                                    <option value="04">04</option>
                                                    <option value="05">05</option>
                                                    <option value="06">06</option>
                                                    <option value="07">07</option>
                                                    <option value="08">08</option>
                                                    <option value="09">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select>
                                            </div>
                                            <div class="inline-el">
                                                <select id="t2" required="required" class="form-control sm-content">
                                                    <option value="">Año</option>
                                                    <option value="20">2020</option><option value="21">2021</option><option value="22">2022</option><option value="23">2023</option><option value="24">2024</option><option value="25">2025</option><option value="26">2026</option><option value="27">2027</option><option value="28">2028</option><option value="29">2029</option><option value="30">2030</option>
                                                </select>
                                            </div>
                                                <INPUT NAME="CardExpDate" type="hidden" ID="CardExpDate" class="form-control sm-content input-credit-card" placeholder="MM/YY" VALUE="">
                                            <div class="inline-el">
                                                <label>CVV</label>
                                                <INPUT NAME="CardCVV2" class="form-control sm-content input-credit-card"   ID="CardCVV2" VALUE="" placeholder="123" required="required">
                                            </div>
                                        </div>
                                        <div class="form-button">
                                            <button id="pagar" class="ibtn">PAGAR</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-sent show-it" style="display: contents">
                                        <div class="tick-holder">
                                            <div class="tick-icon"></div>
                                        </div>
                                        <h3>Pago ya Realizado o Cancelado</h3>
                                        <p>Por favor verifica con {{ $comercio_name }} </p>
                                    </div>
                                @endif
                                <div class="form-sent show-it" style="display: contents">
                                    <div  id="complete" style="display: none">
                                        <div class="tick-holder">
                                            <div class="tick-icon"></div>
                                        </div>
                                        <h3>Pago Realizado Éxito</h3>
                                    </div>
                                </div>
                            </div>
                            <br /><br />
                            <img src="https://app.centraldepago.com/images/fac-logo.png" style="width: 100px;display: block;margin-left: auto;margin-right: auto;"/>
                            <div class="separator"></div>
                            <div class="inline-el-holder" style="text-align: center;">
                                <div class="">
                                    <label style="font-size: 11px; display: block">Derechos Reservados @ 2020 Mentes Geniales S.A.</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/cleave.min.js') }}"></script>
<script src="{{ asset('js/main-bac.js') }}"></script>
</body>
</html>
