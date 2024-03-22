<?php

namespace App\Traits\Providers;

use App\Helpers\Core as Helper;
use App\Models\GamesKey;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;

trait VeniXTrait
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function WebhookVeniX($request)
    {
        if(!empty($request->method)) {
            switch($request->method) {
                case 'account_details':
                    return $this->AccountDetailsVeniX($request);
                case 'user_balance':
                    return $this->GetBalanceVeniX($request);
                case 'transaction':
                    return $this->SetTransactionVeniX($request);
                case 'refund':
                    return $this->SetRefundVeniX($request);
            }
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function AccountDetailsVeniX($request)
    {
        $user = User::find(1);

        return response()->json([
            'email' => $user->email,
            'date' => $user->created_at,
        ]);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function GetBalanceVeniX($request)
    {
        $user = User::with(['wallet'])->find(1);

        return response()->json([
            'status' => 1,
            'balance' => $user->wallet->balance,
        ]);
    }

    private function SetTransactionVeniX($request)
    {
        $user = User::with(['wallet'])->find(1);
        \Log::info(json_encode($request->all()));

        if($request->type == 'bet') {
            $user->wallet->decrement('balance', $request->bet);
        }

        return response()->json([
            'status' => 1,
            'balance' => $user->wallet->balance,
        ]);
    }

    private function SetRefundVeniX($request)
    {

        return response()->json([
            'status' => 1,
        ]);
    }
}
