@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Karakteristik Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('karakteristik-limbah.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Karakteristik
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="50%">Nama Karakteristik</th>
                                    <th width="25%">Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($karakteristikLimbah as $index => $karakteristik)
                                    <tr>
                                        <td>{{ $karakteristikLimbah->firstItem() + $index }}</td>
                                        <td>{{ $karakteristik->nama_karakteristik }}</td>
                                        <td>
                                            @if($karakteristik->status_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('karakteristik-limbah.show', $karakteristik) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('karakteristik-limbah.edit', $karakteristik) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('karakteristik-limbah.destroy', $karakteristik) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus karakteristik limbah ini?')">
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
                                        <td colspan="4" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data karakteristik limbah</p>
                                                <a href="{{ route('karakteristik-limbah.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Karakteristik Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($karakteristikLimbah->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $karakteristikLimbah->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection