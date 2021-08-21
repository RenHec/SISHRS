<?php

namespace App\Http\Controllers\V1\Principal\Reservation;

use SoapClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\V1\Principal\AdvancePrice;
use App\Models\V1\Principal\Baneo;
use App\Models\V1\Principal\Contract;

class BacPaymentController extends Controller
{
    private $wsdlurl;
    private $location;
    private $options;
    private $password;
    private $facId;
    private $acquirerId;
    private $currency;

    public function __construct()
    {
        $this->wsdlurl = "https://marlin.firstatlanticcommerce.com/PGService/Services.svc?wsdl";
        $this->location = "https://marlin.firstatlanticcommerce.com/PGService/Services.svc";
        $this->options = [
            'location' => $this->location,
            'soap_version' => SOAP_1_1,
            'exceptions' => 0,
            'trace' => 1,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];
        $this->password = "37CBbjM6";
        $this->facId = "33302098";
        $this->acquirerId = "464748";
        $this->currency = "320";
    }

    private function Sign($passwd, $facId, $acquirerId, $orderNumber, $amount, $currency)
    {
        $stringtohash = $passwd . $facId . $acquirerId . $orderNumber . $amount . $currency;
        $hash = sha1($stringtohash, true);
        return base64_encode($hash);
    }

    public function amexPay(Request $request)
    {
        try {
            $contract = Contract::where('url', $request->hash)->where('answer', Contract::ACEPTO)->first();
            $advance = AdvancePrice::where('contract_id', $contract->id)->first();

            $client = new SoapClient($this->wsdlurl, $this->options);

            $signature = $this->Sign($this->password, $this->facId, $this->acquirerId, $contract->reservation->id, str_pad('' . ($advance->amount * 100), 12, "0", STR_PAD_LEFT), $this->currency);

            $CardDetails = array(
                'CardCVV2' => $request->cvv,
                'CardExpiryDate' => $request->t1,
                'CardNumber' => $request->cc,
                'IssueNumber' => '',
                'StartDate' => ''
            );

            $TransactionDetails = array(
                'AcquirerId' => $this->acquirerId,
                'Amount' => str_pad('' . ($advance->amount * 100), 12, "0", STR_PAD_LEFT),
                'Currency' => $this->currency,
                'CurrencyExponent' => 2,
                'IPAddress' => '',
                'MerchantId' => $this->facId,
                'OrderNumber' => $contract->reservation->id,
                'Signature' => $signature,
                'SignatureMethod' => 'SHA1',
                'TransactionCode' => '8'
            );

            $AuthorizeRequest = array('Request' => array('CardDetails' => $CardDetails, 'TransactionDetails' => $TransactionDetails));

            $result = $client->Authorize($AuthorizeRequest);

            if (isset($client->fault)) {
                $msg = isset($result->AuthorizeResult->CreditCardTransactionResults) ? $result->AuthorizeResult->CreditCardTransactionResults->ReasonCodeDescription : "Error al procesar la transacción";
                $this->registrarProceso($contract->id, $msg, 'fault', false);
                return ["responseCode" => 510, "msg" => $msg];
            } else {
                if (isset($client->error)) {
                    $msg = isset($result->AuthorizeResult->CreditCardTransactionResults) ? $result->AuthorizeResult->CreditCardTransactionResults->ReasonCodeDescription : "Error desconocido al procesar la transacción";
                    $this->registrarProceso($contract->id, $msg, 'error', false);
                    return ["responseCode" => 510, "msg" => $msg];
                } else {
                    if ($result->AuthorizeResult->CreditCardTransactionResults->ResponseCode == 3) {
                        $msg = isset($result->AuthorizeResult->CreditCardTransactionResults) ? $result->AuthorizeResult->CreditCardTransactionResults->ReasonCodeDescription : "Error al procesar tarjeta";
                        $this->registrarProceso($contract->id, $msg, $result->AuthorizeResult->CreditCardTransactionResults->ResponseCode, false);
                        return ["responseCode" => 510, "msg" => $msg];
                    } else if ($result->AuthorizeResult->CreditCardTransactionResults->ResponseCode == 2) {
                        $msg = isset($result->AuthorizeResult->CreditCardTransactionResults) ? $result->AuthorizeResult->CreditCardTransactionResults->ReasonCodeDescription : "Tarjeta denegada";
                        $this->registrarProceso($contract->id, $msg, $result->AuthorizeResult->CreditCardTransactionResults->ResponseCode, false);
                        return ["responseCode" => 510, "msg" => $msg];
                    } else {
                        $this->registrarProceso($contract->id, $result->AuthorizeResult->CreditCardTransactionResults, $result->AuthorizeResult->CreditCardTransactionResults->ResponseCode, true);
                        return ["responseCode" => 200];
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function registrarProceso($contract, $authorization_link, $status, $pay)
    {
        $advance = AdvancePrice::where('contract_id', $contract)->first();
        $advance->authorization_link = $authorization_link;
        $advance->status = $status;
        $advance->pay = $pay;
        $advance->save();
    }

    public function getPaymentScreen($paymentData)
    {
        try {
            $contract = Contract::where('url', $paymentData)->where('answer', Contract::ACEPTO)->first();
            $advance = AdvancePrice::where('contract_id', $contract->id)->first();

            $amountFormatted = number_format($advance->amount, 2, '.', ',');
            return view("pay.form_pay", [
                "hash" => $paymentData,
                "totalNormal" => "Q {$amountFormatted}",
                "comercio_name" => 'Eco Le Suisse',
                "no_orden" => $contract->reservation->id,
                "first_name" => $contract->reservation->client->name,
                "last_name" => $contract->reservation->client->name,
                "address_l1" => mb_strtolower("{$contract->reservation->client->municipality->getFullNameAttribute()}, {$contract->reservation->client->ubication}"),
                "address_l2" => '',
                "municipio" => mb_strtolower($contract->reservation->client->municipality->name),
                "departamento" => mb_strtolower($contract->reservation->client->departament->name),
                "codigo_postal" => '0101',
                "email" => $contract->reservation->client->email,
                "phone" => '',
                "total" => str_pad('' . ($advance->amount * 100), 12, "0", STR_PAD_LEFT),
                "ip" => $this->get_the_user_ip(),
                "statusPago " => 1,
                "display" => "none",
                "status" => $advance->pay,
                "facId" => $this->facId,
                "acquiredId" => $this->acquirerId,
                "signature" => $this->Sign($this->password, $this->facId, $this->acquirerId, $contract->reservation->id, str_pad('' . ($advance->amount * 100), 12, "0", STR_PAD_LEFT), $this->currency),
                "merRespURL" => route("bac_payment.completeData", $paymentData) //"https://app.centraldepago.com/f/{{$hash}}/completado"
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCompletedScreen(Request $request)
    {
        $token = $request->hash; //token_contract
        $contract = Contract::where('url', $token)->where('answer', Contract::ACEPTO)->first();
        $advance = AdvancePrice::where('contract_id', $contract->id)->first();

        $f = [];

        /*if ($request->ReasonCode == 3) {
            return ["responseCode" => 515, "msg" => isset($request->ReasonCodeDesc) ? $request->ReasonCodeDesc : "No hay mensaje de error"];
        }*/
        if ($request->ReasonCode == 1) {
            $f["ResponseCode"] = $request->ReasonCode;
            $f["ReasonCodeDescription"] = $request->ReasonCodeDesc;
            return ["responseCode" => 200];
        } else {
            $this->ban_ip($this->get_the_user_ip(), $advance->link, $contract->reservation_id);

            $f["ResponseCode"] = $request->ReasonCode;
            $f["ReasonCodeDescription"] = $request->ReasonCodeDesc;
            $amountFormatted = number_format($advance->amount, 2, '.', ',');

            return view("pay.form_pay", [
                "hash" => $request->hash,
                "totalNormal" => "Q {$amountFormatted}",
                "comercio_name" => 'Eco Le Suisse',
                "no_orden" => $contract->reservation->id,
                "first_name" => $contract->reservation->client->name,
                "last_name" => $contract->reservation->client->name,
                "address_l1" => mb_strtolower("{$contract->reservation->client->municipality->getFullNameAttribute()}, {$contract->reservation->client->ubication}"),
                "address_l2" => '',
                "municipio" => mb_strtolower($contract->reservation->client->municipality->name),
                "departamento" => mb_strtolower($contract->reservation->client->departament->name),
                "codigo_postal" => '0101',
                "email" => $contract->reservation->client->email,
                "phone" => '',
                "total" => str_pad('' . ($advance->amount * 100), 12, "0", STR_PAD_LEFT),
                "ip" => $this->get_the_user_ip(),
                "statusPago " => 2,
                "display" => "yes",
                "status" => $advance->pay,
                "facId" => $this->facId,
                "acquiredId" => $this->acquirerId,
                "signature" => $this->Sign($this->password, $this->facId, $this->acquirerId, $contract->reservation->id, str_pad('' . ($advance->amount * 100), 12, "0", STR_PAD_LEFT), $this->currency),
                "merRespURL" => route("bac_payment.completeData", $request->hash) //"https://app.centraldepago.com/f/{{$hash}}/completado"
            ]);
        }
    }

    private function ban_ip($ip_array, $link, $reservation_id)
    {
        $ip_array = explode(",", str_replace(" ", "", $ip_array));
        foreach ($ip_array as $value) {
            $banned_ips = Baneo::where("ip", $value)->first();
            if (is_null($banned_ips)) {
                $banned_ips = new Baneo();
                $banned_ips->level = 1;
                $banned_ips->site_url = $link;
                $banned_ips->reservation_id = $reservation_id;
                $banned_ips->ip = $value;
            } else {
                $banned_ips->level += 1;
            }

            $banned_ips->save();
        }
    }

    private function get_the_user_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
