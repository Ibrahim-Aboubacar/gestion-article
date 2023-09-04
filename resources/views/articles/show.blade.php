<x-app-layout xData=''>
	<x-slot name="header">
		<div class="flex items-center justify-center leading-tight text-gray-800 dark:text-gray-200">
			<h2 class="flex-1 grow text-base font-semibold sm:text-xl">
				{{ $article->name }}
			</h2>
			<div class="">
				<button class="block rounded-md bg-indigo-600 px-5 py-2 text-center font-semibold text-white hover:bg-indigo-800"
					type="button" x-on:click='window.location = "{{ route('articles.create') }}" '>Créer <span
						class="hidden sm:inline">un article</span></button>
			</div>
		</div>
	</x-slot>
	@if (Session::has('success'))
		<div class="mx-auto mt-10 max-w-7xl sm:px-6 lg:px-8">
			<div class="mx-4 flex items-center justify-center rounded-lg bg-green-600 py-4 text-green-200 sm:px-6 lg:px-8">
				<p class="grow px-3">{{ Session::get('success') }}</p>
				<span class="mr-4 cursor-pointer px-3 py-2" x-on:click="$el.parentNode.remove()">x</span>
			</div>
		</div>
	@endif
	@if (Session::has('error'))
		<div class="mx-auto mt-10 max-w-7xl sm:px-6 lg:px-8">
			<div class="mx-4 flex items-center justify-center rounded-lg bg-red-500 py-4 text-red-200 sm:px-6 lg:px-8">
				<p class="grow px-3">{{ Session::get('error') }}</p>
				<span class="mr-4 cursor-pointer px-3 py-2 hover:scale-125" x-on:click="$el.parentNode.remove()">x</span>
			</div>
		</div>
	@endif
	<div class="mx-auto mt-8 max-w-7xl"
		x-data='{
			deleteAction : function (){
				document.getElementById("DeleteForm").submit()
			}
		}'>
		<div
			class="mx-4 flex flex-col justify-center overflow-hidden rounded-b-2xl rounded-t-2xl bg-white dark:bg-gray-800 md:flex-row">
			<div class="img aspect-square w-full grow-0 overflow-hidden md:w-1/2" x-data="{ singleView: true }">
				<img x-show="singleView" x-on:mouseover=" {{ count($article->images) }} > 1 ? singleView = false : ''"
					x-on:touchstart=" {{ count($article->images) }} > 1 ? singleView = false : ''" x-transition x-transition-opacity
					src="{{ $article->getImageUrl() }}" alt="Image {{ $article->name }}"
					class="block min-h-full w-full scale-100 object-cover">
				@if (count($article->images) > 1)
					<div x-show="!singleView " x-on:mouseleave="singleView = true" x-on:touchstart="singleView = true" x-transition
						x-transition-opacity @if (count($article->images) >= 3 && count($article->images) < 5) :class="'grid-rows-2'" @endif
						@if (count($article->images) == 5) :class="'grid-rows-3'" @endif
						@if (count($article->images) <= 2) :class="'grid-rows-1'" @endif
						class="imgs max-w-1/2 grid-rows-3_ grid h-full w-full grid-cols-2 gap-1 bg-gray-700">
						@forelse ($article->images as $image)
							@if (count($article->images) == 4 || (count($article->images) == 5 && $article->images[0]->path != $image->getPath()) or
									count($article->images))
								<div class="@if ($loop->last) col-span-2_ @endif overflow-hidden"
									@if (
										($article->images[count($article->images) - 1]->getPath() == $image->getPath() &&
											count($article->images) >= 3 &&
											(count($article->images) != 4 && $loop->last)) ||
											(count($article->images) == 5 && $loop->last)) :class="'col-span-2'" @endif>
									<img
										class="block aspect-square min-h-full w-full scale-100 cursor-pointer object-cover transition-all hover:scale-125"
										src="{{ $image->getPath() }}" alt="{{ $image->name }}">
								</div>
							@endif
						@empty
						@endforelse
					</div>
				@endif
			</div>
			<div class="flex grow flex-col p-4 md:text-lg">
				<div class="mx-4 grow">
					<div class="grow py-2 text-gray-800 dark:text-gray-200">
						<h2>Nom: <br> <span class="font-semibold">{{ $article->name }}</span></h2>
					</div>
					<div class="grow py-2 text-gray-800 dark:text-gray-200">
						<h2>Categorie: <br>
							<span class="font-semibold">{{ $article->sub_category->category->name }}</span> / <span
								class="font-semibold">{{ $article->sub_category->name }}</span>
						</h2>
					</div>
					<div class="grow py-2 text-gray-800 dark:text-gray-200">
						<h2>description: </h2>
						<p class="font-semibold">{{ $article->description }}</p>
					</div>
				</div>
				{{-- actions --}}
				<div class="mx-4 mt-4 flex justify-stretch gap-4 text-xs sm:justify-end">
					{{-- Retour --}}
					<button
						class="block rounded-md bg-indigo-600 px-2 py-2 text-center font-semibold text-white hover:bg-indigo-700 sm:px-4 md:text-base"
						title="Retourner a la page precedente" type="button" x-on:click.prevent="window.history.back()">Retour</button>
					{{-- Delete --}}
					<form action="{{ route('articles.destroy', ['id' => $article->id]) }}" id="DeleteForm" method="post">
						@csrf
						@method('DELETE')
						<button
							class="flex items-center justify-center gap-1 rounded-md bg-red-600 px-2 py-2 text-center font-semibold text-white hover:bg-red-700 md:text-base"
							title="Suprimer '{{ $article->name }}'" type="submit"
							x-on:click.prevent='confirm("Etes-vous sur de vouloir suprime cet arclicle ?") ? deleteAction() : ""'>
							<svg class="w-5 stroke-white md:w-6" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
								<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
								<g id="SVGRepo_iconCarrier">
									<path
										d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M14 10V17M10 10V17"
										stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
								</g>
							</svg>
							<span>Suprimer</span>
						</button>
					</form>
					{{-- modifier --}}
					<button
						class="flex items-center justify-center gap-2 rounded-md bg-teal-600 px-2 py-2 text-center font-semibold text-white hover:bg-teal-700 sm:px-3 md:text-base"
						type="submit" title="Modifier '{{ $article->name }}'"
						x-on:click='location = "{{ route('articles.edit', ['id' => $article->id]) }}"'>
						<svg class="fill-white_ w-5 stroke-white md:w-6" fill="none" stroke-width="2" stroke="#000000"
							viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
							<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="#CCCCCC">
							</g>
							<g id="SVGRepo_iconCarrier">
								<path
									d="M12 5H9C7.11438 5 6.17157 5 5.58579 5.58579C5 6.17157 5 7.11438 5 9V15C5 16.8856 5 17.8284 5.58579 18.4142C6.17157 19 7.11438 19 9 19H15C16.8856 19 17.8284 19 18.4142 18.4142C19 17.8284 19 16.8856 19 15V12M9.31899 12.6911L15.2486 6.82803C15.7216 6.36041 16.4744 6.33462 16.9782 6.76876C17.5331 7.24688 17.5723 8.09299 17.064 8.62034L11.2329 14.6702L9 15L9.31899 12.6911Z"
									stroke-linecap="round" stroke-linejoin="round"></path>
							</g>
						</svg>
						<span>Modifer</span>
					</button>
				</div>
			</div>
		</div>
		<div class="py-10"></div>
</x-app-layout>
