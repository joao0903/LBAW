document.addEventListener('DOMContentLoaded', function () {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    const clearNotificationsBtn = document.getElementById('clearNotificationsBtn');
    const body = document.body;


    Pusher.logToConsole = true;
    var isDropdownVisible = false; 

    var pusher = new Pusher('2029dfcda5d0ab782d41', {
      cluster: 'eu'
    });
    var channel = pusher.subscribe('FeupTimes');
    
    if (notificationBtn && notificationsDropdown) {
        notificationBtn.addEventListener('click', function (event) {
          console.log('Notification button clicked!');
          event.preventDefault();
          event.stopPropagation();
    
          isDropdownVisible = !isDropdownVisible;
          notificationsDropdown.style.display = isDropdownVisible ? 'block' : 'none';
          fetchNotifications();
        });
        clearNotificationsBtn.addEventListener('click', function () {
            clearAllNotifications();
        });
        body.addEventListener('click', function (event) {
            if (!event.target.closest('#notificationsDropdown') && !event.target.closest('#notificationBtn')) {
                notificationsDropdown.style.display = 'none';
                isDropdownVisible = false;
            }
        });
    
        }
        function clearAllNotifications() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
            sendAjaxRequest('POST', '/clear_notifications', { _token: csrfToken }, function(event) {
                const request = event.target;
                if (request.status === 200) {
                    console.log('Notifications cleared successfully');
                    fetchNotifications();
                } else {
                    console.error('Error clearing notifications:', request.statusText);
                }
            });
        }
        
        
        
        
      
        channel.bind('App\\Events\\NotificationEvent', function (data) {
          console.log('Notification received:', data);
          const notificationList = document.getElementById('notificationList');
          const newNotificationItem = document.createElement('li');
          newNotificationItem.textContent = data.content;
          notificationList.appendChild(newNotificationItem);
          isDropdownVisible = true;
          notificationsDropdown.style.display = 'block';
        });
        



  document.body.addEventListener('keypress', function (event) {
      if (event.which === 13) {
          event.preventDefault();
          performSearch();
      }
  });
  const searchBtn = document.getElementById('searchBtn');

if (searchBtn) {
    searchBtn.addEventListener('click', function(event) {
        event.preventDefault();
        performSearch();
    });
}
  function performSearch() {
      var searchQuery = document.getElementById('searchInput').value.trim();
      if (searchQuery !== '') {
          var currentUrl = window.location.href;
          var searchUrl = currentUrl.includes('/welcome') ? '/welcome/search' : '/search';

          var requestData = {
              query: searchQuery
          };

          history.replaceState(null, null, searchUrl + '?' + encodeForAjax(requestData));

          sendAjaxRequest('GET', searchUrl, requestData, function () {
              if (this.readyState === 4) {
                  if (this.status === 200) {
                    var searchResultsContainer = document.getElementById('suggestions');
                    var contentContainer = document.getElementById('content');
                    var newContent = document.createElement('div');
                    newContent.innerHTML = this.responseText;
                    contentContainer.innerHTML = newContent.querySelector('#content').innerHTML;

                    

                  } else {
                      alert('Error');
                  }
              }
          });
      }
  }

  
});


  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  



/* Edit Comment Pop Up */
function openEditCommentPopUp(editCommentRoute, commentId) {
  document.getElementById('editedComment').value = document.getElementById(`comment-${commentId}`).innerText.trim();
  document.getElementById('editCommentForm').action = editCommentRoute;
  document.getElementById('editCommentModal').style.display = 'block';
}
function closeEditCommentPopUp() {
  document.getElementById('editCommentModal').style.display = 'none';
}

function fetchNotifications() {
    console.log('Fetching notifications...');
    fetch('/notifications')
        .then(response => response.json())
        .then(data => {
            console.log('Notifications data:', data);

            const notificationList = document.getElementById('notificationList');
            const noNotificationsMessage = document.getElementById('noNotifications');

            notificationList.innerHTML = '';

            if (data.length > 0) {
                data.forEach(notification => {
                    const newNotificationItem = document.createElement('li');
                    newNotificationItem.textContent = notification.content;
                    if (notification.id_post !== null) {
                        newNotificationItem.addEventListener('click', () => {
                            const postId = notification.id_post;
                            console.log('Clicked notification, postId:', postId);
                            const postUrl = `/welcome/post/${postId}`;
                            window.location.href = postUrl;
                        });
                    }
            
                    notificationList.appendChild(newNotificationItem);
                });
            } else {
                isDropdownVisible = false;
                noNotificationsMessage.style.display = 'block';
                console.log('No new notifications.');
            }
            })
        .catch(error => {
            console.error('Error fetching notifications:', error);

        });
}
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired on the welcome/search page.');
  
    const searchInput = document.getElementById('searchInput');
    console.log(searchInput); 
  
    const searchResultsContainer = document.getElementById('suggestions');
  
    if (window.location.pathname === '/welcome/search') {
      searchInput.addEventListener('input', function () {
          const query = this.value.trim();
          if (query !== '') {
              fetchSearchResults(query);
          } else {
              clearSearchResults();
          }
      });
  }
  else{
    searchInput.addEventListener('input', function () {
      const query = this.value.trim();
      if (query !== '') {
          fetchSearchResults(query);
      } else {
          clearSearchResults();
      }
  });
  }
  
    function fetchSearchResults(query) {
        const searchResultsUrl = `/search-dropdown?query=${query}`;
        console.log('Fetching results from:', searchResultsUrl); // Debug log
  
        fetch(searchResultsUrl)
            .then(response => response.json())
            .then(data => {
                console.log(data); // DEBUG DEPOIS APAGAR
                updateSearchResults(data);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    }
  
    function updateSearchResults(data) {
        searchResultsContainer.innerHTML = '';
  
        if (data.suggestions.length > 0) {
            const resultsList = document.createElement('ul');
            resultsList.style.listStyleType = 'none';
  
            const displayedResults = data.suggestions.slice(0, 3);
  
            displayedResults.forEach(post => {
                const listItem = document.createElement('li');
                listItem.textContent = post.title;
                listItem.dataset.postId = post.id;
  
                listItem.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    window.location.href = `/welcome/post/${postId}`;
                });
  
                resultsList.appendChild(listItem);
            });
  
            searchResultsContainer.appendChild(resultsList);
            searchResultsContainer.style.display = 'grid';
        } else {
            clearSearchResults();
        }
    }
  
    function clearSearchResults() {
        searchResultsContainer.innerHTML = '';
        searchResultsContainer.style.display = 'none';
    }
    document.addEventListener('click', function(event) {
        const isSearchInputClicked = searchInput.contains(event.target);
        const isSearchResultsClicked = searchResultsContainer.contains(event.target);
        if (!isSearchInputClicked && !isSearchResultsClicked) {
            clearSearchResults();
        }
    });
  });
  

function w3_open() {
  document.getElementById("sidebar").classList.add("expanded");
}

function w3_close() {
  document.getElementById("sidebar").classList.remove("expanded");
}

function confirmDeleteComment() {
    return confirm("Are you sure you want to delete this comment?");
}

function confirmDeletePost(postId) {
    if (confirm("Are you sure you want to delete this post?")) {
        // Redirect to the delete route when confirmed
        window.location.href = "/welcome/post/" + postId + "/delete";
    } else {
        console.log("Deletion canceled");
    }
    return false; // Prevent the default link behavior
}

function confirmDeleteUser(userId) {
    if (confirm("Are you sure you want to delete this user?")) {
        // Redirect to the delete route when confirmed
        window.location.href = "/userManagement/delete/" + userId;
    } else {
        console.log("Deletion canceled");
    }
    return false; // Prevent the default link behavior
}

document.getElementById('imagepath').addEventListener('change', function() {
    var fileSize = this.files[0].size / 1024 / 1024;
    if (fileSize > 2) {
        alert('File size exceeds 2 MB');
        this.value = '';
    }
});

