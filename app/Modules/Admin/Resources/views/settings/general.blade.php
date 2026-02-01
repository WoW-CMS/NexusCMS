@extends('admin::settings.index')

@section('settings_content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">General Settings</h2>
        <p class="text-sm text-gray-600 mt-1">Configure basic site information and settings</p>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Site Name</label>
                <input type="text" value="NexusCMS" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Site URL</label>
                <input type="text" value="https://nexuscms.example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Site Description</label>
            <textarea rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">The ultimate World of Warcraft private server experience with custom content and balanced gameplay.</textarea>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Email</label>
                <input type="email" value="admin@nexuscms.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Time Zone</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>UTC</option>
                    <option>Europe/Madrid</option>
                    <option>America/New_York</option>
                    <option>America/Los_Angeles</option>
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date Format</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Y-m-d (2024-01-29)</option>
                    <option>d/m/Y (29/01/2024)</option>
                    <option>m/d/Y (01/29/2024)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Time Format</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>24-hour (14:30)</option>
                    <option>12-hour (2:30 PM)</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" id="registration" checked class="rounded border-gray-300">
            <label for="registration" class="text-sm text-gray-700">Allow new user registrations</label>
        </div>
    </div>
</div>
@endsection
