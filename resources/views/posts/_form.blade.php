<label for="campaign_id">Campaign</label>
<select name="campaign_id" id="campaign_id">
    <option value="">-- Pilih Campaign --</option>
    @foreach ($campaigns as $campaign)
        {{-- Saat create dari halaman detail campaign, ?campaign_id= di URL membuat campaign itu langsung terpilih --}}
        <option value="{{ $campaign->id }}" @selected(old('campaign_id', $post->campaign_id ?? request('campaign_id')) == $campaign->id)>
            {{ $campaign->nama_campaign }} ({{ $campaign->client->nama_brand }})
        </option>
    @endforeach
</select>
@error('campaign_id')
    <div class="error">{{ $message }}</div>
@enderror

<label for="platform_id">Platform</label>
<select name="platform_id" id="platform_id">
    <option value="">-- Pilih Platform --</option>
    @foreach ($platforms as $platform)
        <option value="{{ $platform->id }}" @selected(old('platform_id', $post->platform_id ?? '') == $platform->id)>
            {{ $platform->nama_platform }}
        </option>
    @endforeach
</select>
@error('platform_id')
    <div class="error">{{ $message }}</div>
@enderror

<label for="judul">Judul</label>
<input type="text" name="judul" id="judul" value="{{ old('judul', $post->judul ?? '') }}">
@error('judul')
    <div class="error">{{ $message }}</div>
@enderror

<label for="caption">Caption</label>
<textarea name="caption" id="caption" rows="5">{{ old('caption', $post->caption ?? '') }}</textarea>
<div class="hint">Maksimal 2.200 karakter.</div>
@error('caption')
    <div class="error">{{ $message }}</div>
@enderror

<label for="format">Format</label>
<select name="format" id="format">
    @foreach (\App\Models\Post::FORMATS as $format)
        <option value="{{ $format }}" @selected(old('format', $post->format ?? 'feed') == $format)>{{ ucfirst($format) }}</option>
    @endforeach
</select>
@error('format')
    <div class="error">{{ $message }}</div>
@enderror

<label for="tanggal_tayang">Tanggal Tayang</label>
<input type="date" name="tanggal_tayang" id="tanggal_tayang" value="{{ old('tanggal_tayang', isset($post) ? $post->tanggal_tayang->format('Y-m-d') : '') }}">
@error('tanggal_tayang')
    <div class="error">{{ $message }}</div>
@enderror

<label for="status">Status</label>
<select name="status" id="status">
    <option value="draft" @selected(old('status', $post->status ?? 'draft') == 'draft')>Draft</option>
    <option value="dijadwalkan" @selected(old('status', $post->status ?? 'draft') == 'dijadwalkan')>Dijadwalkan</option>
</select>
<div class="hint">Status "Tayang" diisi lewat tombol "Tandai Tayang", bukan dari form ini.</div>
@error('status')
    <div class="error">{{ $message }}</div>
@enderror
