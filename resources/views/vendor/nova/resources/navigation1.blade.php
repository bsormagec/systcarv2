@if (count(Nova::availableResources(request())))
<ul class="sidemenu">
    @php
        $orderNav = $navigation->sortBy(function($resources){
            return $resources->first();
        });
    @endphp
    @foreach($orderNav as $group => $resources)
      @if (count($groups) > 1)
      <li class="sidebar-dropdown mb-2">
        <input id="{{$group}}" class="toogle-group" type="checkbox" />
        <label for="{{$group}}" data-toggle="dropdown" class="flex items-center font-normal text-white mb-4 text-base no-underline dim">
            @if(method_exists($resources->first(), 'icon'))
                {!! $resources->first()::icon() !!}
            @endif
            {{ $group }}
            <svg class="text-gray-300 ml-auto h-5 w-5 transform group-hover:text-gray-400 transition-transform ease-in-out duration-150 focus:ring-indigo-500" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
              </svg>
        </label>

        <ul class="dropdown-menu">
          @foreach($resources as $resource)
          <li>
            <router-link :to="{
                name: 'index',
                params: {
                    resourceName: '{{ $resource::uriKey() }}'
                }
            }" class="flex items-center font-normal text-white mb-4 text-base no-underline dim">

                <span class="sidebar-label">{{ $resource::label() }}</span>
            </router-link>
          </li>
          @endforeach
        </ul>
      </li>

      @else
        @foreach($resources as $resource)
        <li class="sidebar-dropdown">
            <router-link :to="{
                name: 'index',
                params: {
                    resourceName: '{{ $resource::uriKey() }}'
                }
            }" class="flex items-center font-normal text-white mb-6 text-base no-underline dim">
                @if(property_exists($resource, 'icon'))
                    {!! $resource::$icon !!}
                @elseif(method_exists($resource, 'icon'))
                    {!! $resource::icon() !!}
                @else
                <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill="var(--sidebar-icon)" d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z"
                    />
                </svg>
                @endif
                <span class="sidebar-label">{{ $resource::label() }}</span>
            </router-link>
        </li>
        @endforeach
      @endif
    @endforeach
</ul>
@endif