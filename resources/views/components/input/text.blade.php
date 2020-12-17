@props(['iconAddOn' => false])

<input {{ $attributes->merge(['class' => 'form-input block w-full py-2 px-3 transition duration-150 ease-in-out sm:text-sm sm:leading-5' . ($iconAddOn ?? '')]) }} />
