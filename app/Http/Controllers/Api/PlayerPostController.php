<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{ResponseHelper ,StorageHelper};
use App\Http\Controllers\Controller;
use App\Http\Resources\{PlayerPostResource, UserResource};
use App\Models\{Player, PlayerPost, PlayerPostLike, PlayerPostMedia, User};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerPostController extends Controller
{
    public function index() {
        $user   = auth()->user();
        $player = $user->player;
        $posts  = PlayerPost::with(['postMedia', 'player.user'])
            ->whereHas('player', function($query) use($player) {
                $followingIds = $player->followings()
                    ->select('following_id')
                    ->get()
                    ->pluck('following_id');

                $query->whereIn('id', $followingIds);
            })
            ->latest()
            ->paginate(10);

        return ResponseHelper::paginate(PlayerPostResource::collection($posts));
    }


    public function indexPerPlayer(User $user) {
        try{
            $posts = PlayerPost::with(['postMedia'])
                ->whereRelation('player', 'user_id', $user->id)
                ->latest()
                ->paginate(10);;

            return ResponseHelper::paginate(PlayerPostResource::collection($posts));
        }catch(ErrorException $err) {
            ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


    public function explore() {
        $posts = PlayerPost::with(['postMedia', 'player.user'])
            ->latest()
            ->paginate(10);

        return ResponseHelper::paginate(
            PlayerPostResource::collection($posts)
        );
    }
    

    public function store(Request $request) {
        try{
            $validator  = Validator::make($request->all(), [
                'text'      => 'nullable|max:4000',
                'media'     => 'nullable|array|max:20',
                'media.*'   => 'mimes:jpeg,jpg,png,bmp,gif,svg,mp4,avi,mpeg,qt|max:30720' // MAX:30MB
            ]);

            if($validator->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors()->all(),
                ]);

            $userId = auth()->user()->id;
            $user   = User::with(['player'])->find($userId);
            $text   = $request->text;
            $media  = $request->media;                            

            if(!$text && !$media)
                throw new Exception('Unprocessable, One of the text or media fields is required', 422);
            if(!$user->player->is_pro_player)
                throw new Exception('Unprocessable, Only pro players can post', 422);

            $post = PlayerPost::create([
                'player_id' => $user->player->id,
                'text'  => $text
            ]);

            if($media) foreach($media as $item) {
                $mediaPath  = StorageHelper::put('images/users/posts', $item);
                PlayerPostMedia::create([
                    'player_post_id'    => $post->id,
                    'url'               => $mediaPath,
                ]);
            }

            $post->load('postMedia');

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => PlayerPostResource::make($post)
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();

            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage,
            ], $errCode);
        }
    }


    public function show(PlayerPost $playerPost) {
        try{
            $playerPost->load('postMedia');

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => PlayerPostResource::make($playerPost),
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();

            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage,
            ]);
        }
    }


    public function showPerPlayer(User $user, PlayerPost $playerPost) {
        try{
            return ResponseHelper::make(
                PlayerPostResource::make($playerPost->load('postMedia'))
            );
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


    public function destroy(PlayerPost $playerPost) {
        try{
            $user   = auth()->user();

            if($user->player->id !== $playerPost->player->id)
                throw new Exception('Not allowed, this is not your post', 403);

            $playerPost->load('postMedia');
            $media  = $playerPost->postMedia;

            if($media) foreach($media as $item) {
                StorageHelper::delete($item->url);
            }

            $playerPost->playerPostLikes()->delete();
            $playerPost->postMedia()->delete();
            $playerPost->delete();

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => PlayerPostResource::make($playerPost),
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();

            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage,
            ]);
        }
    }


    public function like(User $user, PlayerPost $playerPost) {
        try{
            $player = auth()->user()->player;

            $like   = PlayerPostLike::firstOrCreate([
                'player_id'      => $player->id,
                'player_post_id' => $playerPost->id,
            ]);

            return ResponseHelper::make($like);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


    public function likes(User $user, PlayerPost $playerPost) {
        $users = User::whereHas('player', function($query) use($playerPost) {
            $query->whereRelation('playerPostLikes', 'player_post_id', $playerPost->id);
        })->paginate(10);

        return ResponseHelper::paginate(UserResource::collection($users));
    }


    public function unlike(User $user, PlayerPost $playerPost) {
        try{
            $playerId = auth()->user()->player->id ?? null;
            $player   = Player::with('playerPostLikes')->find($playerId);

            $like = $player->playerPostLikes()
                ->where('player_post_id', $playerPost->id)
                ->first();

            if(!$like) throw new ErrorException('Unprocessable', 422, [
                "You don't like this post yet"
            ]);
            
            $like->delete();

            return ResponseHelper::make($like);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
