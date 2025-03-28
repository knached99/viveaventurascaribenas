
    <x-admincomponents.header/>
    <body style="background-image: url({{ asset('assets/images/cancun_mexico_2.jpg') }}); background-size: cover; background-repeat: no-repeat; background-position: center;">

        <div>
        
            {{ $slot }}
        </div>
   
    <x-admincomponents.footer/>
</body>
</html>
