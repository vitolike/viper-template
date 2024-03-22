<?php

namespace App\Http\Controllers\Api\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameFavorite;
use App\Models\GameLike;
use App\Models\Provider;
use App\Traits\Providers\FiversTrait;
use App\Traits\Providers\WorldslotTrait;
use App\Traits\Providers\KaGamingTrait;
use App\Traits\Providers\SalsaGamesTrait;
use App\Traits\Providers\VibraTrait;
use Illuminate\Http\Request;

class GameController extends Controller
{
    use KaGamingTrait, FiversTrait, WorldslotTrait, VibraTrait, SalsaGamesTrait;

    /**
     * @dev victormsalatiel
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::with(['games','games.provider'])
            ->whereHas('games')
            ->orderBy('name', 'desc')
            ->get();

        return response()->json(['providers' =>$providers]);
    }

    /**
     * @dev victormsalatiel
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured()
    {
        $featured_games = Game::with(['provider'])->where('is_featured', 1)->get();
        return response()->json(['featured_games' => $featured_games]);
    }

    /**
     * Source Provider
     *
     * @dev victormsalatiel
     * @param Request $request
     * @param $token
     * @param $action
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function sourceProvider(Request $request, $token, $action)
    {
        $tokenOpen = \Helper::DecToken($token);
        $validEndpoints = ['session', 'icons', 'spin', 'freenum'];

        if (in_array($action, $validEndpoints)) {
            if(isset($tokenOpen['status']) && $tokenOpen['status'])
            {
                $game = Game::whereStatus(1)->where('game_code', $tokenOpen['game'])->first();
                if(!empty($game)) {
                    $controller = \Helper::createController($game->game_code);

                    switch ($action) {
                        case 'session':
                            return $controller->session($token);
                        case 'spin':
                            return $controller->spin($request, $token);
                        case 'freenum':
                            return $controller->freenum($request, $token);
                        case 'icons':
                            return $controller->icons();
                    }
                }
            }
        } else {
            return response()->json([], 500);
        }
    }

    /**
     * @dev victormsalatiel
     * Store a newly created resource in storage.
     */
    public function toggleFavorite($id)
    {
        if(auth('api')->check()) {
            $checkExist = GameFavorite::where('user_id', auth('api')->id())->where('game_id', $id)->first();
            if(!empty($checkExist)) {
                if($checkExist->delete()) {
                    return response()->json(['status' => true, 'message' => 'Removido com sucesso']);
                }
            }else{
                $gameFavoriteCreate = GameFavorite::create([
                    'user_id' => auth('api')->id(),
                    'game_id' => $id
                ]);

                if($gameFavoriteCreate) {
                    return response()->json(['status' => true, 'message' => 'Criado com sucesso']);
                }
            }
        }
    }

    /**
     * @dev victormsalatiel
     * Store a newly created resource in storage.
     */
    public function toggleLike($id)
    {
        if(auth('api')->check()) {
            $checkExist = GameLike::where('user_id', auth('api')->id())->where('game_id', $id)->first();
            if(!empty($checkExist)) {
                if($checkExist->delete()) {
                    return response()->json(['status' => true, 'message' => 'Removido com sucesso']);
                }
            }else{
                $gameLikeCreate = GameLike::create([
                    'user_id' => auth('api')->id(),
                    'game_id' => $id
                ]);

                if($gameLikeCreate) {
                    return response()->json(['status' => true, 'message' => 'Criado com sucesso']);
                }
            }
        }
    }

    /**
     * @dev victormsalatiel
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $game = Game::with(['categories', 'provider'])->whereStatus(1)->find($id);
        if(!empty($game)) {
            $game->increment('views');

            $token = \Helper::MakeToken([
                'id' => auth('api')->id(),
                'game' => $game->game_code
            ]);

            switch ($game->distribution) {
                case 'source':
                    return response()->json([
                        'game' => $game,
                        'gameUrl' => url('/originals/'.$game->game_code.'/index.html?token='.$token),
                        'token' => $token
                    ]);
                case 'salsa':
                    return response()->json([
                        'game' => $game,
                        'gameUrl' => self::playGameSalsa('CHARGED', 'BRL', 'BR', $game->game_id),
                        'token' => $token
                    ]);
                case 'kagaming':
                    return response()->json([
                        'game' => $game,
                        'gameUrl' => self::GameLaunchKaGaming($game->game_server_url, $game->game_code),
                        'token' => $token
                    ]);
                case 'vibra_gaming':
                    return response()->json([
                        'game' => $game,
                        'gameUrl' => self::GenerateGameLaunch($game),
                        'token' => $token
                    ]);
                case 'fivers':
                    $fiversLaunch = self::GameLaunchFivers($game->provider->code, $game->game_id, 'pt', auth('api')->id());

                    if(isset($fiversLaunch['launch_url'])) {
                        return response()->json([
                            'game' => $game,
                            'gameUrl' => $fiversLaunch['launch_url'],
                            'token' => $token
                        ]);
                    }
                case 'worldslot':
                    $worldslotLaunch = self::GameLaunchWorldslot($game->provider->code, $game->game_id, 'pt', auth('api')->id());

                    if(isset($worldslotLaunch['launch_url'])) {
                        return response()->json([
                            'game' => $game,
                            'gameUrl' => $worldslotLaunch['launch_url'],
                            'token' => $token
                        ]);
                    }

                    return response()->json(['error' => $worldslotLaunch, 'status' => false ], 400);

            }


        }

        return response()->json(['error' => '', 'status' => false ], 400);
    }

    /**
     * @dev victormsalatiel
     * Show the form for editing the specified resource.
     */
    public function allGames(Request $request)
    {
        $query = Game::query();
        $query->with(['provider', 'categories']);
        $query->where('status', 1);

        if (!empty($request->provider) && $request->provider != 'all') {
            $query->where('provider_id', $request->provider);
        }

        if (!empty($request->category) && $request->category != 'all') {
            $query->whereHas('categories', function ($categoryQuery) use ($request) {
                $categoryQuery->where('slug', $request->category);
            });
        }

        if (isset($request->searchTerm) && !empty($request->searchTerm) && strlen($request->searchTerm) > 2) {
            $query->whereLike(['game_code', 'game_name', 'description', 'distribution', 'provider.name'], $request->searchTerm);
        }

        $games = $query->orderBy('views', 'desc')->paginate(12)->appends(request()->query());
        return response()->json(['games' => $games]);
    }

    /**
     * @dev victormsalatiel
     * Update the specified resource in storage.
     */
    public function webhookFiversMethod(Request $request)
    {
        return self::WebhooksFivers($request);
    }

    /**
     * Webhook Vibra Method
     *
     * @param Request $request
     * @param $parameters
     * @return array|\Illuminate\Http\JsonResponse|null
     */
    public function webhookVibraMethod(Request $request, $parameters)
    {
        return self::WebhookVibra($request, $parameters);
    }

    /**
     * @param Request $request
     * @return null
     */
    public function webhookKaGamingMethod(Request $request)
    {
        return self::WebhookKaGaming($request);
    }

    /**
     * @param Request $request
     * @return null
     */
    public function webhookSalsaMethod(Request $request)
    {
        return self::webhookSalsa($request);
    }

    /**
     * @param Request $request
     * @return null
     */
    public function WorldslotUserBalance(Request $request)
    {
        return self::GetBalanceInfoWorldslot($request);
    }

    public function WorldslotTransaction(Request $request)
    {
        return self::SetTransactionWorldslot($request);
    }

    public function WorldslotGameStart(Request $request)
    {
        return self::SetTransactionWorldslot($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
