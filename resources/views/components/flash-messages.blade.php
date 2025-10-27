@if(session()->has('success'))
    <x-alert type="success" :title="session('success_title', __('Success!'))" :message="session('success')" />
@endif

@if(session()->has('error'))
    <x-alert type="error" :title="session('error_title', __('Something went wrong!'))" :message="session('error')" />
@endif

@if(session()->has('warning'))
    <x-alert type="warning" :title="session('warning_title', __('Warning!'))" :message="session('warning')" />
@endif

@if(session()->has('info'))
    <x-alert type="info" :title="session('info_title', __('Information'))" :message="session('info')" />
@endif
