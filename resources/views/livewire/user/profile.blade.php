<div class="py-8">
    <form wire:submit.prevent="save">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Personal Information") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Use a permanent address where you can receive mail.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div>
                            <label class="block text-sm leading-5 font-medium text-gray-700">Photo</label>
                            <div class="mt-2 flex items-center">
                                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                    @if($picture)
                                        <img class="h-full w-full bg-center" src="{{ $picture->temporaryUrl() }}" alt="">
                                    @else
                                        <img class="h-full w-full bg-center" src="{{ $logged_in_user->picture }}" alt="{{ $logged_in_user->email }}">
                                    @endif
                                </span>
                                <span class="ml-5 rounded-md shadow-sm">
                                    <label class="inline-flex cursor-pointer py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-brand-400 focus:shadow-outline-brand active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                        {{ __("Change") }}
                                        <input type='file' wire:model='picture' class='hidden' />
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-6 gap-6 mt-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium leading-5 text-gray-700">{{ __("First name") }}</label>
                                <div class="relative mt-1">
                                    <input type='text' wire:model='first_name'  class='form-input block w-full sm:text-sm sm:leading-5' autocomplete='off' id='first_name' />
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="last_name" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Last name") }}</label>
                                <div class="relative mt-1">
                                    <input type='text' wire:model='last_name'  class='form-input block w-full sm:text-sm sm:leading-5' autocomplete='off' id='last_name' />
                                </div>
                            </div>

                            <div class="col-span-6">
                                <label for="email" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Email address") }}</label>
                                <div class="relative mt-1">
                                    <input type='email' wire:model='email' class='form-input block w-full sm:text-sm sm:leading-5' autocomplete='off' id='email' />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-5">
            <div class="flex justify-end">
                <span class="inline-flex rounded-md shadow-sm">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150">
                        <span wire:loading wire:target="save" class="pr-2">
                            <span class="btn-spinner"></span>
                        </span>
                        {{ __("Update") }}
                    </button>
                </span>
            </div>
        </div>
    </form>
</div>
