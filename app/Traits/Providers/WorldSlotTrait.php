<?php

namespace App\Traits\Providers;

use App\Helpers\Core as Helper;
use App\Models\Game;
use App\Models\GamesKey;
use App\Models\GGRGamesWorldslot;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\Missions\MissionTrait;
use Illuminate\Support\Facades\Http;

trait WorldslotTrait
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
    public static function getCredentialsWorldslot(): bool
    {
        $setting = GamesKey::first();

        self::$agentCode        = $setting->getAttributes()['worldslot_agent_code'];
        self::$agentToken       = $setting->getAttributes()['worldslot_agent_token'];
        self::$agentSecretKey   = $setting->getAttributes()['worldslot_agent_secret_key'];
        self::$apiEndpoint      = $setting->getAttributes()['worldslot_api_endpoint'];

        return true;
    }

    public static function GetAllGamesWorldslot()
    {
        if(self::getCredentialsWorldslot()) {


        }
    }

    /**
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $rtp
     * @param $provider
     * @return void
     */
    public static function UpdateRTPWorldslot($rtp, $provider)
    {
        if(self::getCredentialsWorldslot()) {
            $postArray = [
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
                "provider_code" => $provider,
                "user_code"     => auth('api')->id() . '',
                "rtp"           => $rtp
            ];

            $response = Http::post(self::$apiEndpoint."/control_rtp", $postArray);

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
    public static function createUserWorldslot()
    {
        if(self::getCredentialsWorldslot()) {
            $postArray = [
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
                "user_code"     => auth('api')->id() . '',
            ];

            $response = Http::post(self::$apiEndpoint."/user_create", $postArray);

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
    public static function GameLaunchWorldslot($provider_code, $game_code, $lang, $userId)
    {
        if(self::getCredentialsWorldslot()) {
            $wallet = Wallet::where('user_id', $userId)->first();
            $balance = $wallet->total_balance;
            
            $postArray = [
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
                "user_code"     => $userId.'',
                "provider_code" => $provider_code,
                "game_code"     => $game_code,
                "lang"          => $lang,
                "user_balance"  => $balance
            ];

            //\DB::table('debug')->insert(['text' => json_encode($postArray)]);
            $response = Http::post(self::$apiEndpoint."/game_launch", $postArray);

            if($response->successful()) {
                $data = $response->json();

                if($data['status'] == 0) {
                    if($data['msg'] == 'Invalid User') {
                        if(self::createUserWorldslot()) {
                            return self::GameLaunchWorldslot($provider_code, $game_code, $lang, $userId);
                        }
                    }
                }else{
                    return $data;
                }
            }else{
                return false;
            }
        }

    }

    /**
     * Get Worldslot Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @return false|void
     */
    public static function getWorldslotUserDetail()
    {
        if(self::getCredentialsWorldslot()) {
            $dataArray = [
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
            ];

            $response = Http::post(self::$apiEndpoint."/call_players", $dataArray);

            if($response->successful()) {
                $data = $response->json();

                dd($data);
            }else{
                return false;
            }
        }

    }

    /**
     * Get Worldslot Balance
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     * @param $provider_code
     * @param $game_code
     * @param $lang
     * @param $userId
     * @return false|void
     */
    public static function getWorldslotBalance()
    {
        if(self::getCredentialsWorldslot()) {
            $dataArray = [
                "agent_code"    => self::$agentCode,
                "agent_token"   => self::$agentToken,
            ];

            $response = Http::post(self::$apiEndpoint."/info", $dataArray);

            if($response->successful()) {
                $data = $response->json();

                return $data['agent_balance'] ?? 0;
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
    private static function GetBalanceInfoWorldslot($request)
    {
        $wallet = Wallet::where('user_id', $request->user_code)->where('active', 1)->first();
        if(!empty($wallet) && $wallet->total_balance > 0) {
            return response()->json([
                'status' => 1,
                'user_balance' => $wallet->total_balance
            ]);
        }

        return response()->json([
            'status' => 0,
            'user_balance' => 0,
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
    private static function SetTransactionWorldslot($request)
    {
        $data = $request->all();
        $wallet = Wallet::where('user_id', $request->user_code)->where('active', 1)->first();

        if(!empty($wallet)) {
            if($data['game_type'] == 'slot' && isset($data['slot'])) {

                $game = Game::where('game_code', $data['slot']['game_code'])->first();

                /// verificar se usuário tem desafio ativo
                self::CheckMissionExist($request->user_code, $game, 'wordslot');

                $winMoney = (floatval($data['slot']['win']) - floatval($data['slot']['bet']));
                return self::PrepareTransactionsWorldslot($wallet, $request->user_code, $data['slot']['txn_id'], $data['slot']['bet'], $winMoney, $data['slot']['game_code'], $data['slot']['provider_code']);
            }

            if($data['game_type'] == 'live' &&  isset($data['live'])) {
                $game = Game::where('game_code', $data['live']['game_code'])->first();

                /// verificar se usuário tem desafio ativo
                self::CheckMissionExist($request->user_code, $game, 'wordslot');

                return self::PrepareTransactionsWorldslot($wallet, $request->user_code, $data['live']['txn_id'], $data['live']['bet'], $data['live']['win'], $data['live']['game_code'], $data['live']['provider_code']);
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
    private static function PrepareTransactionsWorldslot($wallet, $userCode, $txnId, $betMoney, $winMoney, $gameCode, $providerCode)
    {
        $user = User::find($wallet->user_id);

        $typeAction  = 'bet';
        $changeBonus = 'balance';
        $bet = floatval($betMoney);

        /// deduz o saldo apostado
        if($wallet->balance_bonus >= $bet) {
            $wallet->decrement('balance_bonus', $bet); /// retira do bonus
            $changeBonus = 'balance_bonus'; /// define o tipo de transação
        }elseif($wallet->balance >= $bet) {
            $wallet->decrement('balance', $bet); /// retira do saldo depositado
            $changeBonus = 'balance'; /// define o tipo de transação
        }elseif($wallet->balance_withdrawal >= $bet) {
            $wallet->decrement('balance_withdrawal', $bet); /// retira do saldo liberado pra saque
            $changeBonus = 'balance_withdrawal'; /// define o tipo de transação
        }

        if(floatval($winMoney) > $bet) {
            $typeAction = 'win';
            self::CreateTransactionsWorldslot($userCode, time(), $txnId, $typeAction, $changeBonus, $betMoney, $gameCode, $gameCode);

            /// salvar transação GGR
            GGRGamesWorldslot::create([
                'user_id' => $userCode,
                'provider' => $providerCode,
                'game' => $gameCode,
                'balance_bet' => $betMoney,
                'balance_win' => $winMoney,
                'currency' => $wallet->currency
            ]);

            /// pagar afiliado
            Helper::generateGameHistory($user, $typeAction, $winMoney, $betMoney, $gameCode, $gameCode, $changeBonus, $providerCode);

            return response()->json([
                'status' => 1,
                'user_balance' => $wallet->total_balance
            ]);
        }

        /// criar uma transação
        $checkTransaction = Order::where('transaction_id', $txnId)->first();
        if(empty($checkTransaction)) {
            self::CreateTransactionsWorldslot($userCode, time(), $txnId, $typeAction, $changeBonus, $betMoney, $gameCode, $gameCode);
        }

        /// salvar transação GGR
        GGRGamesWorldslot::create([
            'user_id' => $userCode,
            'provider' => $providerCode,
            'game' => $gameCode,
            'balance_bet' => $betMoney,
            'balance_win' => 0,
            'currency' => $wallet->currencyS
        ]);

        Helper::lossRollover($wallet, $betMoney);

        /// pagar afiliado
        Helper::generateGameHistory($user, 'loss', $winMoney, $betMoney, $gameCode, $gameCode, $changeBonus, $providerCode);

        return response()->json([
            'status' => 1,
            'user_balance' => $wallet->total_balance
        ]);

    }


    /**
     * Create Transactions
     * Metodo para criar uma transação
     * @dev victormsalatiel - Corra de golpista, me chame no instagram
     *
     * @return false
     */
    private static function CreateTransactionsWorldslot($playerId, $betReferenceNum, $transactionID, $type, $changeBonus, $amount, $game, $pn)
    {

        $order = Order::create([
            'user_id'       => $playerId,
            'session_id'    => $betReferenceNum,
            'transaction_id'=> $transactionID,
            'type'          => $type,
            'type_money'    => $changeBonus,
            'amount'        => $amount,
            'providers'     => 'Worldslot',
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
