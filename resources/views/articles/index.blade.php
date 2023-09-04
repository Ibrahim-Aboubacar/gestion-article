<x-app-layout xData='{
		viewType: "grid",
		filterOpen: {{ $filtre }},
		filter : {{ $filtre }},
		articles : {!! $articles !!},
		deleteAction : function (id){
			document.getElementById("article_delete_"+id).submit()
		},
		initViewType: function (){
			console.log(this.articles[1]);
			if(localStorage.getItem("viewtype") == "list" || localStorage.getItem("viewtype") == "grid"){
				this.viewType = localStorage.getItem("viewtype")
			} else {
				localStorage.setItem("viewtype", "grid")
				this.viewType = "grid"
			}
		},
		toggleView: function (){
			if(localStorage.getItem("viewtype") == "list"){
				localStorage.setItem("viewtype", "grid")
				this.viewType = "grid"
			} else {
				localStorage.setItem("viewtype", "list")
				this.viewType = "list"
			}
		},
		subCategories : {!! $subCategories !!},
		categories: {!! $categories !!},
		curentCategory: {{ $curentCategory }},
		curentSubCategory: {{ $curentSubCategory }},
		setSubCategory : function () {
			subCatId = this.subCategories.filter((el) => el.category_id == this.curentCategory).filter((el) => el.id == this.curentSubCategory)
			if(subCatId.length){
				window.document.getElementById("subCategory_"+subCatId[0].id).selected = true;
			}
		},
		setCategory: function (){
			setTimeout(()=>{
				catId = this.categories.filter((el) => el.id == this.curentCategory)[0].id
				window.document.getElementById("category_"+catId).selected = true;
				this.setSubCategory();
			}, 50 )
		},
		updateCategory: function ($el) {this.curentCategory = $el.value;}
	}'>
	<x-slot name="header">
		<div class="flex leading-tight text-gray-800 dark:text-gray-200" x-init="initViewType()">
			<h2 class="flex-1 text-xl font-semibold">
				{{ __('Articles (' . $count . ')') }}
			</h2>
			<div class="flex items-center justify-center p-0">
				<button id="listView" title="Basculer vers grille" x-on:click="toggleView()" x-show="viewType == 'list'">
					<svg class="w-8 stroke-gray-800 dark:stroke-gray-200" fill="none" viewBox="0 0 24 24"
						xmlns="http://www.w3.org/2000/svg">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
						<g id="SVGRepo_iconCarrier">
							<path
								d="M3 9.5H21M3 14.5H21M8 4.5V19.5M6.2 19.5H17.8C18.9201 19.5 19.4802 19.5 19.908 19.282C20.2843 19.0903 20.5903 18.7843 20.782 18.408C21 17.9802 21 17.4201 21 16.3V7.7C21 6.5799 21 6.01984 20.782 5.59202C20.5903 5.21569 20.2843 4.90973 19.908 4.71799C19.4802 4.5 18.9201 4.5 17.8 4.5H6.2C5.0799 4.5 4.51984 4.5 4.09202 4.71799C3.71569 4.90973 3.40973 5.21569 3.21799 5.59202C3 6.01984 3 6.57989 3 7.7V16.3C3 17.4201 3 17.9802 3.21799 18.408C3.40973 18.7843 3.71569 19.0903 4.09202 19.282C4.51984 19.5 5.07989 19.5 6.2 19.5Z"
								stroke-width="2"></path>
						</g>
					</svg>
				</button>
				<button id="gridView" title="Basculer vers liste" x-on:click="toggleView()" x-show="viewType == 'grid'">
					<svg class="w-8 stroke-gray-800 dark:stroke-gray-200" fill="none" viewBox="0 0 24 24"
						xmlns="http://www.w3.org/2000/svg">
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
						<g id="SVGRepo_iconCarrier">
							<path
								d="M14 5.6C14 5.03995 14 4.75992 14.109 4.54601C14.2049 4.35785 14.3578 4.20487 14.546 4.10899C14.7599 4 15.0399 4 15.6 4H18.4C18.9601 4 19.2401 4 19.454 4.10899C19.6422 4.20487 19.7951 4.35785 19.891 4.54601C20 4.75992 20 5.03995 20 5.6V8.4C20 8.96005 20 9.24008 19.891 9.45399C19.7951 9.64215 19.6422 9.79513 19.454 9.89101C19.2401 10 18.9601 10 18.4 10H15.6C15.0399 10 14.7599 10 14.546 9.89101C14.3578 9.79513 14.2049 9.64215 14.109 9.45399C14 9.24008 14 8.96005 14 8.4V5.6Z"
								stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
							<path
								d="M4 5.6C4 5.03995 4 4.75992 4.10899 4.54601C4.20487 4.35785 4.35785 4.20487 4.54601 4.10899C4.75992 4 5.03995 4 5.6 4H8.4C8.96005 4 9.24008 4 9.45399 4.10899C9.64215 4.20487 9.79513 4.35785 9.89101 4.54601C10 4.75992 10 5.03995 10 5.6V8.4C10 8.96005 10 9.24008 9.89101 9.45399C9.79513 9.64215 9.64215 9.79513 9.45399 9.89101C9.24008 10 8.96005 10 8.4 10H5.6C5.03995 10 4.75992 10 4.54601 9.89101C4.35785 9.79513 4.20487 9.64215 4.10899 9.45399C4 9.24008 4 8.96005 4 8.4V5.6Z"
								stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
							<path
								d="M4 15.6C4 15.0399 4 14.7599 4.10899 14.546C4.20487 14.3578 4.35785 14.2049 4.54601 14.109C4.75992 14 5.03995 14 5.6 14H8.4C8.96005 14 9.24008 14 9.45399 14.109C9.64215 14.2049 9.79513 14.3578 9.89101 14.546C10 14.7599 10 15.0399 10 15.6V18.4C10 18.9601 10 19.2401 9.89101 19.454C9.79513 19.6422 9.64215 19.7951 9.45399 19.891C9.24008 20 8.96005 20 8.4 20H5.6C5.03995 20 4.75992 20 4.54601 19.891C4.35785 19.7951 4.20487 19.6422 4.10899 19.454C4 19.2401 4 18.9601 4 18.4V15.6Z"
								stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
							<path
								d="M14 15.6C14 15.0399 14 14.7599 14.109 14.546C14.2049 14.3578 14.3578 14.2049 14.546 14.109C14.7599 14 15.0399 14 15.6 14H18.4C18.9601 14 19.2401 14 19.454 14.109C19.6422 14.2049 19.7951 14.3578 19.891 14.546C20 14.7599 20 15.0399 20 15.6V18.4C20 18.9601 20 19.2401 19.891 19.454C19.7951 19.6422 19.6422 19.7951 19.454 19.891C19.2401 20 18.9601 20 18.4 20H15.6C15.0399 20 14.7599 20 14.546 19.891C14.3578 19.7951 14.2049 19.6422 14.109 19.454C14 19.2401 14 18.9601 14 18.4V15.6Z"
								stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
						</g>
					</svg>
				</button>
			</div>
		</div>
	</x-slot>
	@if (Session::has('success'))
		<div class="m-7 mx-auto max-w-7xl sm:px-6 lg:px-8">
			<div class="mx-4 flex items-center justify-center rounded-lg bg-green-600 py-4 text-green-200 sm:px-6 lg:px-8">
				<p class="grow px-3">{{ Session::get('success') }}</p>
				<span class="mr-4 cursor-pointer px-3 py-2" x-on:click="$el.parentNode.parentNode.remove()">x</span>
			</div>
		</div>
	@endif
	@if (Session::has('error'))
		<div class="m-7 mx-auto max-w-7xl sm:px-6 lg:px-8">
			<div class="mx-4 flex items-center justify-center rounded-lg bg-red-500 py-4 text-red-200 sm:px-6 lg:px-8">
				<p class="grow px-3">{{ Session::get('error') }}</p>
				<span class="mr-4 cursor-pointer px-3 py-2 hover:scale-125" x-on:click="$el.parentNode.parentNode.remove()">x</span>
			</div>
		</div>
	@endif
	<div class="mx-auto max-w-7xl border-t border-gray-500 pb-5 sm:py-12">
		<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
			<div class="overflow-hidden_ flex bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
				<div class="grow p-6 text-gray-900 dark:text-gray-100">
					<h1 class="font-semibold text-gray-900 dark:text-gray-200">
						{{ __('Liste des Articles') }}
					</h1>
				</div>

				<div class="m-4">
					<button :class="filterOpen ? 'bg-green-600 hover:bg-green-800' : 'bg-indigo-600 hover:bg-indigo-800'"
						class="block rounded-md px-3 py-2 text-center font-semibold text-white sm:px-7" type="button"
						x-on:click='filterOpen = ! filterOpen'>Filtre</button>
				</div>
				<div class="mr-4 mt-4">
					<button
						class="block rounded-md bg-indigo-600 px-3 py-2 text-center font-semibold text-white hover:bg-indigo-800 sm:px-7"
						type="button" x-on:click='window.location = "{{ route('articles.create') }}" '>Creer <span
							class="hidden sm:inline">un article</span></button>
				</div>
			</div>
			<form action=""
				class="mt-4 flex flex-col justify-between gap-2 bg-white p-4 shadow-sm dark:bg-gray-800 sm:flex-row sm:rounded-lg"
				method="get" x-cloak x-init="$el.removeAttribute('x-cloak')" x-show="filterOpen {{ $filtre == 'false' ? '|| filter' : '' }}"
				x-transition>
				<div class="flex gap-2">
					<!-- Category -->
					<div class="grow">
						<select autocomplete="category"
							@error('category') 
									:class="'ring-red-500'" 
								@else 
									:class="'ring-gray-300'" 
								@enderror
							class="py-1.5_ block w-full rounded-md border-0 bg-transparent px-7 py-2 text-gray-900 shadow-sm ring-1 ring-inset focus:bg-gray-100 focus:text-gray-800 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:text-gray-200 sm:max-w-xs sm:text-sm sm:leading-6"
							id="categorySelect" name="category" x-init="setCategory()" x-on:change="updateCategory($el)">
							<template x-for="category in categories">
								<option :value="category.id" :id="'category_' + category.id"
									x-bind:selected="category.id == curentCategory ? 'true' : 'false'" x-text="category.name"></option>
							</template>
						</select>
					</div>
					<!-- Sub category -->
					<div class="grow">
						<select {{-- x-init="setSubCategory()" --}} autocomplete="sub_category"
							@error('sub_category') 
									:class="'ring-red-500'" 
								@else 
									:class="'ring-gray-300'" 
								@enderror
							class="py-1.5_ block w-full rounded-md border-0 bg-transparent px-7 py-2 text-gray-900 shadow-sm ring-1 ring-inset focus:bg-gray-100 focus:text-gray-800 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:text-gray-200 sm:max-w-xs sm:text-sm sm:leading-6"
							name="sub_category">
							<template x-for="subCat in subCategories.filter((el) => el.category_id == curentCategory)">
								<option :value="subCat.id" :id="'subCategory_' + subCat.id" x-text="subCat.name">
								</option>
							</template>
						</select>
					</div>
				</div>
				<div>
					<div class="mt-3 flex justify-end gap-4 sm:mt-0">
						<button class="block rounded-md bg-red-600 px-2 py-2 text-center font-semibold text-white hover:bg-red-800"
							type="submit" x-on:click.prevent="location = '{{ route('articles.index') }}'" x-show="filter">Suprimer <span
								class="hidden sm:inline">le filtre</span></button>
						<button
							class="block rounded-md bg-indigo-600 px-4 py-2 text-center font-semibold text-white hover:bg-indigo-800 sm:px-7"
							type="submit">Filtre</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<template x-if="viewType == 'list'">
		<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
			<div class="mx-4 rounded-xl bg-white pt-8 dark:bg-gray-800 sm:mx-0">
				<div class="max-w-7xl">
					<div class="px-4">
						<p class="text-sm text-gray-700 dark:text-gray-400">La liste de tout les articles incent leur
							nom, description,
							categorie...</p>
						<div class="mt-8 flow-root">
							<div class="gc gl adi buw cte -mx-6 -my-2 overflow-x-auto">
								<div class="lv ts avo inline-block min-w-full px-6 py-2 align-middle">
									<table class="ts acc ach min-w-full">
										<thead>
											<tr class="border-b border-gray-500 dark:border-gray-600 dark:text-gray-200">
												<th class="cgi text-left" scope="col">Nom</th>
												<th class="hidden text-left md:table-cell" scope="col">Categorie</th>
												<th class="text-left" scope="col">Sous-categorie</th>
												<th class="ab atm aue cgp text-left" scope="col"><span class="t">Actions</span></th>
											</tr>
										</thead>
										<tbody class="acc acg">
											<template x-for="article in articles">
												<tr class="border-b border-gray-400 py-10 dark:border-gray-600">
													<td class="py-4 font-semibold dark:text-gray-200" x-text="article.name">Iphone XR</td>
													<td class="hidden py-4 dark:text-gray-300 md:table-cell" x-text="article.category">Categorie</td>
													<td class="py-4 dark:text-gray-300" x-text="article.subCategory">Smpartphone Iphone</td>
													<td class="flex flex-col justify-center gap-3 py-4 dark:text-indigo-300 md:flex-row">
														<span class="inline-block">
															<a :href="article.show_link"
																class="group inline-block rounded-md border border-teal-500 p-1 dark:text-teal-300 hover:dark:bg-teal-500 hover:dark:text-teal-500">
																<svg class="w-6 stroke-teal-500 group-hover:dark:stroke-teal-200" fill="none" viewBox="0 0 24 24"
																	xmlns="http://www.w3.org/2000/svg">
																	<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
																	<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
																	<g id="SVGRepo_iconCarrier">
																		<g id="style=linear">
																			<g id="eye-open">
																				<path
																					d="M15 12C15 13.6592 13.6592 15 12 15C10.3408 15 9 13.6592 9 12C9 10.3408 10.3408 9 12 9C13.6592 9 15 10.3408 15 12Z"
																					id="vector" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
																				<path
																					d="M12 19.27C15.53 19.27 18.82 17.4413 21.11 14.2764C22.01 13.0368 22.01 10.9532 21.11 9.71356C18.82 6.54861 15.53 4.71997 12 4.71997C8.46997 4.71997 5.17997 6.54861 2.88997 9.71356C1.98997 10.9532 1.98997 13.0368 2.88997 14.2764C5.17997 17.4413 8.46997 19.27 12 19.27Z"
																					id="vector_2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
																			</g>
																		</g>
																	</g>
																</svg>
															</a>
														</span>
														<apan class="inline-block">
															<a :href="article.edit_link"
																class="group inline-block rounded-md border border-indigo-500 p-1 hover:dark:bg-indigo-500 hover:dark:text-indigo-500">
																<svg class="w-6 stroke-indigo-500 group-hover:dark:stroke-indigo-200" fill="none" stroke-width="2"
																	stroke="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
																	<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
																	<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																		stroke="#CCCCCC"></g>
																	<g id="SVGRepo_iconCarrier">
																		<path
																			d="M12 5H9C7.11438 5 6.17157 5 5.58579 5.58579C5 6.17157 5 7.11438 5 9V15C5 16.8856 5 17.8284 5.58579 18.4142C6.17157 19 7.11438 19 9 19H15C16.8856 19 17.8284 19 18.4142 18.4142C19 17.8284 19 16.8856 19 15V12M9.31899 12.6911L15.2486 6.82803C15.7216 6.36041 16.4744 6.33462 16.9782 6.76876C17.5331 7.24688 17.5723 8.09299 17.064 8.62034L11.2329 14.6702L9 15L9.31899 12.6911Z"
																			stroke-linecap="round" stroke-linejoin="round"></path>
																	</g>
																</svg>
															</a>
														</apan>
														{{-- Effacer --}}
														<form :action="article.destroy_link" :id="'article_delete_' + article.id" class="group inline-block"
															method="post">
															@method('DELETE') @csrf
															<button
																class="inline-block rounded-md border border-red-500 p-1 group-hover:dark:bg-red-500 group-hover:dark:text-red-200"
																x-on:click.prevent='confirm("Etes-vous sur de vouloir suprime cet arclicle ?") ? deleteAction(article.id) : ""'>
																<svg class="w-6 stroke-red-500 group-hover:dark:stroke-red-200" fill="none" viewBox="0 0 24 24"
																	xmlns="http://www.w3.org/2000/svg">
																	<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
																	<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
																	<g id="SVGRepo_iconCarrier">
																		<path
																			d="M18 6L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M14 10V17M10 10V17"
																			stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
																	</g>
																</svg>
															</button>
														</form>
													</td>
												</tr>
											</template>
											<template x-if="{{ $count }} == 0">
												<tr class="py-10">
													<td class="py-4 text-center font-semibold dark:text-gray-400" colspan="6" x-text="'Pas d\'article'">
													</td>
												</tr>
											</template>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</template>

	<template x-if="viewType == 'grid'">
		<div class="mx-auto grid max-w-7xl grid-cols-2 px-4 sm:gap-4 sm:px-6 md:grid-cols-4 lg:px-8">
			<template x-for="article in articles">
				<div x-data="{ singleView: true }"
					class="group m-2 gap-3 overflow-hidden rounded-lg border border-gray-300 bg-white shadow-md shadow-gray-200 dark:border-gray-500 dark:bg-gray-700 dark:shadow-gray-900 sm:m-0">
					<span
						class0="hidden shadow-gray-300 shadow-gray-400 shadow-gray-500 shadow-gray-600 shadow-gray-700 shadow-gray-800 shadow-gray-900"></span>
					<a :href="article.show_link" x-data="{ singleView: true }" x-on:mouseleave="singleView = true">
						<div class="ige aspect-square w-full overflow-hidden">
							<img x-show="singleView" x-on:mouseover="article.images.length > 1 ? singleView = false : ''" x-transition
								x-on:touchstart=" article.images.length > 1 ? singleView = false : ''" x-transition-opacity
								:src="article.image" class="h-full w-full object-cover transition-all group-hover:scale-105"
								loading="lazy">
							<div x-show="!singleView" x-on:mouseleave="singleView = true" x-on:touchstart="singleView = true" x-transition
								x-transition-opacity :class="article.images.length >= 3 && article.images.length < 5 ? 'grid-rows-2' : ''"
								:class="article.images.length == 5 ? 'grid-rows-3' : ''"
								:class="article.images.length <= 2 ? 'grid-rows-1' : ''"
								class="imgs max-w-1/2 grid-rows-3_ grid h-full w-full grid-cols-2 gap-1 bg-gray-700">
								<template x-for="image in article.images" :key="image.id">
									<template
										x-if="((article.images.length == 4 && image.path != article.image) || article.images.length == 3 || (article.images.length == 5 && article.images[0].path != image.path)) || article.images.length == 2">
										<div class="overflow-hidden"
											:class="article.images[article.images.length - 1].path == image.path && article.images.length >= 3 && article.images
											    .length != 5 ? 'col-span-2' :
											    ''">
											<img
												class="block aspect-square min-h-full w-full scale-100 cursor-pointer object-cover transition-all hover:scale-125"
												:src="image.path" :alt="image.name">
										</div>
									</template>
								</template>
							</div>
						</div>
						<div class="leText">
							<h4 class="px-3 pt-2 text-xs font-semibold dark:text-gray-200 sm:text-base" x-text="article.name"></h4>
							<p :title="'De la categorie \'' + article.category + '\'.'"
								class="px-3 pb-3 text-xs dark:text-gray-400 sm:text-sm" class="" x-text="article.subCategory"></p>
						</div>
					</a>
				</div>
			</template>
		</div>
	</template>

	<div class="py-8">
		<div class="mt-4 flex items-center justify-center">
			{{ $pagination->links() }}
		</div>
	</div>
</x-app-layout>
