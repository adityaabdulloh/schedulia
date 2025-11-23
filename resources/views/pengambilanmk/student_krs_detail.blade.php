@extends('layouts.app')

@section('title', 'Detail KRS Mahasiswa')

@push('styles')
<style>
    /* Menambahkan sedikit custom style untuk fine-tuning jika diperlukan */
    .content-wrapper {
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper bg-gray-50 px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 mb-2">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Kartu Rencana Studi (KRS)</h1>
            <p class="mt-1 text-sm text-gray-500">Detail mata kuliah yang diambil oleh mahasiswa.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <form action="{{ route('admin.mahasiswa.krs.approveAll', $mahasiswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui semua mata kuliah yang tertunda?');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.142 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.142z" clip-rule="evenodd" />
                    </svg>
                    Setujui Semua
                </button>
            </form>
        </div>
    </div>

    {{-- Informasi Mahasiswa --}}
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
            <div class="md:col-span-1">
                <h3 class="text-lg font-semibold text-gray-800">{{ $mahasiswa->nama }}</h3>
                <p class="text-sm text-gray-500">{{ $mahasiswa->nim }}</p>
            </div>
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Program Studi</p>
                    <p class="text-base font-semibold text-gray-800">{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Kelas</p>
                    <p class="text-base font-semibold text-gray-800">{{ $mahasiswa->kelas->nama_kelas ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Semester</p>
                    <p class="text-base font-semibold text-gray-800">{{ $mahasiswa->semester }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel KRS --}}
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kode MK</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Mata Kuliah</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">SKS</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Dosen Pengampu</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalSks = 0;
                    @endphp
                    @forelse ($pengambilanMKs as $index => $pengambilan)
                        @php
                            if ($pengambilan->status == 'approved') {
                                $totalSks += $pengambilan->matakuliah->sks;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $pengambilan->matakuliah->kode_mk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $pengambilan->matakuliah->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">{{ $pengambilan->matakuliah->sks }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $pengambilan->pengampu_dosen->dosen->nama ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if ($pengambilan->status == 'approved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                @elseif ($pengambilan->status == 'pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="mt-2 font-semibold">Tidak ada mata kuliah</p>
                                    <p class="text-xs">Mahasiswa ini belum mengambil mata kuliah apapun.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pengambilanMKs->isNotEmpty())
                <tfoot class="bg-gray-100">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-800 uppercase">Total SKS Disetujui:</td>
                        <td class="px-6 py-4 text-center text-base font-bold text-gray-900">{{ $totalSks }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    
    <div class="text-center mt-8 text-sm text-gray-500">
        <p>Tahun Akademik: {{ $pengambilanMKs->first()->tahun_akademik ?? 'N/A' }}</p>
    </div>
</div>
@endsection
