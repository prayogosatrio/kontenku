<label for="client_id">Klien</label>
<select name="client_id" id="client_id">
    <option value="">-- Pilih Klien --</option>
    @foreach ($clients as $client)
        <option value="{{ $client->id }}" @selected(old('client_id', $campaign->client_id ?? '') == $client->id)>
            {{ $client->nama_brand }} ({{ $client->industri }})
        </option>
    @endforeach
</select>
@error('client_id')
    <div class="error">{{ $message }}</div>
@enderror

<label for="nama_campaign">Nama Campaign</label>
<input type="text" name="nama_campaign" id="nama_campaign" value="{{ old('nama_campaign', $campaign->nama_campaign ?? '') }}">
@error('nama_campaign')
    <div class="error">{{ $message }}</div>
@enderror

<label for="tujuan">Tujuan (opsional)</label>
<textarea name="tujuan" id="tujuan" rows="3">{{ old('tujuan', $campaign->tujuan ?? '') }}</textarea>
@error('tujuan')
    <div class="error">{{ $message }}</div>
@enderror

<label for="tanggal_mulai">Tanggal Mulai</label>
<input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', isset($campaign) ? $campaign->tanggal_mulai->format('Y-m-d') : '') }}">
@error('tanggal_mulai')
    <div class="error">{{ $message }}</div>
@enderror

<label for="tanggal_selesai">Tanggal Selesai</label>
<input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', isset($campaign) ? $campaign->tanggal_selesai->format('Y-m-d') : '') }}">
@error('tanggal_selesai')
    <div class="error">{{ $message }}</div>
@enderror

<label for="status">Status</label>
<select name="status" id="status">
    @foreach (['draft' => 'Draft', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'] as $value => $label)
        <option value="{{ $value }}" @selected(old('status', $campaign->status ?? 'draft') == $value)>{{ $label }}</option>
    @endforeach
</select>
@error('status')
    <div class="error">{{ $message }}</div>
@enderror

<p class="hint">PIC campaign: {{ isset($campaign) ? $campaign->user->name : auth()->user()->name.' (otomatis dari akun yang login)' }}.</p>
