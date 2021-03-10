<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Super titre</title>
        <meta charset="UTF-8"/>
        <!--<link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;900&display=swap" >
        -->
        <link rel="stylesheet" href="css/reset.css" />
       <!-- <link rel="stylesheet" href="css/main.css" />-->
        <link type="text/css" rel="stylesheet" href="css/main.css?t=<? echo time(); ?>" media="all"/    >
        <?php
            require ('fonctions.php');
        ?>
    </head>
<body>
    <header class="main-header flex f-space-between">
        <div id="logo" >
            <img src="assets/logo.png"  class="logo" alt="Logo">
        </div>

        <div>
            <nav class="mainNav">
                <ul class="flex f-center">
                   <!-- Fonction Php avec les catégories -->
                    <?php
                        echo MenuCatMainNav();
                    ?>
                </ul>
            </nav>
        </div>

        <div>
            <div class="user flex f-space-between">
                <svg role="img" aria-label="Profil" class="user_tools" viewBox="-42 0 512 512.001" xmlns="http://www.w3.org/2000/svg"><path d="M210.352 246.633c33.882 0 63.218-12.153 87.195-36.13 23.969-23.972 36.125-53.304 36.125-87.19 0-33.876-12.152-63.211-36.129-87.192C273.566 12.152 244.23 0 210.352 0c-33.887 0-63.22 12.152-87.192 36.125s-36.129 53.309-36.129 87.188c0 33.886 12.156 63.222 36.13 87.195 23.98 23.969 53.316 36.125 87.19 36.125zM144.379 57.34c18.394-18.395 39.973-27.336 65.973-27.336 25.996 0 47.578 8.941 65.976 27.336 18.395 18.398 27.34 39.98 27.34 65.972 0 26-8.945 47.579-27.34 65.977-18.398 18.399-39.98 27.34-65.976 27.34-25.993 0-47.57-8.945-65.973-27.34-18.399-18.394-27.344-39.976-27.344-65.976 0-25.993 8.945-47.575 27.344-65.973zm0 0M426.129 393.703c-.692-9.976-2.09-20.86-4.149-32.351-2.078-11.579-4.753-22.524-7.957-32.528-3.312-10.34-7.808-20.55-13.375-30.336-5.77-10.156-12.55-19-20.16-26.277-7.957-7.613-17.699-13.734-28.965-18.2-11.226-4.44-23.668-6.69-36.976-6.69-5.227 0-10.281 2.144-20.043 8.5a2711.03 2711.03 0 01-20.879 13.46c-6.707 4.274-15.793 8.278-27.016 11.903-10.949 3.543-22.066 5.34-33.043 5.34-10.968 0-22.086-1.797-33.043-5.34-11.21-3.622-20.3-7.625-26.996-11.899-7.77-4.965-14.8-9.496-20.898-13.469-9.754-6.355-14.809-8.5-20.035-8.5-13.313 0-25.75 2.254-36.973 6.7-11.258 4.457-21.004 10.578-28.969 18.199-7.609 7.281-14.39 16.12-20.156 26.273-5.558 9.785-10.058 19.992-13.371 30.34-3.2 10.004-5.875 20.945-7.953 32.524-2.063 11.476-3.457 22.363-4.149 32.363C.343 403.492 0 413.668 0 423.949c0 26.727 8.496 48.363 25.25 64.32C41.797 504.017 63.688 512 90.316 512h246.532c26.62 0 48.511-7.984 65.062-23.73 16.758-15.946 25.254-37.59 25.254-64.325-.004-10.316-.351-20.492-1.035-30.242zm-44.906 72.828c-10.934 10.406-25.45 15.465-44.38 15.465H90.317c-18.933 0-33.449-5.059-44.379-15.46-10.722-10.208-15.933-24.141-15.933-42.587 0-9.594.316-19.066.95-28.16.616-8.922 1.878-18.723 3.75-29.137 1.847-10.285 4.198-19.937 6.995-28.675 2.684-8.38 6.344-16.676 10.883-24.668 4.332-7.618 9.316-14.153 14.816-19.418 5.145-4.926 11.63-8.957 19.27-11.98 7.066-2.798 15.008-4.329 23.629-4.56 1.05.56 2.922 1.626 5.953 3.602 6.168 4.02 13.277 8.606 21.137 13.625 8.86 5.649 20.273 10.75 33.91 15.152 13.941 4.508 28.16 6.797 42.273 6.797 14.114 0 28.336-2.289 42.27-6.793 13.648-4.41 25.058-9.507 33.93-15.164 8.043-5.14 14.953-9.593 21.12-13.617 3.032-1.973 4.903-3.043 5.954-3.601 8.625.23 16.566 1.761 23.636 4.558 7.637 3.024 14.122 7.059 19.266 11.98 5.5 5.262 10.484 11.798 14.816 19.423 4.543 7.988 8.208 16.289 10.887 24.66 2.801 8.75 5.156 18.398 7 28.675 1.867 10.434 3.133 20.239 3.75 29.145v.008c.637 9.058.957 18.527.961 28.148-.004 18.45-5.215 32.38-15.937 42.582zm0 0"/></svg>

                <svg role="img" aria-label="Panier" class="user_tools" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 201.387 201.387"><path d="M129.413 24.885C127.389 10.699 115.041 0 100.692 0 91.464 0 82.7 4.453 77.251 11.916a3.413 3.413 0 105.51 4.026c4.171-5.707 10.873-9.115 17.93-9.115 10.974 0 20.415 8.178 21.963 19.021a3.417 3.417 0 003.862 2.898 3.415 3.415 0 002.897-3.861zM178.712 63.096l-10.24-17.067a3.409 3.409 0 00-2.927-1.657h-9.813a3.414 3.414 0 000 6.826h7.881l6.144 10.24H31.626l6.144-10.24h3.615a3.414 3.414 0 000-6.826h-5.547c-1.2 0-2.311.628-2.927 1.657l-10.24 17.067a3.417 3.417 0 002.927 5.171h150.187a3.414 3.414 0 002.927-5.171z"/><path d="M161.698 31.623a3.408 3.408 0 00-2.123-1.524l-46.531-10.883a3.42 3.42 0 00-2.579.423 3.416 3.416 0 00-1.522 2.123l-3.509 15a3.41 3.41 0 002.546 4.099 3.412 3.412 0 004.101-2.546l2.732-11.675 39.883 9.329-6.267 26.795a3.41 3.41 0 003.328 4.189 3.408 3.408 0 003.318-2.635L162.12 34.2a3.4 3.4 0 00-.422-2.577zM102.497 39.692l-3.11-26.305a3.413 3.413 0 00-3.791-2.99l-57.09 6.748a3.414 3.414 0 00-2.988 3.791l5.185 43.873a3.414 3.414 0 106.78-.801l-4.785-40.486 50.311-5.946 2.708 22.915a3.413 3.413 0 106.78-.799z"/><path d="M129.492 63.556l-6.775-28.174a3.422 3.422 0 00-1.536-2.113 3.434 3.434 0 00-2.581-.406L63.613 46.087a3.414 3.414 0 00-2.521 4.117l3.386 14.082a3.414 3.414 0 006.637-1.596l-2.589-10.764 48.35-11.626 5.977 24.854a3.413 3.413 0 004.118 2.519 3.414 3.414 0 002.521-4.117z"/><path d="M179.197 64.679a3.415 3.415 0 00-3.41-3.238H25.6a3.414 3.414 0 00-3.41 3.238l-6.827 133.12a3.415 3.415 0 003.409 3.588h163.84c.935 0 1.83-.384 2.478-1.062a3.422 3.422 0 00.934-2.526l-6.827-133.12zM22.364 194.56l6.477-126.293h143.701l6.477 126.293H22.364z"/><path d="M126.292 75.093c-5.647 0-10.24 4.593-10.24 10.24s4.593 10.24 10.24 10.24 10.24-4.593 10.24-10.24-4.593-10.24-10.24-10.24zm0 13.654c-1.883 0-3.413-1.531-3.413-3.413s1.531-3.413 3.413-3.413 3.413 1.531 3.413 3.413-1.531 3.413-3.413 3.413zM75.092 75.093c-5.647 0-10.24 4.593-10.24 10.24s4.593 10.24 10.24 10.24 10.24-4.593 10.24-10.24-4.593-10.24-10.24-10.24zm0 13.654c-1.882 0-3.413-1.531-3.413-3.413s1.531-3.413 3.413-3.413 3.413 1.531 3.413 3.413-1.531 3.413-3.413 3.413z"/><path d="M126.292 85.333h-.263a3.414 3.414 0 00-3.15 4.729v17.457c0 12.233-9.953 22.187-22.187 22.187s-22.187-9.953-22.187-22.187V88.747a3.414 3.414 0 00-6.826 0v18.773c0 15.998 13.015 29.013 29.013 29.013s29.013-13.015 29.013-29.013V88.747a3.415 3.415 0 00-3.413-3.414z"/></svg>
            </div>
        </div>
    </header>