<x-app-layout xData=''>
	<x-slot name="header">
		<div class="flex leading-tight text-gray-800 dark:text-gray-200">
			<h2 class="flex-1 text-xl font-semibold">
				{{ __('Creer un nouvel article') }}
			</h2>
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
	<div class="mx-auto mt-8 max-w-7xl sm:px-6 lg:px-8"
		x-data='{
			subCategories : {!! $sub_categories !!},
			categories: {!! $categories !!},
			curentCategory: null,
			uploadedImages : [],
			currentImageCount: 0,
			imageLimit : {{ $imageLimit }},
			notification : {show: false, type: "success", message : ""},
			setCategory: function (){
				setTimeout(()=>{
					window.document.getElementById("category_"+this.categories[0].id).selected = true;
					this.curentCategory = this.categories[0].id
				}, 100 )
			},
			inputImageChanged: function (e){
				this.uploadedImages.length = [];
				this.notification.show = false;
				if(e.target.files.length + this.currentImageCount > this.imageLimit){
					this.notification.show = true;
					this.notification.type = "danger";
					imageRestCount = this.imageLimit - this.currentImageCount;
					this.notification.message = "Vous ne pouvez pas uploader plus de " + this.imageLimit + " images au total. Veuillez uploader (" + imageRestCount + ") images";
				} else {
					for(i = 0; i < e.target.files.length; i++){
						file = e.target.files[i]
						imageUrl = URL.createObjectURL(file)
						this.uploadedImages.push({
							id: imageUrl,
							path: imageUrl,
							size: file.size,
							name: file.name
						})
					}
					this.notification.show = true;
					this.notification.type = "success";
					this.notification.message = "Pour persister les nouvelles images, cliquer sur enregistrer."
				}
			},
			updateCategory: function ($el) {this.curentCategory = $el.value;
			} 
		}'>
		<div class="mx-4 rounded-t-2xl bg-white pt-1 dark:bg-gray-800">
			<div class="max-w-7xl">
				<div class="px-4">
					<p class="mt-6 text-sm text-gray-700 dark:text-gray-400">Veuillez renseigner les champs ci-dessous</p>
					<div class="-mx-6_ -my-2_ overflow-x-auto">
						<form action="{{ route('articles.store') }}" enctype="multipart/form-data" method="post">
							@csrf
							<div class="mt-3">
								{{-- Input nom article --}}
								<div class="flex flex-col gap-3 sm:flex-row">
									{{-- Nom --}}
									<div class="flex-auto grow">
										<label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200"
											for="name">Nom:</label>
										<div class="mt-2">
											<div
												@error('name') 
													:class="'ring-red-500'" 
												@else 
													:class="'ring-gray-300'" 
												@enderror
												class="flex rounded-md shadow-sm ring-1 ring-inset focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
												<input autocomplete="name"
													class="block flex-1 border-0 bg-transparent p-1.5 text-gray-900 placeholder:text-gray-500 focus-within:outline-0 focus:ring-0 focus-visible:outline-none dark:text-gray-200 sm:text-sm sm:leading-6"
													id="name" name="name" placeholder="Iphone 15 Ultra" type="text" value="{{ old('name') }}">
											</div>
											@error('name')
												<p class="text-sm text-red-500">{{ $message }}</p>
											@enderror
										</div>
									</div>
									{{-- category --}}
									<div class="grow">
										<label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200"
											for="categorySelect">Categorie:</label>
										<div class="mt-2">
											<select autocomplete="category"
												@error('category') 
													:class="'ring-red-500'" 
												@else 
													:class="'ring-gray-300'" 
												@enderror
												class="block w-full rounded-md border-0 bg-transparent p-1.5 text-gray-900 shadow-sm ring-1 ring-inset focus:bg-gray-100 focus:text-gray-800 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:text-gray-200 sm:max-w-xs sm:leading-6"
												name="category" id="categorySelect" x-init="setCategory()" x-on:change="updateCategory($el)">
												<template x-for="category in categories">
													<option :value="category.id" :id="'category_' + category.id" x-text="category.name"></option>
												</template>
											</select>
										</div>
										@error('category')
											<p class="text-sm text-red-500">{{ $message }}</p>
										@enderror
									</div>
									{{-- Sous category --}}
									<div class="grow">
										<label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200" for="sub_category">Sous
											Categorie:</label>
										<div class="mt-2">
											<select {{-- x-init="setSubCategory()" --}} autocomplete="sub_category"
												@error('sub_category') 
													:class="'ring-red-500'" 
												@else 
													:class="'ring-gray-300'" 
												@enderror
												class="block w-full rounded-md border-0 bg-transparent p-1.5 text-gray-900 shadow-sm ring-1 ring-inset focus:bg-gray-100 focus:text-gray-800 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:text-gray-200 sm:leading-6"
												id="sub_category" name="sub_category">
												<template x-for="subCat in subCategories.filter((el) => el.category_id == curentCategory)">
													<option :value="subCat.id" :id="'subCategory_' + subCat.id" x-text="subCat.name">
													</option>
												</template>
											</select>
										</div>
										@error('sub_category')
											<p class="text-sm text-red-500">{{ $message }}</p>
										@enderror
									</div>
								</div>
								{{-- description --}}
								<div class="mt-4 w-full">
									<label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200"
										for="description">Description:</label>

									<div class="mt-2">
										<textarea
										 @error('description') 
												:class="'ring-red-500'" 
											@else 
												:class="'ring-gray-300'" 
											@enderror
										 class="block w-full rounded-md border-0 bg-transparent p-1.5 text-gray-900 shadow-sm ring-1 ring-inset placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-indigo-600 focus-visible:outline-none dark:text-gray-300 sm:leading-6"
										 id="description" name="description" rows="5">{{ old('description') }}</textarea>
									</div>
									@error('description')
										<p class="mb-3 text-sm text-red-500">{{ $message }}</p>
									@else
										<p class="mb-3 text-xs leading-6 text-gray-400">Une brieve description de l'article</p>
									@enderror
								</div>
								{{-- uploaded images --}}
								<div class="images mt-8 grid grid-cols-2 gap-2 sm:grid-cols-2 md:grid-cols-4">
									<template x-for="imageFile in uploadedImages"></template>
									<div :id="'image_' + imageFile.id"
										class="relative w-full overflow-hidden rounded border border-gray-400 dark:border-gray-600">
										<div class="image aspect-square w-full overflow-hidden">
											<img :src="imageFile.path" class="h-full w-full object-cover" :alt="imageFile.name">
										</div>
										<div class="imfos w-full p-3 text-sm text-gray-800 dark:text-gray-300">
											<p>Nom: <span clas="text-gray-950 dark:text-white" x-text="imageFile.name"></span></p>
											<p>Taille: <span class="text-gray-950 dark:text-white"
													x-text="(imageFile.size / 1024 > 1) ? (imageFile.size / 1024).toFixed(2) + 'Ko' : (imageFile.size / 1024 / 1024).toFixed(2) + 'Mo'"></span>
											</p>
											<div class="flex gap-3">

											</div>
										</div>
									</div>
									</template>
								</div>
								<div x-show="notification.show" x-transition class="mx-auto mt-10 max-w-7xl sm:px-6 lg:px-8">
									<div :class="notification.type == 'danger' ? 'bg-red-500 text-red-200' : 'bg-green-600 text-green-200'"
										class="mx-4 flex items-center justify-center rounded-lg py-4 sm:px-6 lg:px-8">
										<p class="grow px-3" x-text="notification.message"></p>
										<span class="mr-4 cursor-pointer px-3 py-2 hover:scale-125" x-on:click="notification.show = false">x</span>
									</div>
								</div>
								{{-- Image bouton --}}
								<div class="mt-4">
									<label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200" for="imagesInput">
										Image de l'article:</label>
									<div class="mt-2">
										<input accept="image/png, image/jpeg" class="hidden" id="imagesInput" x-on:change="inputImageChanged($event)"
											name="images[]" multiple type="file">
										<button
											@error('image') 
												:class="'ring-red-500'" 
											@else 
												:class="'ring-gray-300'" 
											@enderror
											:class="currentImageCount == imageLimit ? 'opacity-50' : ''"
											class="opacity-50_ flex w-full items-center justify-center gap-2 rounded-md border-0 bg-indigo-600 p-1.5 text-gray-200 shadow-sm ring-1 ring-inset hover:bg-gray-700 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:bg-gray-600 sm:w-max sm:px-4 sm:py-2 sm:leading-6"
											x-on:click.prevent="if(currentImageCount == imageLimit){notification.show=true;notification.type='danger';notification.message='La limite des images a ete ateinte, veuillez modifier les existante, ou en suprimer pour continuer!' }else{document.getElementById('imagesInput').click()}   ">
											<span>
												<svg class="w-6 stroke-gray-200" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
													<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
													<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
													<g id="SVGRepo_iconCarrier">
														<path
															d="M3 15C3 17.8284 3 19.2426 3.87868 20.1213C4.75736 21 6.17157 21 9 21H15C17.8284 21 19.2426 21 20.1213 20.1213C21 19.2426 21 17.8284 21 15"stroke-width="2"
															stroke-linecap="round" stroke-linejoin="round"></path>
														<path d="M12 16V3M12 3L16 7.375M12 3L8 7.375" stroke-linecap="round" stroke-linejoin="round"
															stroke-width="2"></path>
													</g>
												</svg>
											</span>
											<span
												x-text="uploadedImages.length + currentImageCount >= imageLimit ? 'Choisisez '+ (imageLimit - (uploadedImages.length + currentImageCount)) +' images' : 'Choisisez '+ (imageLimit - (uploadedImages.length + currentImageCount)) +' images'">
												Choisisez une image
											</span>
										</button>
										<template x-if="(imageLimit - (uploadedImages.length + currentImageCount)) == 0">
											<p class="text-sm text-gray-600 dark:text-gray-400"
												x-text="'La limite des images a ete ateinte, veuillez modifier les existante, ou en suprimer pour continuer!'">
											</p>

										</template>
										@error('image')
											<p class="text-sm text-red-500">{{ $message }}</p>
										@enderror
									</div>
								</div>

							</div>
							{{-- Boutons --}}
							<div class="mt-4 flex gap-4 sm:justify-end">
								{{-- Annuler --}}
								<button
									class="block grow rounded-md bg-red-600 px-7 py-2 text-center font-semibold text-white hover:bg-red-700 sm:grow-0"
									type="button" x-on:click.prevent="window.history.back()">Annuler</button>
								{{-- Envoyer --}}
								<button
									class="flex grow items-center justify-center gap-2 rounded-md bg-teal-600 px-7 py-2 text-center font-semibold text-white hover:bg-teal-700 sm:grow-0"
									type="submit" x-on:click="document.form.submit()">
									<span>
										<svg class="stroke+-white w-6 fill-white" fill="none" viewBox="0 0 24 24"
											xmlns="http://www.w3.org/2000/svg">
											<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
											<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
											<g id="SVGRepo_iconCarrier">
												<path clip-rule="evenodd"
													d="M18.1716 1C18.702 1 19.2107 1.21071 19.5858 1.58579L22.4142 4.41421C22.7893 4.78929 23 5.29799 23 5.82843V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H18.1716ZM4 3C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21L5 21L5 15C5 13.3431 6.34315 12 8 12L16 12C17.6569 12 19 13.3431 19 15V21H20C20.5523 21 21 20.5523 21 20V6.82843C21 6.29799 20.7893 5.78929 20.4142 5.41421L18.5858 3.58579C18.2107 3.21071 17.702 3 17.1716 3H17V5C17 6.65685 15.6569 8 14 8H10C8.34315 8 7 6.65685 7 5V3H4ZM17 21V15C17 14.4477 16.5523 14 16 14L8 14C7.44772 14 7 14.4477 7 15L7 21L17 21ZM9 3H15V5C15 5.55228 14.5523 6 14 6H10C9.44772 6 9 5.55228 9 5V3Z"
													fill-rule="evenodd"></path>
											</g>
										</svg>
									</span>
									<span>Enregistrer</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="py-8"></div>
		</div>
	</div>
</x-app-layout>
