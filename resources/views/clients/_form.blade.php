<label for="nama_brand">Nama Brand</label>
<input type="text" name="nama_brand" id="nama_brand" value="{{ old('nama_brand', $client->nama_brand ?? '') }}">
@error('nama_brand')
    <div class="error">{{ $message }}</div>
@enderror

<label for="industri">Industri</label>
<input type="text" name="industri" id="industri" value="{{ old('industri', $client->industri ?? '') }}" placeholder="Contoh: Fashion, Kuliner">
@error('industri')
    <div class="error">{{ $message }}</div>
@enderror

<label for="nama_kontak">Nama Kontak (PIC klien)</label>
<input type="text" name="nama_kontak" id="nama_kontak" value="{{ old('nama_kontak', $client->nama_kontak ?? '') }}">
@error('nama_kontak')
    <div class="error">{{ $message }}</div>
@enderror

<label for="email">Email</label>
<input type="text" name="email" id="email" value="{{ old('email', $client->email ?? '') }}">
@error('email')
    <div class="error">{{ $message }}</div>
@enderror

<label for="nomor_telepon">Nomor Telepon</label>
<input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $client->nomor_telepon ?? '') }}">
@error('nomor_telepon')
    <div class="error">{{ $message }}</div>
@enderror

<label for="status">Status</label>
<select name="status" id="status">
    <option value="aktif" @selected(old('status', $client->status ?? 'aktif') == 'aktif')>Aktif</option>
    <option value="nonaktif" @selected(old('status', $client->status ?? 'aktif') == 'nonaktif')>Nonaktif</option>
</select>
@error('status')
    <div class="error">{{ $message }}</div>
@enderror
