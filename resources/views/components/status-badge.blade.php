@props(['status' => 'default', 'text' => ''])

@php
$statusClasses = [
    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900 dark:text-yellow-300',
    'approved' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900 dark:text-green-300',
    'rejected' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900 dark:text-red-300',
    'active' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900 dark:text-blue-300',
    'inactive' => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-900 dark:text-gray-300',
    'default' => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-900 dark:text-gray-300'
];

$class = $statusClasses[$status] ?? $statusClasses['default'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $class }}">
    {{ $text ?: ucfirst($status) }}
</span>