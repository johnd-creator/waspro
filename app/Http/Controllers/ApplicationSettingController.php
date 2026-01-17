<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ApplicationSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a hub/overview of all settings categories.
     * This is now a read-only dashboard that links to category-specific pages.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ApplicationSetting::class);

        $settings = ApplicationSetting::orderBy('category')
            ->orderBy('key')
            ->get();

        // Group settings by category
        $settingsByCategory = $settings->groupBy('category');

        // Define category routes for navigation
        $categoryRoutes = [
            'workflow' => route('workflow-settings.index'),
            'upload' => route('upload-settings.index'),
            'report' => route('report-settings.index'),
            'expiry' => route('expiry-settings.index'),
            // Security and System are managed here directly
        ];

        return view('application-settings.index', compact('settingsByCategory', 'categoryRoutes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationSetting $applicationSetting)
    {
        Gate::authorize('view', $applicationSetting);

        $setting = $applicationSetting;

        // Get related settings from the same category
        $relatedSettings = ApplicationSetting::where('category', $setting->category)
            ->where('id', '!=', $setting->id)
            ->orderBy('key')
            ->get();

        return view('application-settings.show', compact('setting', 'relatedSettings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationSetting $applicationSetting)
    {
        Gate::authorize('update', $applicationSetting);

        $setting = $applicationSetting;
        $categories = ApplicationSetting::distinct()
            ->pluck('category')
            ->sort();

        return view('application-settings.edit', compact('setting', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApplicationSetting $applicationSetting)
    {
        Gate::authorize('update', $applicationSetting);

        $validated = $request->validate([
            'value' => 'nullable|string',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Validate value based on type
        $this->validateValueByType($request->value, $applicationSetting->type);

        $validated['is_active'] = $request->has('is_active');

        // Clear cache for old key
        \Illuminate\Support\Facades\Cache::forget("app_setting_{$applicationSetting->key}");

        $applicationSetting->update($validated);

        return redirect()->route('application-settings.index')
            ->with('success', 'Setting berhasil diperbarui.');
    }

    /**
     * Clear all settings cache
     */
    public function clearCache()
    {
        Gate::authorize('update', ApplicationSetting::class);

        ApplicationSetting::clearCache();

        return redirect()->route('application-settings.index')
            ->with('success', 'Cache setting berhasil dibersihkan.');
    }

    /**
     * Validate value based on type
     */
    private function validateValueByType($value, $type)
    {
        if (empty($value)) {
            return;
        }

        switch ($type) {
            case 'integer':
                if (!is_numeric($value)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'value' => 'Value harus berupa angka untuk tipe integer.',
                    ]);
                }
                break;
            case 'boolean':
                if (!in_array(strtolower($value), ['true', 'false', '1', '0', 'yes', 'no'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'value' => 'Value harus berupa true/false, 1/0, atau yes/no untuk tipe boolean.',
                    ]);
                }
                break;
            case 'json':
                if (json_decode($value) === null && json_last_error() !== JSON_ERROR_NONE) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'value' => 'Value harus berupa JSON yang valid.',
                    ]);
                }
                break;
        }
    }
}
