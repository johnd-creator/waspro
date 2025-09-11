@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Jenis Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('jenis-limbah.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Jenis Limbah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode Limbah</th>
                                    <th width="25%">Nama Limbah</th>
                                    <th width="20%">Karakteristik</th>
                                    <th width="15%">Kategori Sumber</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jenisLimbah as $index => $jenis)
                                    <tr>
                                        <td>{{ $jenisLimbah->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $jenis->kode_limbah }}</span>
                                        </td>
                                        <td>{{ $jenis->nama_limbah }}</td>
                                        <td>
                                            @if($jenis->karakteristikLimbah)
                                                <span class="badge bg-secondary">{{ $jenis->karakteristikLimbah->nama_karakteristik }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($jenis->kategoriKegiatanSumber)
                                                <span class="badge bg-warning">{{ $jenis->kategoriKegiatanSumber->nama_kategori }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($jenis->status_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('jenis-limbah.show', $jenis) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('jenis-limbah.edit', $jenis) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('jenis-limbah.destroy', $jenis) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis limbah ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data jenis limbah</p>
                                                <a href="{{ route('jenis-limbah.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Jenis Limbah Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($jenisLimbah->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $jenisLimbah->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection