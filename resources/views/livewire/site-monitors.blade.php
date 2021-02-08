<div>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="flex flex-col">
      <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
          <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
              <colgroup>
                <col class="w-6/12" />
                <col class="w-3/12" />
                <col class="w-3/12" />
                <col class="w-1/12" />
              </colgroup>
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Site
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Certificate Expiry
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Last checked
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($monitors as $monitor)
                  <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      <div class="flex items-center space-x-2">
                        <div>
                          <x-monitor-status :monitor="$monitor" />
                        </div>
                        <div>
                          <a href="https://{{ $monitor->site }}" target="_blank">{{ $monitor->site }}</a>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div class="flex items-center space-x-2">
                        @if ($monitor->certificate_expires_at)
                          <x-localised-date timestamp="{{ $monitor->certificate_expires_at->timestamp }}" />
                        @endif
                        <div>
                          @if ($monitor->is_expiring)
                            <div title="Certificate will expire in {{ $monitor->expires_in_days }} {{ Str::plural('day', $monitor->expires_in_days ) }}">
                              <x-is-expiring class="w-4 h-4 text-orange-500" />
                            </div>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      @if ($monitor->last_checked_at)
                        <x-localised-date timestamp="{{ $monitor->last_checked_at->timestamp }}" />
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      @if ($monitor->latestCheck)
                        <livewire:check-details :check="$monitor->latestCheck" :key="$monitor->latestCheck->id" />
                      @endif

                      <button
                        wire:click="deleteMonitor({{ $monitor->id }})" 
                        onclick="javascript: return confirm('Are you sure you want to remove this monitor?')"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-gray-900 text-center" colspan="4">
                      You are not currently monitoring any sites.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
            @if ($monitors->hasPages())
              <div class="px-4 sm:px-6 py-4">
                {!! $monitors->links() !!}
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
</div>
