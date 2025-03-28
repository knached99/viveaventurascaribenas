
    <x-admincomponents.header/>
    <body style="background-image: url({{ asset('assets/images/campeche_mexico_sign.jpg') }}); background-size: cover; background-repeat: no-repeat; background-position: center;">

        <div>
        
            {{ $slot }}
        </div>
   
    <x-admincomponents.footer/>
</body>
</html>
