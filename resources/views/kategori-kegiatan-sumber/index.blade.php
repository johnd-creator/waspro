@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kategori Kegiatan Sumber</h3>
                    <div class="card-tools">
                        <a href="{{ route('kategori-kegiatan-sumber.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="70%">Nama Kategori</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoriKegiatanSumber as $index => $kategori)
                                    <tr>
                                        <td>{{ $kategoriKegiatanSumber->firstItem() + $index }}</td>
                                        <td>{{ $kategori->nama_kategori }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('kategori-kegiatan-sumber.show', $kategori) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('kategori-kegiatan-sumber.edit', $kategori) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('kategori-kegiatan-sumber.destroy', $kategori) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori kegiatan sumber ini?')">
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
                                        <td colspan="3" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data kategori kegiatan sumber</p>
                                                <a href="{{ route('kategori-kegiatan-sumber.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Kategori Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($kategoriKegiatanSumber->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $kategoriKegiatanSumber->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection