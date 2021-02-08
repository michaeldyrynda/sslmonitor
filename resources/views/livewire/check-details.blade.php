<div x-data="{ open: @entangle('shown') }" x-keypress.escape="open = false">
	<button wire:click="$toggle('shown')">
		View
	</button>

	<!-- This example requires Tailwind CSS v2.0+ -->
	<div x-show="open" class="fixed z-10 inset-0 overflow-y-auto">
		<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
			<div 
				x-show="open"
				@transition:enter="ease-out duration-300"
				@transition:enter-start="opacity-0"
				@transition:enter-end="opacity-100"
				@transition:leave="ease-in duration-200"
				@transition:leave-start="opacity-100"
				@transition:leave-end="opacity-0"
				class="fixed inset-0 transition-opacity"
				aria-hidden="true"
			>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<!-- This element is to trick the browser into centering the modal contents. -->
			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

			<div
				@click.away="open = false"
				x-show="open"
				@transition:enter="ease-out duration-300"
				@transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
				@transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
				@transition:leave="ease-in duration-200"
				@transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
				@transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
				class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6"
				role="dialog" 
				aria-modal="true" 
				aria-labelledby="modal-headline"
			>
				<div class="relative">
					<div class="absolute top-0 right-0">
						<button @click="open = false">
							<x-heroicon-s-x class="h-5 w-6" />
						</button>
					</div>
					<div class="mt-3 sm:mt-5">
						<div class="flex items-center justify-center space-x-2">
							<x-monitor-status :monitor="$check->monitor" />
							<h3 class="text-lg leading-6 font-medium text-gray-900 text-center" id="modal-headline">
								{{ $check->monitor->site }}
							</h3>
						</div>
						<div class="mt-2 text-center">
							<p class="text-sm text-gray-500">
								Site last checked <x-localised-date timestamp="{{ $check->created_at->timestamp }}"></x-localised-date>
							</p>
						</div>
						<div class="mt-2 -mx-4 sm:-mx-6">
							<dl class="sm:divide-y sm:divide-gray-200">
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Domain Status
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										<div class="flex items-center space-x-2">
											<span>
												{{ $check->domain_status ?: 'N/A' }}
											</span>
											<div class="w-4 h-4">
												@if ($check->is_domain_valid)
													<x-heroicon-o-badge-check /> 
												@else 
													<x-heroicon-o-ban /> 
												@endif
											</div>
										</div>
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Certificate Issuer	
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										{{ $check->issuer }}
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Certificate Organisation	
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										{{ $check->organisation ?: 'N/A' }}
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Primary Domain
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										{{ $check->domain }}
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Additional Domains
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										{!! implode('<br>', $check->additional_domains) !!}
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Certificate Algorithm
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										{{ $check->algorithm }}
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										SHA256 Fingerprint
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										<code>
											{{ $check->sha256_fingerprint }}
										</code>
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Valid From
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										{{ optional($check->valid_from)->format('d/m/Y H:ia') }} ({{ ($days = optional($check->valid_from)->diffInDays()) }} {{ Str::plural('day', $days) }} ago)
									</dd>
								</div>
								<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
									<dt class="text-sm font-medium text-gray-500">
										Expires At
									</dt>
									<dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
										{{ optional($check->certificate_expires_at)->format('d/m/Y H:ia') }} ({{ ($days = optional($check->certificate_expires_at)->diffInDays()) }} {{ Str::plural('day', $days) }})
									</dd>
								</div>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
