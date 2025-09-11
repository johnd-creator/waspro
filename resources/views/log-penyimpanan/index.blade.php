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
                    <h3 class="card-title">Log Penyimpanan Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('log-penyimpanan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Log
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Quick Search Bar -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('log-penyimpanan.index') }}" class="d-flex gap-2">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control form-control-lg" name="search_kode_identitas" 
                                           value="{{ request('search_kode_identitas') }}" 
                                           placeholder="🔍 Cari berdasarkan Kode Identitas Limbah (contoh: LMB-UNIT-202501-001)...">
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if(request()->hasAny(['search_kode_identitas', 'search_jenis', 'search_perusahaan', 'search_status', 'search_tanggal']))
                                    <a href="{{ route('log-penyimpanan.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                    
                    <!-- Advanced Search Form -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="collapse" id="advancedSearch">
                                <div class="card card-body">
                                    <form method="GET" action="{{ route('log-penyimpanan.index') }}" class="row g-3">
                                        <input type="hidden" name="search_kode_identitas" value="{{ request('search_kode_identitas') }}">
                                <div class="col-md-3">
                                    <label for="search_jenis" class="form-label">Jenis Limbah</label>
                                    <input type="text" class="form-control" id="search_jenis" name="search_jenis" 
                                           value="{{ request('search_jenis') }}" placeholder="Cari jenis limbah...">
                                </div>
                                <div class="col-md-3">
                                    <label for="search_perusahaan" class="form-label">Perusahaan</label>
                                    <input type="text" class="form-control" id="search_perusahaan" name="search_perusahaan" 
                                           value="{{ request('search_perusahaan') }}" placeholder="Cari perusahaan...">
                                </div>
                                <div class="col-md-2">
                                    <label for="search_status" class="form-label">Status</label>
                                    <select class="form-select" id="search_status" name="search_status">
                                        <option value="">Semua Status</option>
                                        <option value="Tersimpan" {{ request('search_status') == 'Tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                                        <option value="Diangkut" {{ request('search_status') == 'Diangkut' ? 'selected' : '' }}>Diangkut</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="search_tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="search_tanggal" name="search_tanggal" 
                                           value="{{ request('search_tanggal') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ route('log-penyimpanan.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch">
                                    <i class="fas fa-filter"></i> Pencarian Lanjutan
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    @if(request()->hasAny(['search_jenis', 'search_perusahaan', 'search_status', 'search_tanggal']))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Menampilkan hasil pencarian untuk: 
                            @if(request('search_jenis'))
                                <strong>Jenis:</strong> {{ request('search_jenis') }}
                            @endif
                            @if(request('search_perusahaan'))
                                <strong>Perusahaan:</strong> {{ request('search_perusahaan') }}
                            @endif
                            @if(request('search_status'))
                                <strong>Status:</strong> {{ request('search_status') }}
                            @endif
                            @if(request('search_tanggal'))
                                <strong>Tanggal:</strong> {{ request('search_tanggal') }}
                            @endif
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th width="12%">Kode Identitas</th>
                                    <th width="9%">Tanggal Masuk</th>
                                    <th width="12%">Jenis Limbah</th>
                                    <th width="18%">Sumber Limbah</th>
                                    <th width="7%">Jumlah (Kg)</th>
                                    <th width="13%">Perusahaan</th>
                                    <th width="7%">Status</th>
                                    <th width="10%">Status Kadaluarsa</th>
                                    <th width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $index => $log)
                                    <tr>
                                        <td>{{ $logs->firstItem() + $index }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $log->kode_identitas ?? 'Belum Ada' }}</strong>
                                            @if($log->kode_identitas)
                                                <br><small class="text-muted"><i class="fas fa-qrcode"></i> ID Limbah</small>
                                            @endif
                                        </td>
                                        <td>{{ $log->tanggal_limbah_masuk }}</td>
                                        <td>
                                            <strong>{{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}</strong>
                                            @if($log->kode_limbah)
                                                <br><small class="text-muted">{{ $log->kode_limbah }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($log->detail_sumber_limbah, 50) }}</small>
                                        </td>
                                        <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                        <td>
                                            <small>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($log->status_log == 'Tersimpan')
                                                <span class="badge bg-warning">{{ $log->status_log }}</span>
                                            @elseif($log->status_log == 'Diangkut')
                                                <span class="badge bg-success">{{ $log->status_log }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $log->status_log }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->status_log == 'Tersimpan' && $log->expiry_status)
                                                <span class="badge {{ $log->getExpiryStatusBadgeClass() }}">
                                                    {{ $log->getExpiryStatusText() }}
                                                </span>
                                                @if($log->expiry_status == 'Critical' || $log->expiry_status == 'Warning')
                                                    <br><small class="text-muted">
                                                        <i class="fas fa-clock"></i> 
                                                        @if($log->tanggal_kadaluarsa)
                                                            Kadaluarsa: {{ \Carbon\Carbon::parse($log->tanggal_kadaluarsa)->format('d/m/Y') }}
                                                        @endif
                                                    </small>
                                                @endif
                                            @elseif($log->status_log == 'Diangkut')
                                                <span class="badge badge-light">-</span>
                                            @else
                                                <span class="badge badge-secondary">Belum Dihitung</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->status_log == 'Tersimpan')
                                                <!-- Quick Action Button untuk Tandai Diangkut -->
                                                <button type="button" class="btn btn-success btn-sm mb-1 w-100" 
                                                        data-bs-toggle="modal" data-bs-target="#transportModal{{ $log->log_id }}" 
                                                        title="Quick Action: Tandai Diangkut ({{ $log->kode_identitas }})">
                                                    <i class="fas fa-truck"></i> Angkut
                                                </button>
                                            @endif
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('log-penyimpanan.show', $log) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat Detail ({{ $log->kode_identitas }})">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('log-penyimpanan.edit', $log) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit ({{ $log->kode_identitas }})">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('log-penyimpanan.destroy', $log) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus ({{ $log->kode_identitas }})"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus log {{ $log->kode_identitas }}?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    @if($log->status_log == 'Tersimpan')
                                    <!-- Transport Modal -->
                                    <div class="modal fade" id="transportModal{{ $log->log_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('log-penyimpanan.transport', $log) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tandai Sebagai Diangkut</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tanggal Pengangkutan</label>
                                                            <input type="date" name="tanggal_pengangkutan" class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Jumlah Diangkut (Kg)</label>
                                                            <input type="number" name="jumlah_diangkut" class="form-control" step="0.01" max="{{ $log->jumlah_limbah_masuk }}" required>
                                                            <small class="text-muted">Maksimal: {{ $log->jumlah_limbah_masuk }} kg</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data log penyimpanan limbah</p>
                                                <a href="{{ route('log-penyimpanan.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Log Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($logs->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection