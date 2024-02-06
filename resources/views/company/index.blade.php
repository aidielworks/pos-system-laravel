<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Setting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="setup()">
                    <ul class="w-full flex justify-center items-center my-4">
                        <template x-for="(tab, index) in tabs" :key="index">
                            <li class="cursor-pointer py-2 px-4 text-gray-500 border-b-8"
                                :class="activeTab===index ? 'text-yellow-500 border-yellow-500' : ''" @click="activeTab = index"
                                x-text="tab">
                            </li>
                        </template>
                    </ul>

                    <div class="w-full py-5 px-4">
                        <div x-show="activeTab===0">
                            <div class="p-4">
                                <form action="{{ route('company.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="flex flex-col gap-6 md:flex-row">
                                        <div class="w-full">
                                            <p class="font-bold text-2xl text-yellow-500">Company Information</p>
                                            <div>
                                                <div class="my-4">
                                                    <label for="name" class="mb-2 block text-base font-medium">
                                                        <div class="flex items-center gap-2">
                                                            <span>Company Name</span>
                                                            <span class="group flex relative">
                                                                <span class="text-gray-500 w-5 h-5">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <circle cx="12" cy="12" r="10"></circle>
                                                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                                    </svg>
                                                                </span>
                                                                <span class="pointer-events-none absolute left-3/4 -translate-y-[60px] w-48 rounded bg-gray-900 px-2 py-1 text-sm font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">
                                                                    Please contact our admin if you wish to make changes for company name.
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </label>
                                                    <div class="px-4 py-2 w-full rounded-md border border-[#e0e0e0] bg-gray-200 text-[#6B7280] text-base font-medium outline-none hover:cursor-not-allowed focus:border-[#6A64F1] focus:shadow-md">
                                                        {{ $company->name }}
                                                    </div>
                                                </div>
                                                <div class="my-4">
                                                    <label for="registration_number" class="mb-2 block text-base font-medium">
                                                        <div class="flex items-center gap-2">
                                                            <span>Registration Number</span>
                                                            <span class="group flex relative">
                                                            <span class="text-gray-500 w-5 h-5">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                                </svg>
                                                            </span>
                                                            <span class="pointer-events-none absolute left-3/4 -translate-y-[60px] w-48 rounded bg-gray-900 px-2 py-1 text-sm font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">
                                                                Please contact our admin if you wish to make changes for registration number.
                                                            </span>
                                                        </span>
                                                        </div>
                                                    </label>
                                                    <div class="px-4 py-2 w-full rounded-md border border-[#e0e0e0] bg-gray-200 text-[#6B7280] text-base font-medium outline-none hover:cursor-not-allowed focus:border-[#6A64F1] focus:shadow-md">
                                                        {{ $company->registration_number }}
                                                    </div>
                                                </div>
                                                <div class="my-4">
                                                    <label for="brand_name" class="mb-2 block text-base font-medium">
                                                        Brand Name
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="brand_name"
                                                        id="brand_name"
                                                        class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                        value="{{ $company->brand_name }}"
                                                    />
                                                </div>
                                                <div class="my-4">
                                                    <label for="brand_name" class="mb-2 block text-base font-medium">
                                                        Company logo
                                                    </label>
                                                    <div class="flex flex-col items-center gap-4">
                                                        <div class="relative">
                                                            <div class="w-48 h-48 flex gap-4 justify-center items-center bg-gray-200 opacity-0 absolute -top-0 -left-0 transition-opacity hover:opacity-60">
                                                                @if(isset($company->logo_url))
                                                                    <button type="button" id="remove_logo" class="p-2 bg-yellow-400 border-2 border-yellow-500 w-10 h-10 rounded-lg hover:bg-yellow-500">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                            <line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                                <button type="button" id="revert" class="hidden p-2 bg-yellow-400 border-2 border-yellow-500 w-10 h-10 rounded-lg hover:bg-yellow-500">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M2.5 2v6h6M2.66 15.57a10 10 0 1 0 .57-8.38"/>
                                                                    </svg>
                                                                </button>
                                                                <button type="button" id="upload" class="p-2 bg-yellow-400 border-2 border-yellow-500 w-10 h-10 rounded-lg hover:bg-yellow-500">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M3 15v4c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2v-4M17 8l-5-5-5 5M12 4.2v10.3"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <img id="images" src="{{ $company->logo_url ? asset($company->logo_url) : asset('asset/img/default-image.png') }}" class="w-48 h-48 object-cover rounded-md border-2" alt="Company Logo">
                                                        </div>
                                                    </div>
                                                    <input class="hidden" type="file" name="logo" id="logo">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact Information --->
                                        <div class="w-full">
                                            <p class="font-bold text-2xl text-yellow-500">Contact Information</p>
                                            <div>
                                                <div class="my-4">
                                                    <label
                                                        for="owner"
                                                        class="mb-2 block text-base font-medium"
                                                    >
                                                        Contact Person
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="owner"
                                                        id="owner"
                                                        class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                        value="{{ $company->owner }}"
                                                    />
                                                </div>
                                                <div class="my-4">
                                                    <label
                                                        for="phone_number"
                                                        class="mb-2 block text-base font-medium"
                                                    >
                                                        Phone Number
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="phone_number"
                                                        id="phone_number"
                                                        class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                        value="{{ $company->phone_number }}"
                                                    />
                                                </div>
                                                <div class="my-4">
                                                    <label
                                                        for="email"
                                                        class="mb-2 block text-base font-medium"
                                                    >
                                                        Email
                                                    </label>
                                                    <input
                                                        type="email"
                                                        name="email"
                                                        id="email"
                                                        class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                        value="{{ $company->email }}"
                                                    />
                                                </div>
                                            </div>

                                            <hr class="border border-yellow-500 my-6">

                                            <p class="font-bold text-2xl text-yellow-500">Business Address</p>
                                            <div>
                                                <div class="my-4">
                                                    <label for="address" class="mb-2 block text-base font-medium">
                                                        Address
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="address"
                                                        id="address"
                                                        class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                        value="{{ $company->address }}"
                                                    />
                                                </div>
                                                <div class="flex gap-4">
                                                    <div class="my-2 w-full">
                                                        <label for="postcode" class="mb-2 block text-base font-medium">
                                                            Postcode
                                                        </label>
                                                        <input
                                                            type="text"
                                                            name="postcode"
                                                            id="postcode"
                                                            class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                            value="{{ $company->postcode }}"
                                                        />
                                                    </div>
                                                    <div class="my-2 w-full">
                                                        <label for="city" class="mb-2 block text-base font-medium">
                                                            City
                                                        </label>
                                                        <input
                                                            type="text"
                                                            name="city"
                                                            id="city"
                                                            class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                            value="{{ $company->city }}"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="my-4">
                                                    <label for="state" class="mb-2 block text-base font-medium">
                                                        State
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="state"
                                                        id="state"
                                                        class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                        value="{{ $company->state }}"
                                                    />
                                                </div>
                                                <div class="my-4">
                                                    <label for="country" class="mb-2 block text-base font-medium">
                                                        Country
                                                    </label>
                                                    <select
                                                        name="country"
                                                        id="country"
                                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                                                    >
                                                        <option>Select country</option>
                                                        @foreach($countries as $key => $country)
                                                            <option @if($company->country == $key) selected @endif value="{{ $key }}">{{ $country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Contact Information --->
                                    </div>
                                    <button type="submit" class="bg-yellow-500 px-4 py-2 rounded-lg text-yellow-900 hover:bg-yellow-300">Update</button>
                                </form>
                            </div>
                        </div>
                        <div x-show="activeTab===1">
                            <livewire:category.category-list/>
                        </div>
                        <div x-show="activeTab===2">
                            <livewire:product.product-list/>
                        </div>
                        <div x-show="activeTab===3">
                            <livewire:tables-list/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            function setup() {
                return {
                    activeTab: {{ session()->has('setting_tab') ? session()->get('setting_tab') : 0 }},
                    tabs: [
                        "Company Information",
                        "Categories",
                        "Products",
                        "Tables"
                    ]
                };
            };

            const images = document.getElementById('images');
            const company_logo = '{!! asset($company->logo_url) !!}';
            const default_logo = '{!! asset('asset/img/default-image.png') !!}';
            const revert = document.getElementById('revert');

            // click the hidden input of type file if the visible button is clicked
            // and capture the selected files
            const logo = document.getElementById("logo");
            document.getElementById("upload").onclick = () => logo.click();
            logo.onchange = (e) => {
                for (const file of e.target.files) {
                    addFile(file);
                }
            };

            document.getElementById('revert').onclick = () => {
                if('{{$company->logo_url}}' == ''){
                    images.src = default_logo;
                }else {
                    images.src = company_logo;
                }
                logo.value = null;
                revert.classList.add('hidden');
            };

            if (document.getElementById('remove_logo') !== null) {
                document.getElementById('remove_logo').onclick = () => {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    Swal.fire({
                        title: 'Remove logo?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, remove!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type:'POST',
                                url:"{{ route('company.remove-logo') }}",
                                data:{},
                                success:function(data){
                                    Swal.fire({
                                        title: data.message,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                    images.src = default_logo;
                                },
                                error:function(error){
                                    Swal.fire({
                                        title: error.responseJSON.message,
                                        icon: 'error',
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                }
                            });
                        }
                    })
                };
            }

            // check if file is of type image and prepend the initialied
            // template to the target element
            function addFile(file) {
                const isImage = file.type.match("image.*"),
                    objectURL = URL.createObjectURL(file);

                if (!isImage) {
                    Swal.fire({
                        title: 'File type must be png,jpg,jpeg',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    return;
                }

                images.src = objectURL;
                revert.classList.remove('hidden');
            }
        </script>
    @endpush

</x-app-layout>
