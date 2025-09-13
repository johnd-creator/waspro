@extends('layouts.app')

@section('title', 'Pengangkutan Limbah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengangkutan Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengangkutan-limbah.diangkut') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-truck"></i> Limbah Diangkut
                        </a>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body">
                    <form method="GET" action="{{ route('pengangkutan-limbah.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="jenis_limbah" class="form-control form-control-sm">
                                    <option value="">Semua Jenis Limbah</option>
                                    @foreach($jenisLimbah as $jenis)
                                        <option value="{{ $jenis->kode_limbah }}" 
                                            {{ request('jenis_limbah') == $jenis->kode_limbah ? 'selected' : '' }}>
                                            {{ $jenis->nama_limbah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="perusahaan" class="form-control form-control-sm">
                                    <option value="">Semua Perusahaan</option>
                                    @foreach($perusahaan as $p)
                                        <option value="{{ $p->perusahaan_id }}" 
                                            {{ request('perusahaan') == $p->perusahaan_id ? 'selected' : '' }}>
                                            {{ $p->nama_perusahaan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">Semua Status</option>
                                    @foreach($statusOptions as $status)
                                        <option value="{{ $status }}" 
                                            {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="tanggal_mulai" class="form-control form-control-sm" 
                                    value="{{ request('tanggal_mulai') }}" placeholder="Tanggal Mulai">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="tanggal_akhir" class="form-control form-control-sm" 
                                    value="{{ request('tanggal_akhir') }}" placeholder="Tanggal Akhir">
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('pengangkutan-limbah.index') }}" class="btn btn-secondary btn-sm ml-1">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <input type="text" name="kode_identitas" class="form-control form-control-sm" 
                                    value="{{ request('kode_identitas') }}" placeholder="Cari Kode Identitas...">
                            </div>
                        </div>
                    </form>

                    @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                    <!-- Bulk Action Form -->
                    <form id="bulkApproveForm" method="POST" action="{{ route('pengangkutan-limbah.bulk-approve') }}">
                        @csrf
                        <div class="mb-3">
                            <button type="button" id="selectAll" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-square"></i> Pilih Semua
                            </button>
                            <button type="button" id="deselectAll" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-square"></i> Batal Pilih
                            </button>
                            <button type="submit" id="bulkApproveBtn" class="btn btn-sm btn-success" disabled>
                                <i class="fas fa-truck"></i> Setujui Pengangkutan Terpilih
                            </button>
                        </div>
                    @endif

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                                    <th width="30">
                                        <input type="checkbox" id="masterCheckbox">
                                    </th>
                                    @endif
                                    <th>Kode Identitas</th>
                                    <th>Jenis Limbah</th>
                                    <th>Perusahaan</th>
                                    <th>Unit</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Status</th>
                                    <th>Sisa Hari</th>
                                    @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logPenyimpanan as $log)
                                <tr>
                                    @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                                    <td>
                                        <input type="checkbox" name="selected_logs[]" value="{{ $log->log_id }}" class="log-checkbox">
                                    </td>
                                    @endif
                                    <td>{{ $log->kode_identitas }}</td>
                                    <td>{{ $log->jenisLimbah->nama_limbah ?? '-' }}</td>
                                    <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? '-' }}</td>
                                    <td>{{ $log->unitPembangkit->nama_unit ?? '-' }}</td>
                                    <td>{{ $log->tanggal_limbah_masuk->format('d/m/Y') }}</td>
                                    <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $log->getStatusBadgeClass() }}">
                                            {{ $log->getStatusText() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($log->getSisaHariKadaluarsa() !== null)
                                            {{ $log->getSisaHariKadaluarsa() }} hari
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                                    <td>
                                        <form method="POST" action="{{ route('pengangkutan-limbah.approve', $log->log_id) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengangkutan limbah ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isSupervisor() || auth()->user()->isAdmin() ? '10' : '8' }}" class="text-center">
                                        Tidak ada data limbah yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                    </form>
                    @endif

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $logPenyimpanan->firstItem() ?? 0 }} sampai {{ $logPenyimpanan->lastItem() ?? 0 }} 
                            dari {{ $logPenyimpanan->total() }} data
                        </div>
                        <div>
                            {{ $logPenyimpanan->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Master checkbox functionality
    $('#masterCheckbox').change(function() {
        $('.log-checkbox').prop('checked', this.checked);
        toggleBulkApproveButton();
    });

    // Individual checkbox functionality
    $('.log-checkbox').change(function() {
        var totalCheckboxes = $('.log-checkbox').length;
        var checkedCheckboxes = $('.log-checkbox:checked').length;
        
        $('#masterCheckbox').prop('checked', totalCheckboxes === checkedCheckboxes);
        toggleBulkApproveButton();
    });

    // Select all button
    $('#selectAll').click(function() {
        $('.log-checkbox').prop('checked', true);
        $('#masterCheckbox').prop('checked', true);
        toggleBulkApproveButton();
    });

    // Deselect all button
    $('#deselectAll').click(function() {
        $('.log-checkbox').prop('checked', false);
        $('#masterCheckbox').prop('checked', false);
        toggleBulkApproveButton();
    });

    // Toggle bulk approve button
    function toggleBulkApproveButton() {
        var checkedCount = $('.log-checkbox:checked').length;
        $('#bulkApproveBtn').prop('disabled', checkedCount === 0);
    }

    // Bulk approve form submission
    $('#bulkApproveForm').submit(function(e) {
        var checkedCount = $('.log-checkbox:checked').length;
        if (checkedCount === 0) {
            e.preventDefault();
            alert('Pilih minimal satu limbah untuk disetujui pengangkutannya.');
            return false;
        }
        
        return confirm('Apakah Anda yakin ingin menyetujui pengangkutan ' + checkedCount + ' limbah yang dipilih?');
    });
});
</script>
@endpush