@props([
    'title' => config('app.name'),
    'description' => null,
    'image' => null,
    'canonical' => null,
])

<title>{{ $title }}</title>
@if ($description)
    <meta name="description" content="{{ $description }}">
    <meta property="og:description" content="{{ $description }}">
@endif
<meta property="og:title" content="{{ $title }}">
<meta property="og:type" content="website">
@if ($image)
    <meta property="og:image" content="{{ $image }}">
@endif
@if ($canonical)
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:url" content="{{ $canonical }}">
@endif
