<div>
  <div>
    @if ($added)
	  <!-- This example requires Tailwind CSS v2.0+ -->
	  <div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
		<div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
		  <div class="p-4">
			<div class="flex items-start">
			  <div class="flex-shrink-0">
				<!-- Heroicon name: outline/check-circle -->
				<x-heroicon-o-check-circle class="h-6 w-6 text-green-400" />
			  </div>
			  <div class="ml-3 w-0 flex-1 pt-0.5">
				<p class="text-sm font-medium text-gray-900">
				  Site added
				</p>
			  </div>
			  <div class="ml-4 flex-shrink-0 flex">
				<button
                  wire:click="$toggle('added')"
                  class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
				  <span class="sr-only">Close</span>
				  <x-heroicon-s-x class="h-5 w-5" />
				</button>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
    @endif
  </div>

  <div>
    @if ($error)
	  <div class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end">
		<div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
		  <div class="p-4">
			<div class="flex items-start">
			  <div class="flex-shrink-0">
				<x-heroicon-o-x-circle class="h-6 w-6 text-red-400" />
			  </div>
			  <div class="ml-3 w-0 flex-1 pt-0.5">
				<p class="text-sm font-medium text-gray-900">
				  {{ $error }}
				</p>
			  </div>
			  <div class="ml-4 flex-shrink-0 flex">
				<button
                  wire:click="$set('error', null)"
                  class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
				  <span class="sr-only">Close</span>
				  <x-heroicon-s-x class="h-5 w-5" />
				</button>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
    @endif
  </div>

  <x-forms.input
    label="Add new monitor"
    id="site"
    wire:keydown.enter="addMonitor"
    wire:model.defer="site"
    autofocus
  />
</div>
