@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Judul Tabel -->
    <div class="col-12">
        <h4 class="title">Data Jam</h4>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <a href="{{ route('jam.create') }}" class="btn btn-primary mb-3">Tambah Jam</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="fw-bold" style="color: black; background-color: #d9edfc;">No</th>
                <th class="fw-bold" style="color: black; background-color: #d9edfc;">Jam Mulai</th>
                <th class="fw-bold" style="color: black; background-color: #d9edfc;">Jam Selesai</th>
                <th class="fw-bold" style="color: black; background-color: #d9edfc;">Durasi (menit)</th>
                <th class="fw-bold" style="color: black; background-color: #d9edfc;">Aksi</th>

            </tr>
        </thead>
        <tbody>
            @forelse($jamList as $jam)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jam->jam_mulai }}</td>
                    <td>{{ $jam->jam_selesai }}</td>
                    <td>{{ $jam->durasi }}</td>
                    <td>
                        <a href="{{ route('jam.edit', $jam->id) }}" class="btn btn-warning btn-sm"> <i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('jam.destroy', $jam->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $jam->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(event, 'delete-form-{{ $jam->id }}')"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data jam tersedia</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(event, formId) {
    event.preventDefault(); // Prevent the default form submission
    const form = document.getElementById(formId);

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data jam ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // Submit the form if confirmed
        }
    });
}
</script>
@endpush