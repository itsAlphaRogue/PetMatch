<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<header class="my-4 flex mx-4 items-center justify-between px-3 lg:ml-8 lg:p-0">
    <!-- logo in navbar -->
    <a href="./">
    <div class="flex items-center">
            <img src="assets/images/logo.svg" alt="" class="h-12 w-fit" />
            <div class="ml-2">
                <p class="text-xl font-bold">PetMatch</p>
                <p>Find your purrfect friend</p>
            </div>
        </div>
    </a>

    <!-- hamburger menu for mobile devices -->
    <nav onclick="shownav()" class="lg:hidden">
        <svg class="h-8 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z" /></svg>
    </nav>

    <!-- nav menu for lg devices -->
    <nav class="hidden lg:block">
        <ul class="flex justify-between">
            <li class="mx-8"><a href="./">Home</a></li>
            <li class="mx-8"><a href="findapet">Find a pet</a></li>
            <li class="mx-8"><a href="matcher">Breed Matcher</a></li>
            <li class="mx-8"><a href="about">About us</a></li>
        </ul>
    </nav>

    <!-- user icon or login / signup button -->
    <div class="hidden md:hidden lg:block">
        <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if(isset($_SESSION['user']) or isset($_SESSION['admin']))
            {
                if(isset($_SESSION['user']))
                {
                    $user = $_SESSION['user'];
                    $redirect = "dashboard";
                }
                elseif(isset($_SESSION['admin']))
                {
                    $user = $_SESSION['admin'];
                    $redirect = "admindashboard";
                }
                echo <<<END
                    <div class="gap-2 items-center justify-center cursor-pointer">
                        <a href="$redirect" class="flex flex-col justify-center">
                            <svg class="h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                            <p>$user</p>
                        </a>
                    </div>
                END;
            }
            else
            {
                echo '<div class="flex gap-2 items-center justify-center">
                        <a class="w-24 text-center cursor-pointer rounded-sm bg-green-500  py-2 transition-colors hover:bg-green-300 active:bg-green-600" href="login">Login</a>
                        <a class="w-24 text-center cursor-pointer rounded-sm bg-blue-500  py-2 transition-colors hover:bg-blue-300 active:bg-blue-600" href="register">Sign up</a>
                    </div>';
            }
        ?>
    </div>

    <!-- nav menu for sm devices -->
    <nav id="nav" class="fixed top-0 right-[-190px] hidden h-screen w-48 z-10 bg-neutral-400/50 backdrop-blur-xl transition-all duration-300">
        <span onclick="hidenav()" class="text-end">
            <svg class="mt-2 h-8 cursor-pointer p-2 pl-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z" /></svg>
        </span>

        <ul class="flex w-full flex-col">
            <li class="hover:bg-white/50"><a class="block w-full py-2 pl-4" href="./">Home</a></li>
            <li class="hover:bg-white/50"><a class="block w-full py-2 pl-4" href="findapet">Find a pet</a></li>
            <li class="hover:bg-white/50"><a class="block w-full py-2 pl-4" href="matcher">Breed Matcher</a></li>
            <li class="hover:bg-white/50"><a class="block w-full py-2 pl-4" href="about">About us</a></li>
        </ul>

        <?php
            if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            if(isset($_SESSION['user']) or isset($_SESSION['admin']))
            {
                if(isset($_SESSION['user']))
                {
                    $user = $_SESSION['user'];
                    $redirect = "dashboard";
                }
                elseif(isset($_SESSION['admin']))
                {
                    $user = $_SESSION['admin'];
                    $redirect = "admindashboard";
                }
                
                echo <<<END
                <div class="gap-2 mt-8 items-center justify-center cursor-pointer">
                    <a href="$redirect" class="flex flex-col justify-center">
                        <svg class="h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M399 384.2C376.9 345.8 335.4 320 288 320l-64 0c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                        <p class="text-center mt-2">$user</p>
                    </a>
                </div>
                END;
            }
            else
            {
                echo '<div class="flex flex-col justify-center items-center mt-10 gap-2">
                        <a class="h-12 w-32 text-center cursor-pointer rounded-sm bg-green-500 px-8 py-3 transition-colors hover:bg-green-300 active:bg-green-600" href="">Login</a>
                        <a class="h-12 w-32 text-center cursor-pointer rounded-sm bg-blue-500 px-8 py-3 transition-colors hover:bg-blue-300 active:bg-blue-600" href="">Sign up</a>
                    </div>';
            }
        ?>
    </nav>
</header>
