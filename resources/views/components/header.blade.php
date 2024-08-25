<div class="navbar">
    <button class="w3-button w3-teal w3-xlarge" onclick="w3_open()">☰</button>
    <nav class="nav-pages">
        <ul>
            <li><a href="/about">About</a></li>
            <li><a href="/FAQ">FAQ</a></li>
            <li><a href="/contactUs">Contact Us</a></li>
            @if (!Auth::check())
                <li><a href="/login">Login/Register</a></li>
            @endif
        </ul>
    </nav>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search...">
        <div id="suggestions" class="suggestions-results"></div>

        <a href="#" id="searchBtn">
            <svg class="icons" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.319 14.4326C20.7628 11.2941 20.542 6.75347 17.6569 3.86829C14.5327 0.744098 9.46734 0.744098 6.34315 3.86829C3.21895 6.99249 3.21895 12.0578 6.34315 15.182C9.22833 18.0672 13.769 18.2879 16.9075 15.8442C16.921 15.8595 16.9351 15.8745 16.9497 15.8891L21.1924 20.1317C21.5829 20.5223 22.2161 20.5223 22.6066 20.1317C22.9971 19.7412 22.9971 19.1081 22.6066 18.7175L18.364 14.4749C18.3493 14.4603 18.3343 14.4462 18.319 14.4326ZM16.2426 5.28251C18.5858 7.62565 18.5858 11.4246 16.2426 13.7678C13.8995 16.1109 10.1005 16.1109 7.75736 13.7678C5.41421 11.4246 5.41421 7.62565 7.75736 5.28251C10.1005 2.93936 13.8995 2.93936 16.2426 5.28251Z" fill="currentColor" />
            </svg>
        </a>
        @auth
            <div class="notifications-container">
                <a href="/notifications" id="notificationBtn">
                    <svg class="icons" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14 3V3.28988C16.8915 4.15043 19 6.82898 19 10V17H20V19H4V17H5V10C5 6.82898 7.10851 4.15043 10 3.28988V3C10 1.89543 10.8954 1 12 1C13.1046 1 14 1.89543 14 3ZM7 17H17V10C17 7.23858 14.7614 5 12 5C9.23858 5 7 7.23858 7 10V17ZM14 21V20H10V21C10 22.1046 10.8954 23 12 23C13.1046 23 14 22.1046 14 21Z" fill="currentColor" />
                    </svg>
                </a>
        
                <div id="notificationsDropdown" class="notifications-dropdown">
                    <ul id="notificationList"></ul>
                    <p id="noNotifications" style="display: none; color: white;">No new notifications</p>
                    <button id="clearNotificationsBtn">Clear All Notifications</button>
                </div>
            </div>
        @endauth
    </div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script src='public/js/app.js' defer></script>


</div>


