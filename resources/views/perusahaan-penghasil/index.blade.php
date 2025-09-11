@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Perusahaan Penghasil Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('perusahaan-penghasil.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Perusahaan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama Perusahaan</th>
                                    <th width="30%">Alamat</th>
                                    <th width="20%">Kontak</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perusahaanPenghasil as $index => $perusahaan)
                                    <tr>
                                        <td>{{ $perusahaanPenghasil->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $perusahaan->nama_perusahaan }}</strong>
                                            @if($perusahaan->jenis_perusahaan)
                                                <br><small class="text-muted">{{ $perusahaan->jenis_perusahaan }}</small>
                                            @endif
                                        </td>

                                        <td>
                                            <small>
                                                {{ Str::limit($perusahaan->alamat_perusahaan, 50) }}
                                                @if($perusahaan->kota)
                                                    <br><strong>{{ $perusahaan->kota }}</strong>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            @if($perusahaan->telepon)
                                                <small><i class="fas fa-phone"></i> {{ $perusahaan->telepon }}</small><br>
                                            @endif
                                            @if($perusahaan->email)
                                                <small><i class="fas fa-envelope"></i> {{ $perusahaan->email }}</small>
                                            @endif
                                        </td>

                                        <td>
                                            @if($perusahaan->status_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('perusahaan-penghasil.show', $perusahaan) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('perusahaan-penghasil.edit', $perusahaan) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('perusahaan-penghasil.destroy', $perusahaan) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus perusahaan ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data perusahaan penghasil limbah</p>
                                                <a href="{{ route('perusahaan-penghasil.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Perusahaan Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($perusahaanPenghasil->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $perusahaanPenghasil->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection