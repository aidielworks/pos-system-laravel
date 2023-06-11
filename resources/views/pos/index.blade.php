<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
            {{ __('Pos') }}
        </h2>
    </x-slot>

    <div class="my-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <livewire:pos.pos/>
            </div>
        </div>
    </div>

    @if(session()->has('print-order-receipt'))
        <script>
            function makeHttpObject() {
                if("XMLHttpRequest" in window)return new XMLHttpRequest();
                else if("ActiveXObject" in window)return new ActiveXObject("Msxml2.XMLHTTP");
            }

            const request = makeHttpObject();
            request.open("GET", "{{ route('print.order-receipt', session()->get('print-order-receipt')) }}", true);
            request.send(null);
            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    printOrder(request.responseText)
                }
            };

            function printOrder(content){
                const frame1 = document.createElement('iframe');
                frame1.name = "frame1";
                frame1.style.position = "absolute";
                frame1.style.top = "-1000000px";
                document.body.appendChild(frame1);
                const frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
                frameDoc.document.open();
                frameDoc.document.write(content);
                frameDoc.document.close();
                setTimeout(function () {
                    window.frames["frame1"].focus();
                    window.frames["frame1"].print();
                    document.body.removeChild(frame1);
                }, 500);
                return false;
            }
        </script>
    @endif
</x-app-layout>
