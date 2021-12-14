<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DonationController extends Controller
{
    public function index()
    {
        return view('donate.index');
    }

    public function bank(Donation $donation)
    {
        $url = config('services.toyyibpay.url-dev').'getBankFPX';
        $response = Http::get($url);
        $banks = $response->json();

        return view('donate.bank', compact('banks', 'donation'));
    }

    public function runBill($bank, Donation $donation)
    {
        $billCode = $donation->toyyibPay_bill_code;

        $url = config('services.toyyibpay.url-dev-1').'runBill';
        $key = config('services.toyyibpay.secret');

        $response = Http::asForm()->post($url, [
            'userSecretKey' => $key,
            'billCode' => $billCode,
            'billpaymentAmount' => '2.00',
            'billpaymentPayorName' => 'Sumayyah',
            'billpaymentPayorPhone'=>'60197789876',
            'billpaymentPayorEmail'=>'sumayyah@gmail.com',
            'billBankID'=>$bank
        ]);

        return $response;
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|min:1',
        ]);

        // multiply input amount with 100
        $amount = $request->amount * 100;

        $donation = Donation::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount' => $amount,
            'note' => $request->note,
        ]);

        $url = config('services.toyyibpay.url-dev').'createBill';
        $key = config('services.toyyibpay.secret');

        $response = Http::asForm()->post($url, [
            'userSecretKey' => $key,
            'categoryCode' => '0ggnfljz',
            'billName' => auth()->user()->name ?? $request->name,
            'billDescription' => 'Donation from '.$request->name,
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => $amount,
            // 'billReturnUrl' => $host.'/return-url',
            'billReturnUrl' => route('donate:return-url'),
            // 'billCallbackUrl' => $host.'/call-back-url',
            'billCallbackUrl' => route('donate:return-url'),
            'billExternalReferenceNo' => $donation->uuid.$donation->id,
            'billTo' => auth()->user()->name ?? $request->name,
            'billEmail' => auth()->user()->email ?? $request->email,
            'billPhone' => $request->phone,
            'billContentEmail'=>'Thank you for donating!',
        ])->json();
        
        $billCode = $response[0]['BillCode'];

        $donation->update([
            'toyyibPay_bill_code' => $billCode,
        ]);

        return redirect()->route('donate:bank', $donation);
    }

    public function returnUrl(Request $request)
    {
        $donate = Donation::where('toyyibpay_bill_code', $request->billcode)->first();
        $donater = $request->name;

        if (empty($donate)) {
            return 'Please check your response';
        }

        if($donate->uuid.$donate->id != $request->order_id) {
            return 'response not valid';
        }

        if($request->status_id !== '1')
        {
            $message = 'Fail';
            return view('donate.receipt', compact('donater', 'message'));
        }

        $message = 'Success';

        return view('donate.receipt', compact('donater', 'message'));
    }

    public function callbackUrl()
    {
        \info(['from payment gateway' => $request->all()]);

        $donate = Donation::where('toyyibpay_bill_code', $request->billcode)->first();

        if($donate)
        {
            if($donate->uuid == $request->order_id)
            {
                if($request->status_id === '1')
                {
                    $donate->update(['payment_status'=>1]);
    
                    \info(['success' => 'update order success']);
                }
                \info(['pending' => 'try response again']);
            }
            \info(['failed' => 'respond is not valid']);
        }
        else
        {
            \info(['failed' => 'Re-check response']);
        }
    }

    public function billplz_create(Request $request)
    {
        // store donater info
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|numeric|digits_between:1,10'
        ]);

        // multiply input amount with 100
        $amount = $request->amount * 100;

        $donation = Donation::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount' => $amount,
            'note' => $request->note,
        ]);

        // redirect bank option
        return redirect()->route('donate:billplz:bank', $donation);
    }

    public function billplz_bank(Donation $donation)
    {
        $key = 'Basic '.base64_encode(config('services.billplz.secret'));
        $url = config('services.billplz.url-sandbox').'v4/payment_gateways';

        $response = Http::withHeaders([
            'Authorization' => $key,
        ])->get($url);

        $banks = $response->json()['payment_gateways'];

        return view('donate.billplz.bank', compact('banks', 'donation'));
    }

    public function billplz_create_bill(Donation $donation, $bankCode)
    {   
        $collection_id = 'mlkpk949';
        $mobile = '+60'.$donation->phone;

        $key = 'Basic '.base64_encode(config('services.billplz.secret'));
        $url = config('services.billplz.url-sandbox').'v3/bills';

        // bypass billplz bill page
        $response = Http::withHeaders([
            'Authorization' => $key,
        ])->asForm()->post($url, [
            'collection_id' => $collection_id,
            'description' => $donation->note,
            'email' => $donation->email,
            'name' => $donation->name,            
            'amount' => $donation->amount,
            'reference_1_label' => "Bank Code",
            'reference_1' => $bankCode,
            'callback_url' => route('donate:billplz:callback-url'),
            'redirect_url' => route('donate:billplz:redirect-url')
            
        ]);

        $redirect_url = $response->json()['url']."?auto_submit=true";

        return redirect($redirect_url);
    }
}
