// admin-users-live-search.js

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('input[name="search"]');
    const usersTableBody = document.querySelector('table tbody');
    const searchForm = searchInput ? searchInput.closest('form') : null;
    let searchTimeout = null;

    if (!searchInput || !usersTableBody) return;

    function doLiveSearch() {
        const query = searchInput.value;
        fetch(`${window.location.pathname}?search=${encodeURIComponent(query)}&ajax=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.text())
            .then(html => {
                usersTableBody.innerHTML = html;
                attachUserActionListeners();
                if (typeof bindEmailPopupButtons === 'function') {
                    bindEmailPopupButtons();
                }
            });
    }

    function attachUserActionListeners() {
        // View User
        document.querySelectorAll('.view-user-btn').forEach(btn => {
            btn.onclick = function(e) {
                e.preventDefault();
                document.getElementById('modalUserName').innerText = this.dataset.name;
                document.getElementById('modalUserEmail').innerText = this.dataset.email;
                document.getElementById('modalUserCountry').innerText = this.dataset.country;
                document.getElementById('modalUserRole').innerText = this.dataset.role;
                document.getElementById('modalUserUploaded').innerText = this.dataset.uploaded;
                document.getElementById('modalUserDownloaded').innerText = this.dataset.downloaded;
                document.getElementById('userInfoModal').style.display = 'flex';
            };
        });
        // Edit User
        document.querySelectorAll('.edit-user-btn').forEach(btn => {
            btn.onclick = function(e) {
                e.preventDefault();
                document.getElementById('editUserId').value = this.dataset.userId;
                document.getElementById('editUserName').value = this.dataset.name;
                document.getElementById('editUserEmail').value = this.dataset.email;
                document.getElementById('editUserCountry').value = this.dataset.country;
                document.getElementById('editUserRole').value = this.dataset.role;
                document.getElementById('editUserModal').style.display = 'flex';
            };
        });
        // Password User
        document.querySelectorAll('.password-user-btn').forEach(btn => {
            btn.onclick = function(e) {
                e.preventDefault();
                document.getElementById('passwordUserId').value = this.dataset.userId;
                document.getElementById('passwordUserName').innerText = this.dataset.userName || this.dataset.name;
                document.getElementById('passwordModal').style.display = 'flex';
            };
        });
        // Suspend User
        document.querySelectorAll('.suspend-user-btn').forEach(btn => {
            btn.onclick = function(e) {
                e.preventDefault();
                const suspended = this.dataset.suspended === '1';
                document.getElementById('suspendUserId').value = this.dataset.userId;
                document.getElementById('suspendAction').value = suspended ? 'activate' : 'suspend';
                document.getElementById('suspendModalTitle').innerText = suspended ? 'Activate User' : 'Suspend User';
                document.getElementById('suspendUserText').innerText = suspended ? 
                    'Are you sure you want to activate this user? They will be able to log in again.' :
                    'Are you sure you want to suspend this user? Once suspended, they cannot login, and the Forgot Password feature will be disabled for them. You can reactivate later.';
                document.getElementById('suspendModalBtn').innerText = suspended ? 'Activate' : 'Suspend';
                document.getElementById('suspendModalBtn').style.background = suspended ? '#22c55e' : '#facc15';
                document.getElementById('suspendUserModal').style.display = 'flex';
            };
        });
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(doLiveSearch, 250);
    });

    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            doLiveSearch();
        });
    }

    // Refresh button logic
    const refreshBtn = document.getElementById('user-search-refresh');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            doLiveSearch();
            if (searchInput) searchInput.focus();
            if (typeof bindEmailPopupButtons === 'function') {
                bindEmailPopupButtons();
            }
        });
    }

    // Attach listeners on page load
    attachUserActionListeners();
});
