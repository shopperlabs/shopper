@props(['value' => null])

<textarea {{ $attributes }} rows="4" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">{{ $value }}</textarea>
