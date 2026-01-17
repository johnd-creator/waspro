<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use App\Models\LogPenyimpananLimbah;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the current user
     */
    public function index(Request $request)
    {
        $notifications = $this->getExpiryNotificationsData();

        if ($request->ajax()) {
            return response()->json([
                'notifications' => $notifications,
                'count' => count($notifications),
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get count of unread notifications
     */
    public function getCount()
    {
        $notifications = $this->getExpiryNotificationsData();
        $unreadCount = $this->getUnreadCount($notifications);

        return response()->json([
            'count' => $unreadCount,
        ]);
    }

    /**
     * Get expiry notifications for waste (API endpoint)
     */
    public function getExpiryNotifications()
    {
        $notifications = $this->getExpiryNotificationsData();

        return response()->json([
            'notifications' => $notifications,
            'count' => count($notifications),
        ]);
    }

    /**
     * Get expiry notifications for waste (internal method)
     */
    private function getExpiryNotificationsData()
    {
        // Get thresholds
        $warningDays = (int) ApplicationSetting::getValue('warning_days', 30);
        $criticalDays = (int) ApplicationSetting::getValue('critical_days', 7);
        $urgentDays = (int) ApplicationSetting::getValue('expiry.urgent_days', 3);

        // Calculate cutoff date
        $cutoffDate = now()->addDays($warningDays);

        // Get stored waste that is approaching expiry or expired
        // Optimized: Filter in database instead of memory and limit to 50 records
        $allStoredWaste = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
            ->where('status_log', 'Tersimpan')
            ->where('maksimal_penyimpanan_tanggal', '<=', $cutoffDate)
            ->orderBy('maksimal_penyimpanan_tanggal', 'asc')
            ->limit(50)
            ->get();

        $notifications = [];

        foreach ($allStoredWaste as $waste) {
            $daysUntilExpiry = $waste->getDaysUntilExpiry();

            if ($daysUntilExpiry === null) {
                continue;
            }

            $notification = [
                'id' => $waste->log_id,
                'kode_identitas' => $waste->kode_identitas,
                'jenis_limbah' => $waste->jenisLimbah->nama_limbah ?? 'Unknown',
                'perusahaan' => $waste->perusahaanPenghasil->nama_perusahaan ?? 'Unknown',
                'unit' => $waste->unitPembangkit->nama_unit ?? 'Unknown',
                'jumlah' => $waste->jumlah_limbah_masuk,
                'days_until_expiry' => $daysUntilExpiry,
                'created_at' => $waste->created_at,
            ];

            if ($daysUntilExpiry <= 0) {
                // Expired
                $notification['type'] = 'expired';
                $notification['priority'] = 'critical';
                $notification['title'] = 'Limbah Kadaluarsa';
                $notification['message'] = "Limbah {$waste->kode_identitas} telah kadaluarsa " . abs($daysUntilExpiry) . ' hari yang lalu';
                $notification['icon'] = 'fas fa-exclamation-triangle';
                $notification['color'] = 'danger';
                $notifications[] = $notification;
            } elseif ($daysUntilExpiry <= $urgentDays) {
                // Urgent (3 days or less)
                $notification['type'] = 'urgent';
                $notification['priority'] = 'high';
                $notification['title'] = 'Limbah Kritis';
                $notification['message'] = "Limbah {$waste->kode_identitas} akan kadaluarsa dalam {$daysUntilExpiry} hari";
                $notification['icon'] = 'fas fa-exclamation-circle';
                $notification['color'] = 'danger';
                $notifications[] = $notification;
            } elseif ($daysUntilExpiry <= $criticalDays) {
                // Critical (7 days or less)
                $notification['type'] = 'critical';
                $notification['priority'] = 'medium';
                $notification['title'] = 'Peringatan Limbah';
                $notification['message'] = "Limbah {$waste->kode_identitas} akan kadaluarsa dalam {$daysUntilExpiry} hari";
                $notification['icon'] = 'fas fa-exclamation';
                $notification['color'] = 'warning';
                $notifications[] = $notification;
            } elseif ($daysUntilExpiry <= $warningDays) {
                // Warning (within warning days threshold)
                $notification['type'] = 'warning';
                $notification['priority'] = 'low';
                $notification['title'] = 'Perhatian Limbah';
                $notification['message'] = "Limbah {$waste->kode_identitas} akan kadaluarsa dalam {$daysUntilExpiry} hari";
                $notification['icon'] = 'fas fa-info-circle';
                $notification['color'] = 'info';
                $notifications[] = $notification;
            }
        }

        // Sort by priority and days until expiry
        usort($notifications, function ($a, $b) {
            $priorityOrder = ['critical' => 0, 'high' => 1, 'medium' => 2, 'low' => 3];

            if ($priorityOrder[$a['priority']] !== $priorityOrder[$b['priority']]) {
                return $priorityOrder[$a['priority']] - $priorityOrder[$b['priority']];
            }

            return $a['days_until_expiry'] - $b['days_until_expiry'];
        });

        return $notifications;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        // Store the current timestamp in session
        session(['notifications_read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai sebagai sudah dibaca',
        ]);
    }

    /**
     * Mark notification as read (for future implementation)
     */
    public function markAsRead(Request $request, $id)
    {
        // This can be implemented later with a notifications table
        return response()->json(['success' => true]);
    }

    /**
     * Get count of unread notifications
     */
    private function getUnreadCount($notifications)
    {
        $readAt = session('notifications_read_at');

        if (!$readAt) {
            return count($notifications);
        }

        // Count notifications created after the last read timestamp
        $unreadCount = 0;
        foreach ($notifications as $notification) {
            if (isset($notification['created_at']) && $notification['created_at'] > $readAt) {
                $unreadCount++;
            }
        }

        return $unreadCount;
    }

    /**
     * Get notification settings
     */
    public function getSettings()
    {
        $settings = [
            'warning_days' => (int) ApplicationSetting::getValue('warning_days', 30),
            'critical_days' => (int) ApplicationSetting::getValue('critical_days', 7),
            'urgent_days' => (int) ApplicationSetting::getValue('expiry.urgent_days', 3),
            'auto_refresh' => true,
            'refresh_interval' => 300000, // 5 minutes in milliseconds
        ];

        return response()->json($settings);
    }
}
