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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ApplicationSetting::class);

        $settings = ApplicationSetting::orderBy('category')
            ->orderBy('key')
            ->get();

        // Group settings by category
        $settingsByCategory = $settings->groupBy('category');

        return view('application-settings.index', compact('settingsByCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', ApplicationSetting::class);

        $categories = ApplicationSetting::distinct()
            ->pluck('category')
            ->sort();

        return view('application-settings.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', ApplicationSetting::class);

        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:application_settings,key',
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json,text',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Validate value based on type
        $this->validateValueByType($request->value, $request->type);

        $validated['is_active'] = $request->has('is_active');

        ApplicationSetting::create($validated);

        return redirect()->route('application-settings.index')
            ->with('success', 'Setting berhasil dibuat.');
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
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('application_settings', 'key')->ignore($applicationSetting->id),
            ],
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json,text',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Validate value based on type
        $this->validateValueByType($request->value, $request->type);

        $validated['is_active'] = $request->has('is_active');

        // Clear cache for old key if key changed
        if ($applicationSetting->key !== $validated['key']) {
            \Illuminate\Support\Facades\Cache::forget("app_setting_{$applicationSetting->key}");
        }

        $applicationSetting->update($validated);

        // Clear cache for new key
        \Illuminate\Support\Facades\Cache::forget("app_setting_{$validated['key']}");

        return redirect()->route('application-settings.index')
            ->with('success', 'Setting berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationSetting $applicationSetting)
    {
        Gate::authorize('delete', $applicationSetting);

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget("app_setting_{$applicationSetting->key}");

        $applicationSetting->delete();

        return redirect()->route('application-settings.index')
            ->with('success', 'Setting berhasil dihapus.');
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
                if (! is_numeric($value)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'value' => 'Value harus berupa angka untuk tipe integer.',
                    ]);
                }
                break;
            case 'boolean':
                if (! in_array(strtolower($value), ['true', 'false', '1', '0', 'yes', 'no'])) {
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
