<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['campaign.client', 'platform']);

        if ($request->filled('judul')) {
            $query->where('judul', 'like', '%'.$request->query('judul').'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('platform_id')) {
            $query->where('platform_id', $request->query('platform_id'));
        }

        // ?urut=terdekat dipakai halaman Jadwal Konten: tanggal tayang paling dekat di atas.
        $request->query('urut') === 'terdekat'
            ? $query->orderBy('tanggal_tayang')->orderBy('id')
            : $query->orderByDesc('tanggal_tayang')->orderByDesc('id');

        return PostResource::collection($query->paginate(10));
    }

    public function show(string $id)
    {
        return new PostResource(Post::with(['campaign.client', 'platform'])->findOrFail($id));
    }

    // Memakai ulang StorePostRequest yang sama dengan form web: aturan validasi cukup ditulis sekali.
    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());

        return (new PostResource($post->load(['campaign.client', 'platform'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(StorePostRequest $request, string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->status === 'tayang') {
            return response()->json(['message' => 'Post yang sudah tayang tidak bisa diedit lagi.'], 409);
        }

        $post->update($request->validated());

        return new PostResource($post->load(['campaign.client', 'platform']));
    }

    public function destroy(string $id)
    {
        Post::findOrFail($id)->delete();

        return response()->json(['message' => 'Post berhasil dihapus.']);
    }

    public function tayang(string $id)
    {
        $post = Post::with(['campaign.client', 'platform'])->findOrFail($id);

        if ($post->status !== 'dijadwalkan') {
            return response()->json(['message' => 'Hanya post berstatus "dijadwalkan" yang bisa ditandai tayang.'], 409);
        }

        $post->update([
            'status' => 'tayang',
            'tanggal_ditayangkan' => now()->toDateString(),
        ]);

        return new PostResource($post);
    }
}
