@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-sidebar-green']) }}>{{ $message }}</p>
@enderror
