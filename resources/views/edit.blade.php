<form action="{{ route('status.update', $data->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
        <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control" value="{{ old('jumlah_masuk', $data->jumlah_masuk) }}" min="0" required>
        @error('jumlah_masuk')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        @if($data->status === 'Disetujui')
            <input type="text" class="form-control" value="Disetujui" readonly>
            <div class="text-success mt-2">
                Status sudah <b>Disetujui</b> dan tidak dapat diubah lagi.
            </div>
        @else
            <select name="status" id="status" class="form-control">
                <option value="Menunggu Persetujuan" {{ $data->status == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="Disetujui" {{ $data->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Ditolak" {{ $data->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary mt-2">Ubah Data</button>
        @endif
    </div>
</form>