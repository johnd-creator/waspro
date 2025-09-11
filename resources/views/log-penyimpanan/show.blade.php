@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Log Penyimpanan Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('log-penyimpanan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('log-penyimpanan.edit', $logPenyimpanan) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Limbah</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Tanggal Masuk:</strong></td>
                                    <td>{{ $logPenyimpanan->tanggal_limbah_masuk }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Limbah:</strong></td>
                                    <td>
                                        {{ $logPenyimpanan->jenisLimbah->nama_limbah ?? 'N/A' }}<br>
                                        <small class="text-muted">Kode: {{ $logPenyimpanan->kode_limbah }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Masuk:</strong></td>
                                    <td>{{ number_format($logPenyimpanan->jumlah_limbah_masuk, 2) }} Kg</td>
                                </tr>
                                <tr>
                                    <td><strong>Maksimal Penyimpanan:</strong></td>
                                    <td>
                                        {{ $logPenyimpanan->maksimal_penyimpanan_tanggal }}
                                        @php
                                            $maxDate = \Carbon\Carbon::parse($logPenyimpanan->maksimal_penyimpanan_tanggal);
                                            $now = \Carbon\Carbon::now();
                                            $daysLeft = $now->diffInDays($maxDate, false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Kadaluarsa {{ abs($daysLeft) }} hari</span>
                                        @elseif($daysLeft <= 30)
                                            <span class="badge bg-warning">{{ $daysLeft }} hari lagi</span>
                                        @else
                                            <span class="badge bg-success">{{ $daysLeft }} hari lagi</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($logPenyimpanan->status_log == 'Tersimpan')
                                            <span class="badge bg-warning fs-6">{{ $logPenyimpanan->status_log }}</span>
                                        @elseif($logPenyimpanan->status_log == 'Diangkut')
                                            <span class="badge bg-success fs-6">{{ $logPenyimpanan->status_log }}</span>
                                        @else
                                            <span class="badge bg-danger fs-6">{{ $logPenyimpanan->status_log }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($logPenyimpanan->status_log == 'Tersimpan')
                                <tr>
                                    <td><strong>Status Kadaluarsa:</strong></td>
                                    <td>
                                        @if($logPenyimpanan->expiry_status)
                                            <span class="badge {{ $logPenyimpanan->getExpiryStatusBadgeClass() }} fs-6">
                                                {{ $logPenyimpanan->getExpiryStatusText() }}
                                            </span>
                                            @if($logPenyimpanan->tanggal_kadaluarsa)
                                                <br><small class="text-muted mt-1">
                                                    <i class="fas fa-calendar-alt"></i> 
                                                    Tanggal Kadaluarsa: {{ \Carbon\Carbon::parse($logPenyimpanan->tanggal_kadaluarsa)->format('d F Y') }}
                                                </small>
                                                @php
                                                    $daysUntilExpiry = $logPenyimpanan->getDaysUntilExpiry();
                                                @endphp
                                                @if($daysUntilExpiry !== null)
                                                    <br><small class="text-muted">
                                                        <i class="fas fa-clock"></i> 
                                                        @if($daysUntilExpiry > 0)
                                                            {{ $daysUntilExpiry }} hari lagi
                                                        @elseif($daysUntilExpiry == 0)
                                                            Kadaluarsa hari ini
                                                        @else
                                                            Sudah kadaluarsa {{ abs($daysUntilExpiry) }} hari
                                                        @endif
                                                    </small>
                                                @endif
                                            @endif
                                        @else
                                            <span class="badge bg-secondary fs-6">Belum Dihitung</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Informasi Perusahaan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Perusahaan:</strong></td>
                                    <td>{{ $logPenyimpanan->perusahaanPenghasil->nama_perusahaan ?? 'Tidak ada' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unit Pembangkit:</strong></td>
                                    <td>{{ $logPenyimpanan->unitPembangkit->nama_unit ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat Unit:</strong></td>
                                    <td>{{ $logPenyimpanan->unitPembangkit->alamat_unit ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diinput oleh:</strong></td>
                                    <td>{{ $logPenyimpanan->penggunaSistem->nama_lengkap ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Input:</strong></td>
                                    <td>{{ $logPenyimpanan->timestamp_input ?? $logPenyimpanan->created_at }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Detail Sumber Limbah</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {{ $logPenyimpanan->detail_sumber_limbah }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($logPenyimpanan->status_log == 'Diangkut')
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Informasi Pengangkutan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="20%"><strong>Tanggal Pengangkutan:</strong></td>
                                    <td>{{ $logPenyimpanan->tanggal_pengangkutan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Diangkut:</strong></td>
                                    <td>{{ number_format($logPenyimpanan->jumlah_diangkut, 2) }} Kg</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
                    
                    @if($logPenyimpanan->jenisLimbah)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Informasi Jenis Limbah</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="20%"><strong>Nama Limbah:</strong></td>
                                    <td>{{ $logPenyimpanan->jenisLimbah->nama_limbah }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kemasan:</strong></td>
                                    <td>{{ $logPenyimpanan->jenisLimbah->kemasan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Penyimpanan:</strong></td>
                                    <td>{{ $logPenyimpanan->jenisLimbah->waktu_penyimpanan_hari }} hari</td>
                                </tr>
                                <tr>
                                    <td><strong>Karakteristik:</strong></td>
                                    <td>{{ $logPenyimpanan->jenisLimbah->karakteristik->nama_karakteristik ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori Kegiatan:</strong></td>
                                    <td>{{ $logPenyimpanan->jenisLimbah->kategori->nama_kategori ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection