@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Unit Pembangkit</h3>
                    <div class="card-tools">
                        <a href="{{ route('unit-pembangkit.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Unit Pembangkit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama Unit</th>
                                    <th width="30%">Alamat</th>
                                    <th width="15%">Kota</th>
                                    <th width="10%">Kode Pos</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitPembangkit as $index => $unit)
                                    <tr>
                                        <td>{{ $unitPembangkit->firstItem() + $index }}</td>
                                        <td>{{ $unit->nama_unit }}</td>
                                        <td>{{ $unit->alamat_unit ?? '-' }}</td>
                                        <td>{{ $unit->kota ?? '-' }}</td>
                                        <td>{{ $unit->kode_pos ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('unit-pembangkit.show', $unit) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('unit-pembangkit.edit', $unit) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('unit-pembangkit.destroy', $unit) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus unit pembangkit ini?')">
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
                                                <p class="text-muted">Belum ada data unit pembangkit</p>
                                                <a href="{{ route('unit-pembangkit.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Unit Pembangkit Pertama
                        </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($unitPembangkit->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $unitPembangkit->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection