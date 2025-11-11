@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div>
            <h2 class="text-2xl font-semibold leading-tight">HMI List</h2>
        </div>
        <div class="my-2 flex sm:flex-row flex-col">
            <div class="flex flex-row mb-1 sm:mb-0">
                <div class="relative">
                    <input placeholder="Search" id="searchInput" class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-2 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none focus:shadow-outline" />
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Area</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Hostname</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">MAC Address</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Model/Type</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">O/S</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">RAM</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">SSD/HDD</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">PC Username</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Password</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Monitor Size</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            </tr>
                        </thead>
                        <tbody id="hmi-table-body">
                            @foreach($hmis as $hmi)
                            <tr class="hmi-row">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->area }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->hostname }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->ip_address }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->mac_address }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->serial_number }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->model_type }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->os }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->ram }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->ssd_hdd }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->pc_username }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->password }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->monitor_size }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $hmi->location }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.hmi-row');

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
