<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="px-4 max-w-[90vw] mx-auto sm:px-6 lg:px-8">
            <div>
                <p class="font-bold text-lg sm:text-3xl text-center mb-4">Today's Sales</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <div
                        class='flex flex-wrap flex-row sm:flex-col justify-center items-center w-full sm:w-1/4 p-5 bg-white rounded-md shadow-xl border-l-4 border-red-300'>
                        <div class="flex justify-between items-center w-full">
                            <div>
                                <div class="p-2">
                                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" fill="currentColor">
                                        <path d="M256 410.304V96a32 32 0 0 1 64 0v314.304a96 96 0 0 0 64-90.56V96a32 32 0 0 1 64 0v223.744a160 160 0 0 1-128 156.8V928a32 32 0 1 1-64 0V476.544a160 160 0 0 1-128-156.8V96a32 32 0 0 1 64 0v223.744a96 96 0 0 0 64 90.56zM672 572.48C581.184 552.128 512 446.848 512 320c0-141.44 85.952-256 192-256s192 114.56 192 256c0 126.848-69.184 232.128-160 252.48V928a32 32 0 1 1-64 0V572.48zM704 512c66.048 0 128-82.56 128-192s-61.952-192-128-192s-128 82.56-128 192s61.952 192 128 192z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <div style="padding-top: 0.1em; padding-bottom: 0.1rem"
                                    @class([
                                        "flex items-center text-xs px-3 rounded-full",
                                        "bg-green-200 text-green-800" => $todays_sales_foods['percent'] > 0 ,
                                        "bg-red-200 text-red-800" => $todays_sales_foods['percent'] <= 0
                                    ])>{{ $todays_sales_foods['percent'] }}%</div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="font-bold text-5xl">
                                {{ $todays_sales_foods['value'] }}
                            </div>
                            <div class="font-bold text-sm">
                                Foods
                            </div>
                        </div>
                    </div>
                    <div
                        class='flex flex-wrap flex-row sm:flex-col justify-center items-center w-full sm:w-1/4 p-5 bg-white rounded-md shadow-xl border-l-4 border-blue-300'>
                        <div class="flex justify-between items-center w-full">
                            <div>
                                <div class="p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" stroke-width="1.5"
                                         stroke="currentColor" class="w-6 h-6">
                                        <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M256 196C256 229.1 227.3 256 192 256C156.7 256 128 229.1 128 196C128 171.1 161.7 125.9 180.2 102.5C186.3 94.77 197.7 94.77 203.8 102.5C222.3 125.9 256 171.1 256 196V196zM352 0C360.9 0 369.4 3.692 375.4 10.19C381.5 16.69 384.6 25.42 383.9 34.28L355.1 437.7C352.1 479.6 317.3 512 275.3 512H108.7C66.72 512 31.89 479.6 28.9 437.7L.0813 34.28C-.5517 25.42 2.527 16.69 8.58 10.19C14.63 3.692 23.12 0 32 0L352 0zM96 304C116.1 314.1 139.9 314.1 160 304C180.1 293.9 203.9 293.9 224 304C244.1 314.1 267.9 314.1 288 304L300.1 297.5L317.6 64H66.37L83.05 297.5L96 304z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <div style="padding-top: 0.1em; padding-bottom: 0.1rem"
                                     @class([
                                        "flex items-center text-xs px-3 rounded-full",
                                        "bg-green-200 text-green-800" => $todays_sales_drinks['percent'] > 0 ,
                                        "bg-red-200 text-red-800" => $todays_sales_drinks['percent'] <= 0
                                    ])>{{ $todays_sales_drinks['percent'] }}%</div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="font-bold text-5xl">
                                {{ $todays_sales_drinks['value'] }}
                            </div>
                            <div class="font-bold text-sm">
                                Drinks
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full mt-12 flex flex-col md:flex-row gap-5">
                <div class="w-full md:w-2/3 bg-white rounded-lg p-4 shadow-md">
                    <p class="font-bold text-lg text-center my-4">Total Sales By Month</p>
                    <canvas id="monthly_sales_chart"></canvas>
                </div>
                <div class="w-full md:w-1/3 bg-white rounded-lg shadow-md p-4">
                    <p class="font-bold text-lg text-center my-4">Monthly Sales by Categories</p>
                    <canvas id="categories_sales_chart"></canvas>
                </div>
            </div>

            <div class="mt-12">
                <p class="font-bold text-lg sm:text-3xl text-center mb-4">Top 10 Items Monthly Sales</p>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full bg-white p-4 rounded-lg shadow-md">
                        <p class="font-bold text-lg text-center mb-4">Foods</p>
                        <table class="w-full bg-white">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <td class="px-4 py-2 w-14">#</td>
                                    <td class="px-4 py-2">Items</td>
                                    <td class="px-4 py-2">Sold</td>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($top_10_foods as $food)
                                <tr>
                                    <td class="px-4 py-2 border text-xs sm:text-lg">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border text-xs sm:text-lg">{{ $food->product->product_name }}</td>
                                    <td class="px-4 py-2 border text-xs sm:text-lg">{{ $food->quantity }}</td>
                                </tr>
                            @empty
                                 <tr>
                                     <td colspan="3" class="px-4 py-2 text-center text-gray-500">No Top 10 Foods. Let's make more sales!</td>
                                 </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="w-full bg-white p-4 rounded-lg shadow-md">
                        <p class="font-bold text-lg text-center mb-4">Drinks</p>
                        <table class="w-full bg-white">
                            <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <td class="px-4 py-2 w-14">#</td>
                                <td class="px-4 py-2">Items</td>
                                <td class="px-4 py-2">Sold</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($top_10_drinks as $drink)
                                <tr>
                                    <td class="px-4 py-2 border text-xs sm:text-lg">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border text-xs sm:text-lg">{{ $drink->product->product_name }}</td>
                                    <td class="px-4 py-2 border text-xs sm:text-lg">{{ $drink->quantity }}</td>
                                </tr>
                            @empty
                                 <tr>
                                     <td colspan="3" class="px-4 py-2 text-center text-gray-500">No Top 10 drinks. Let's make more sales!</td>
                                 </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        const monthLabels = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'June',
            'July',
            'Aug',
            'Sept',
            'Oct',
            'Nov',
            'Dec',
        ];

        const categories = @json($categories);

        function generateDoughnutColor() {
            let colors = []

            categories.forEach(function () {
                colors.push(random_rgba())
            });

            return colors;
        }

        function random_rgba() {
            var o = Math.round, r = Math.random, s = 255;
            return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
        }

        new Chart(
            document.getElementById('categories_sales_chart'),
            {
                type: 'doughnut',
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Sales by Categories',
                        data: @json($category_counts),
                        backgroundColor: generateDoughnutColor(),
                        borderWidth: 1.5,
                        borderColor:'rgb(17 24 39)',
                        hoverOffset: 40,
                        // hoverBorderColor: 'rgb(17 24 39)'
                    }]
                },
                options: {
                    radius:"90%"
                }
            }
        );

        new Chart(
            document.getElementById('monthly_sales_chart'),
            {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Monthly Total Sales (RM)',
                        backgroundColor: 'rgb(234 179 8)',
                        borderColor: 'rgb(234 179 8)',
                        data: @json($month_sales),
                        tension: 0.1
                    }]
                },
                options: {}
            },
        );
    </script>
</x-app-layout>
