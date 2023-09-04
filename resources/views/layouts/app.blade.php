<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta content="{{ csrf_token() }}" name="csrf-token">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link href="https://fonts.bunny.net" rel="preconnect">
	<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

	{{-- @vite(["resources/css/app.css", "resources/js/app.js"]) --}}
	<script src="https://cdn.tailwindcss.com"></script>
	<script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="font-sans antialiased" x-data="{{ $xData }}">
	<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
		@include('layouts.navigation')

		<!-- Page Heading -->
		@if (isset($header))
			<header class="bg-white shadow dark:bg-gray-800">
				<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
					{{ $header }}
				</div>
			</header>
		@endif

		<!-- Page Content -->
		<main>
			{{ $slot }}
		</main>
	</div>
</body>

</html>
