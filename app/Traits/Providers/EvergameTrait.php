<?php

namespace App\Traits\Providers;

use App\Helpers\Core as Helper;
use App\Models\Game;
use App\Models\Provider;
use App\Models\GamesKey;
use App\Models\GGRGames;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\Missions\MissionTrait;
use Illuminate\Support\Facades\Http;

trait EvergameTrait
{
    use MissionTrait;

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @var string
     */
    protected static $agentCode;
    protected static $agentToken;
    protected static $agentSecretKey;
    protected static $apiEndpoint;

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return void
     */
    public static function getCredentialsEvergame(): bool
    {
        $setting = GamesKey::first();

        self::$agentCode        = $setting->getAttributes()['evergame_agent_code'];
        self::$agentToken       = $setting->getAttributes()['evergame_agent_token'];
        self::$apiEndpoint      = $setting->getAttributes()['evergame_api_endpoint'];

        return true;
    }


    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $rtp
     * @param $provider
     * @return void
     */
    public static function UpdateRTPEvergame($rtp, $provider)
    {
        if(self::getCredentialsEvergame()) {
            $postArray = [
                "method"        => "control_rtp",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
                "vendorCode" => $provider,
                "userCode"     => auth('api')->id() . '',
                "rtp"           => $rtp
            ];

            $response = Http::post(self::$apiEndpoint, $postArray);

            if($response->successful()) {

            }
        }
    }

    /**
     * Create User
     * Metodo para criar novo usuário
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @return bool
     */
    public static function createUserEvergame()
    {
        if(self::getCredentialsEvergame()) {
            $postArray = [
                "method"        => "createUser",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
                "userCode"     => auth('api')->id() . '',
            ];

            $response = Http::post(self::$apiEndpoint, $postArray);

            if($response->successful()) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Iniciar Jogo
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * Metodo responsavel para iniciar o jogo
     *
     */
    public static function GameLaunchEvergame($provider_code, $game_code, $lang, $userId)
    {
        if(self::getCredentialsEvergame()) {
            $postArray = [
                "method"        => "GetGameUrl",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
                "userCode"     => $userId.'',
                "vendorCode" => $provider_code,
                "gameCode"     => $game_code,
                "lang"          => $lang
            ];

            //\DB::table('debug')->insert(['text' => json_encode($postArray)]);
            $response = Http::post(self::$apiEndpoint, $postArray);

            $data = $response->json();

            if($data['status'] == 5) {
                if($data['msg'] == 'INVALID_USER') {
                    if(self::createUserEvergame()) {
                        return self::GameLaunchEvergame($provider_code, $game_code, $lang, $userId);
                    }
                }
            }else{

                if($provider_code == "Evolution_Casino"){
                    $data['launchUrl'] = $data['launchUrl']."&game_id=".$game_code;
                }

                return $data;
            }

        }

    }

    /**
     * Get FIvers Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return false|void
     */
    public static function getFiversUserDetailEvergame()
    {
        if(self::getCredentialsEvergame()) {
            $dataArray = [
                "method"        => "call_players",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
            ];

            $response = Http::post(self::$apiEndpoint, $dataArray);

            if($response->successful()) {
                $data = $response->json();

                dd($data);
            }else{
                return false;
            }
        }

    }

    /**
     * Get FIvers Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $provider_code
     * @param $game_code
     * @param $lang
     * @param $userId
     * @return false|void
     */
    public static function getFiversBalanceEvergame()
    {
        if(self::getCredentialsEvergame()) {
            $dataArray = [
                "method"        => "money_info",
                "agentCode"    => self::$agentCode,
                "token"   => self::$agentToken,
            ];

            $response = Http::post(self::$apiEndpoint, $dataArray);

            if($response->successful()) {
                $data = $response->json();

                return $data['agent']['balance'] ?? 0;
            }else{
                return false;
            }
        }

    }


    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private static function GetBalanceInfoEvergame($request)
    {
        $wallet = Wallet::where('user_id', $request->userCode)->where('active', 1)->first();
        if(!empty($wallet) && $wallet->total_balance > 0) {
            return response()->json([
                'balance' => $wallet->total_balance,
                'msg' => "SUCCESS"
            ]);
        }

        return response()->json([
            'balance' => 0,
            'msg' => "INSUFFICIENT_USER_FUNDS"
        ]);
    }

    /**
     * Set Transactions
     *
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private static function SetTransactionEvergame($request)
    {

        $data = $request->all();
        $wallet = Wallet::where('user_id', $data['userCode'])->where('active', 1)->first();

        if(!empty($wallet)) {
            if(isset($data['userCode'])) {

                $amount = floatval($data['amount']);

                if($amount < 0){
                    $bet = abs($amount);
                    $win = 0;
                }else{
                    $bet = 0;
                    $win = $amount;
                }

                if ($bet == 0 && $win == 0) {
                    return response()->json([
                        "status"      => 0,
                        "balance"  => floatval(number_format($wallet->total_balance, 2, '.', '')),
                        "msg"    => "SUCCESS",
                    ]);
                }

                $game = Game::where('game_id', $data['gameCode'])->first();
                $provider = Provider::where('id', $game->provider_id)->first();

                if($wallet->balance_bonus >= $bet && $wallet->balance_bonus > 0) {
                    $changeBonus = 'balance_bonus'; /// define o tipo de transação
                }elseif($wallet->balance >= $bet && $wallet->balance > 0) {
                    $changeBonus = 'balance'; /// define o tipo de transação
                }elseif($wallet->balance_withdrawal >= $bet) {
                    $changeBonus = 'balance_withdrawal'; /// define o tipo de transação
                }


                /// verificar se usuário tem desafio ativo
                self::CheckMissionExist($data['userCode'], $game, 'evergame');

                return self::PrepareTransactionsEvergame($wallet, $data['userCode'], $data['txnCode'], $bet, $win, $game->game_name, $provider->code, $changeBonus);


            }
        }
    }

    /**
     * Prepare Transaction
     * Metodo responsavel por preparar a transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @param $wallet
     * @param $userCode
     * @param $txnId
     * @param $betMoney
     * @param $winMoney
     * @param $gameCode
     * @return \Illuminate\Http\JsonResponse|void
     */
    private static function PrepareTransactionsEvergame($wallet, $userCode, $txnId, $betMoney, $winMoney, $gameCode, $providerCode, $changeBonus)
    {
        $user = User::find($wallet->user_id);

        Helper::lossRollover($wallet, $betMoney);

        if($winMoney > $betMoney) {

            $typeAction = 'win';

            self::CreateTransactionsImperium($userCode, time(), $txnId, $typeAction, $changeBonus, $betMoney, $gameCode, $gameCode);

            /// salvar transação GGR
            GGRGames::create([
                'user_id' => $userCode,
                'provider' => $providerCode,
                'game' => $gameCode,
                'balance_bet' => $betMoney,
                'balance_win' => $winMoney,
                'currency' => $wallet->currency,
                'aggregator' => "evergame",
                "type" => "win"
            ]);


            /// pagar afiliado
            Helper::generateGameHistory($user, $typeAction, $winMoney, $betMoney, $gameCode, $gameCode, $changeBonus, $providerCode);

            $wallet = Wallet::where('user_id', $wallet->user_id)->where('active', 1)->first();

            return response()->json([
                "status"      => 0,
                "balance"  => floatval(number_format($wallet->total_balance, 2, '.', '')),
                "msg"    => "SUCCESS",
            ]);
        }

        $typeAction = 'loss';

        /// criar uma transação
        $checkTransaction = Order::where('transaction_id', $txnId)->first();

        if(empty($checkTransaction)) {
            self::CreateTransactionsImperium($userCode, time(), $txnId, $typeAction, $changeBonus, $betMoney, $gameCode, $gameCode);
        }

        $wallet->decrement($changeBonus, $betMoney);

        /// salvar transação GGR
        GGRGames::create([
            'user_id' => $userCode,
            'provider' => $providerCode,
            'game' => $gameCode,
            'balance_bet' => $betMoney,
            'balance_win' => $winMoney,
            'currency' => $wallet->currency,
            'aggregator' => "evergame",
            "type" => "loss"
        ]);

        /// pagar afiliado
        Helper::generateGameHistory($user, $typeAction, $winMoney, $betMoney, $gameCode, $gameCode, $changeBonus, $providerCode);

        $wallet = Wallet::where('user_id', $wallet->user_id)->where('active', 1)->first();

        return response()->json([
            "status"      => 0,
            "balance"  => floatval(number_format($wallet->total_balance, 2, '.', '')),
            "msg"    => "SUCCESS",
        ]);

    }

    /**
     * @param $request
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return \Illuminate\Http\JsonResponse|null
     */
    public static function WebhooksEvergame($request)
    {
        switch ($request->method) {
            case "GetBalance":
                return self::GetBalanceInfoEvergame($request);
            case "ChangeBalance":
                return self::SetTransactionEvergame($request);
            default:
                return response()->json(['status' => 0]);
        }
    }


    /**
     * Create Transactions
     * Metodo para criar uma transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @return false
     */
    private static function CreateTransactionsEvergame($playerId, $betReferenceNum, $transactionID, $type, $changeBonus, $amount, $game, $pn)
    {

        $order = Order::create([
            'user_id'       => $playerId,
            'session_id'    => $betReferenceNum,
            'transaction_id'=> $transactionID,
            'type'          => $type,
            'type_money'    => $changeBonus,
            'amount'        => $amount,
            'providers'     => 'Evergame',
            'game'          => $game,
            'game_uuid'     => $pn,
            'round_id'      => 1,
        ]);

        if($order) {
            return $order->id;
        }

        return false;
    }
}


?>
