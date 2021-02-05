@props(['id', 'label'])

<div>
  <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>

  <div class="mt=1">
    <input
      class="shadow-sm focus:outline-none block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has($id) ? 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'focus:ring-indigo-500 focus:border-indigo-500' }}"
      type="text"
      placeholder="maxo.com.au"
      id="{{ $id }}"
      name="{{ $id }}"
      {{ $attributes }}
      />
  </div>
  {!! $errors->first($id, '<span class="mt-1 text-sm text-red-700">:message</span>') !!}
</div>
