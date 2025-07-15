@props([
    'title' => '',
    'method' => 'POST',
    'action' => '',
    'enctype' => null,
    'backUrl' => null,
    'submitLabel' => 'Simpan',
    'showReset' => false,
    'breadcrumbs' => []
])

<x-admin.page-header 
    :title="$title"
    :breadcrumbs="$breadcrumbs"
/>

<form method="{{ $method === 'GET' ? 'GET' : 'POST' }}" action="{{ $action }}" 
      {{ $enctype ? 'enctype=' . $enctype : '' }} class="space-y-6">
    @if($method !== 'GET' && $method !== 'POST')
        @method($method)
    @endif
    
    @if($method !== 'GET')
        @csrf
    @endif
    
    {{ $slot }}
    
    <x-admin.form-actions 
        :back-url="$backUrl"
        :submit-label="$submitLabel"
        :show-reset="$showReset"
    />
</form>
