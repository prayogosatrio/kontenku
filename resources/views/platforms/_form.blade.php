{{-- Dipakai create DAN edit. $platform hanya ada saat edit, jadi diakses dengan ?-> dan ?? --}}
<label for="nama_platform">Nama Platform</label>
<input type="text" name="nama_platform" id="nama_platform" value="{{ old('nama_platform', $platform->nama_platform ?? '') }}">
@error('nama_platform')
    <div class="error">{{ $message }}</div>
@enderror

<label for="deskripsi">Deskripsi (opsional)</label>
<textarea name="deskripsi" id="deskripsi" rows="3">{{ old('deskripsi', $platform->deskripsi ?? '') }}</textarea>
@error('deskripsi')
    <div class="error">{{ $message }}</div>
@enderror
