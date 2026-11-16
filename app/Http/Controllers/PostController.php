<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Campaign;
use App\Models\Platform;
use App\Models\Post;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function index()
    {
        // Eager loading: tanpa with(), tiap baris post memicu query baru (N+1 Query Problem).
        $posts = Post::with(['campaign.client', 'platform'])
            ->when(request('search'), fn ($query, $search) => $query->where('judul', 'like', "%{$search}%"))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->when(request('platform_id'), fn ($query, $platformId) => $query->where('platform_id', $platformId))
            ->orderByDesc('tanggal_tayang')
            ->paginate(10);

        $platforms = Platform::orderBy('nama_platform')->get();

        return view('posts.index', compact('posts', 'platforms'));
    }

    /**
     * Jadwal konten: datanya diambil dari GET /api/posts (bukan query database langsung).
     */
    public function jadwal(Request $request)
    {
        $body = ['data' => [], 'meta' => null];
        $apiAktif = false;

        try {
            $response = Http::timeout(5)->get(config('services.internal_api.base_url').'/api/posts', [
                'status' => 'dijadwalkan',
                'urut' => 'terdekat',
                'page' => $request->query('page', 1),
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $apiAktif = true;
            }
        } catch (ConnectionException $e) {
            // Server API internal (port 8011) tidak bisa dihubungi: tabel jadwal tampil kosong.
        }

        return view('posts.jadwal', [
            'posts' => $body['data'] ?? [],
            'meta' => $body['meta'] ?? null,
            'apiAktif' => $apiAktif,
        ]);
    }

    public function create()
    {
        return view('posts.create', $this->pilihanForm());
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());

        return redirect()->route('campaigns.show', $post->campaign_id)
            ->with('success', "Post \"{$post->judul}\" berhasil ditambahkan.");
    }

    public function show(string $id)
    {
        $post = Post::with(['campaign.client', 'campaign.user', 'platform'])->findOrFail($id);

        return view('posts.show', compact('post'));
    }

    public function edit(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->status === 'tayang') {
            return redirect()->route('posts.show', $post->id)
                ->with('error', 'Post yang sudah tayang tidak bisa diedit lagi.');
        }

        return view('posts.edit', ['post' => $post] + $this->pilihanForm());
    }

    public function update(StorePostRequest $request, string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->status === 'tayang') {
            return redirect()->route('posts.show', $post->id)
                ->with('error', 'Post yang sudah tayang tidak bisa diedit lagi.');
        }

        $post->update($request->validated());

        return redirect()->route('posts.show', $post->id)
            ->with('success', "Post \"{$post->judul}\" berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus.');
    }

    // Route kustom di luar 7 method resource: PUT /posts/{post}/tayang
    public function tayang(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->status !== 'dijadwalkan') {
            return back()->with('error', 'Hanya post berstatus "dijadwalkan" yang bisa ditandai tayang.');
        }

        $post->update([
            'status' => 'tayang',
            'tanggal_ditayangkan' => now()->toDateString(),
        ]);

        return back()->with('success', "Post \"{$post->judul}\" ditandai sudah tayang.");
    }

    private function pilihanForm(): array
    {
        return [
            'campaigns' => Campaign::with('client')->where('status', '!=', 'selesai')->latest('tanggal_mulai')->get(),
            'platforms' => Platform::orderBy('nama_platform')->get(),
        ];
    }
}
